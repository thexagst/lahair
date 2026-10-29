<?php

namespace App\Models;

use CodeIgniter\Model;

class SavedCustomerModel extends Model
{
    protected $table            = 'saved_customers';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = ['name', 'phone', 'birthdate', 'order_count'];

    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    /**
     * Upsert customer: insert new or increment order_count if phone exists.
     * Also updates name and birthdate if provided.
     */
    public function upsertCustomer(string $name, string $phone, ?string $birthdate = null): void
    {
        $existing = $this->where('phone', $phone)->first();

        $data = [
            'name' => $name,
        ];
        if (!empty($birthdate)) {
            $data['birthdate'] = $birthdate;
        }

        if ($existing) {
            $data['order_count'] = $existing['order_count'] + 1;
            $this->update($existing['id'], $data);
        } else {
            $data['phone']       = $phone;
            $data['order_count'] = 1;
            $this->insert($data);
        }
    }

    /**
     * Search customers by name or phone for autocomplete.
     */
    public function search(string $query, int $limit = 15): array
    {
        $saved = $this->groupStart()
                      ->like('name', $query)
                      ->orLike('phone', $query)
                      ->groupEnd()
                      ->orderBy('order_count', 'DESC')
                      ->orderBy('updated_at', 'DESC')
                      ->limit($limit)
                      ->findAll();

        $phones = array_filter(array_column($saved, 'phone'));

        $db = \Config\Database::connect();
        $builder = $db->table('orders');
        $builder->select('customer_name as name, customer_phone as phone, customer_birthdate as birthdate, COUNT(*) as order_count')
                ->where('customer_name !=', '')
                ->where('customer_phone !=', '')
                ->groupStart()
                    ->like('customer_name', $query)
                    ->orLike('customer_phone', $query)
                ->groupEnd()
                ->groupBy('customer_phone, customer_name, customer_birthdate')
                ->orderBy('order_count', 'DESC')
                ->limit($limit);

        if (!empty($phones)) {
            $builder->whereNotIn('customer_phone', $phones);
        }

        $orderCustomers = $builder->get()->getResultArray();
        $all = array_merge($saved, $orderCustomers);
        usort($all, fn($a, $b) => ($b['order_count'] ?? 0) <=> ($a['order_count'] ?? 0));

        return array_slice($all, 0, $limit);
    }

    /**
     * Get all customers ordered by most frequent.
     */
    public function getAllSorted(int $limit = 200): array
    {
        $saved = $this->orderBy('order_count', 'DESC')
                      ->orderBy('updated_at', 'DESC')
                      ->limit($limit)
                      ->findAll();

        $phones = array_filter(array_column($saved, 'phone'));

        $db = \Config\Database::connect();
        $builder = $db->table('orders');
        $builder->select('customer_name as name, customer_phone as phone, customer_birthdate as birthdate, COUNT(*) as order_count')
                ->where('customer_name !=', '')
                ->where('customer_phone !=', '')
                ->groupBy('customer_phone, customer_name, customer_birthdate')
                ->orderBy('order_count', 'DESC')
                ->limit($limit);

        if (!empty($phones)) {
            $builder->whereNotIn('customer_phone', $phones);
        }

        $orderCustomers = $builder->get()->getResultArray();
        $all = array_merge($saved, $orderCustomers);
        usort($all, fn($a, $b) => ($b['order_count'] ?? 0) <=> ($a['order_count'] ?? 0));

        return array_slice($all, 0, $limit);
    }

    /**
     * Get upcoming customer birthdays within next $days days (0 = today)
     */
    public function getUpcomingBirthdays(int $days = 7): array
    {
        $customers = $this->where('birthdate IS NOT NULL')
                          ->where('birthdate !=', '0000-00-00')
                          ->findAll();

        $phones = array_filter(array_column($customers, 'phone'));
        $db = \Config\Database::connect();
        $builder = $db->table('orders');
        $builder->select('customer_name as name, customer_phone as phone, customer_birthdate as birthdate, COUNT(*) as order_count')
                ->where('customer_birthdate IS NOT NULL')
                ->where('customer_birthdate !=', '0000-00-00')
                ->where('customer_name !=', '')
                ->where('customer_phone !=', '')
                ->groupBy('customer_phone, customer_name, customer_birthdate');
        if (!empty($phones)) {
            $builder->whereNotIn('customer_phone', $phones);
        }
        $orderBirthdays = $builder->get()->getResultArray();
        $customers = array_merge($customers, $orderBirthdays);

        $result = [];
        $today = new \DateTime(date('Y-m-d'));
        $todayYear = (int)$today->format('Y');

        foreach ($customers as $c) {
            if (empty($c['birthdate'])) continue;
            try {
                $bdate = new \DateTime($c['birthdate']);
                $bCurrent = new \DateTime(sprintf('%04d-%02d-%02d', $todayYear, (int)$bdate->format('m'), (int)$bdate->format('d')));
                
                // If passed this year, roll over to next year
                if ($bCurrent < $today) {
                    $bCurrent = new \DateTime(sprintf('%04d-%02d-%02d', $todayYear + 1, (int)$bdate->format('m'), (int)$bdate->format('d')));
                }

                $diffDays = (int)$today->diff($bCurrent)->format('%r%a');
                if ($diffDays >= 0 && $diffDays <= $days) {
                    $c['days_until_birthday'] = $diffDays;
                    $c['birthday_formatted'] = $bdate->format('d M');
                    $c['birth_year'] = (int)$bdate->format('Y');
                    $c['age_turning'] = (int)$bCurrent->format('Y') - (int)$bdate->format('Y');
                    $result[] = $c;
                }
            } catch (\Exception $e) {}
        }

        usort($result, function($a, $b) {
            if ($a['days_until_birthday'] === $b['days_until_birthday']) {
                return strcmp($a['name'], $b['name']);
            }
            return $a['days_until_birthday'] <=> $b['days_until_birthday'];
        });

        return $result;
    }
}
