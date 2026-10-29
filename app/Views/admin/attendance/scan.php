<?= $this->extend('admin/layout') ?>

<?= $this->section('content') ?>

<div class="header">
    <h1 class="page-title">Scan Absensi</h1>
</div>

<div class="card" style="max-width: 500px; margin: 0 auto; text-align: center;">
    <div id="reader" style="width: 100%;"></div>
    
    <div id="scanResult" style="margin-top: 20px; display: none;">
        <div id="resultMessage" class="alert"></div>
    </div>
    
    <p class="text-muted" style="margin-top: 20px;">
        Arahkan kamera ke QR Code yang ada di layar Admin untuk melakukan Check-In atau Check-Out.
    </p>
</div>

<!-- HTML5-QRCode Library -->
<!-- HTML5-QRCode Library moved to layout -->
<script>
    var html5QrcodeScanner = new Html5QrcodeScanner(
        "reader", { fps: 10, qrbox: 250 });
        
    function onScanSuccess(decodedText, decodedResult) {
        // Handle on success condition with the decoded text or result.
        console.log(`Scan result: ${decodedText}`, decodedResult);
        
        // Stop scanning temporarily
        html5QrcodeScanner.clear();
        
        // Send to server
        fetch('/admin/attendance/process-scan', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': '<?= csrf_hash() ?>' // Ensure CSRF token is available
            },
            body: 'token=' + encodeURIComponent(decodedText) + '&<?= csrf_token() ?>=<?= csrf_hash() ?>'
        })
        .then(response => response.json())
        .then(data => {
            var resultDiv = document.getElementById('scanResult');
            var msgDiv = document.getElementById('resultMessage');
            resultDiv.style.display = 'block';
            
            if (data.status === 'success') {
                msgDiv.className = 'alert alert-success';
                msgDiv.innerHTML = '<strong>Sukses!</strong> ' + data.message;
            } else if (data.status === 'info') {
                msgDiv.className = 'alert alert-info';
                msgDiv.innerHTML = '<strong>Info:</strong> ' + data.message;
            } else {
                msgDiv.className = 'alert alert-error';
                msgDiv.innerHTML = '<strong>Gagal!</strong> ' + data.message;
            }
            
            // Re-initialize scanner after 3 seconds if needed, or leave it clear
            setTimeout(() => {
                location.reload(); // Reload to scan again or update status
            }, 3000);
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Terjadi kesalahan koneksi.');
            location.reload();
        });
    }

    function onScanError(errorMessage) {
        // handle on error condition, with error message
        // console.warn(`Scan error: ${errorMessage}`);
    }

    html5QrcodeScanner.render(onScanSuccess, onScanError);
</script>

<?= $this->endSection() ?>
