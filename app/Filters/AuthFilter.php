<?php

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use App\Models\AdminModel;

class AuthFilter implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        if (!session()->get('isLoggedIn')) {
            // Check remember token cookie
            helper('cookie');
            $token = get_cookie('remember_token');
            if ($token) {
                $adminModel = new AdminModel();
                $admin = $adminModel->getByRememberToken($token);
                if ($admin) {
                    $data = [
                        'admin_id'   => $admin['id'],
                        'username'   => $admin['username'],
                        'role'       => $admin['role'] ?? 'admin', // Add role to session
                        'isLoggedIn' => true,
                    ];
                    if (!empty($admin['branch_id'])) {
                        $branch = (new \App\Models\BranchModel())->find($admin['branch_id']);
                        if ($branch) {
                            $data['active_branch_id']   = (int)$branch['id'];
                            $data['active_branch_name'] = $branch['name'];
                            $data['active_branch_code'] = $branch['code'];
                        }
                    }
                    session()->set($data);
                } else {
                    return redirect()->to('/login')->with('error', 'Silakan login terlebih dahulu');
                }
            } else {
                return redirect()->to('/login')->with('error', 'Silakan login terlebih dahulu');
            }
        }

        // Enforce branch selection for admin area:
        // When first opening admin, user must choose branch immediately
        $uri = service('uri');
        $path = trim($uri->getPath(), '/');
        $path = preg_replace('#^index\.php/?#i', '', $path);
        
        $exemptPaths = ['admin/select-branch', 'admin/switch-branch', 'logout'];
        $isExempt = false;
        foreach ($exemptPaths as $exempt) {
            if ($path === $exempt || str_starts_with($path, $exempt . '/')) {
                $isExempt = true;
                break;
            }
        }

        if (!$isExempt && !session()->has('active_branch_id')) {
            if (!$request->isAJAX()) {
                return redirect()->to('/admin/select-branch');
            }
        }
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // Do something here
    }
}
