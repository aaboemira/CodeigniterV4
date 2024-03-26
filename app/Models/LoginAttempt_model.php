<?php
namespace App\Models;

use CodeIgniter\Model;

class LoginAttempt_model extends Model {
    
    protected $table = 'login_attempts';
    protected $primaryKey = 'id';
    protected $allowedFields = ['ip_address', 'failed_attempts', 'last_attempt_time'];

    // Record a failed login attempt or increment the counter
    public function recordFailedLogin($ipAddress) {
        $attempt = $this->where('ip_address', $ipAddress)->first();
        
        if ($attempt) {
            $attempt['failed_attempts'] += 1;
            $attempt['last_attempt_time'] = date('Y-m-d H:i:s');
            $this->save($attempt);
        } else {
            $this->insert([
                'ip_address' => $ipAddress,
                'failed_attempts' => 1,
                'last_attempt_time' => date('Y-m-d H:i:s'),
            ]);
        }
    }

    // Reset the failed login attempt counter
    public function resetFailedLogin($ipAddress) {
        $this->set([
            'failed_attempts' => 0,
            'last_attempt_time' => date('Y-m-d H:i:s'),
        ])->where('ip_address', $ipAddress)->update();
    }

    // Get the number of failed attempts for an IP
    public function getFailedAttempts($ipAddress) {
        $attempt = $this->where('ip_address', $ipAddress)->first();
        return $attempt ? $attempt['failed_attempts'] : 0;
    }

    // Check if an IP is locked out due to too many failed attempts
    public function isLockedOut($ipAddress) {
        $attempt = $this->where('ip_address', $ipAddress)->first();
        $maxAttempts = 5; // Number of attempts after which login is locked
        $lockoutTime = 900; // Lockout time in seconds (15 minutes here)

        if ($attempt) {
            if ($attempt['failed_attempts'] >= $maxAttempts) {
                $lastAttemptTime = strtotime($attempt['last_attempt_time']);
                $currentTime = time();

                // Check if the lockout time has passed
                if (($currentTime - $lastAttemptTime) < $lockoutTime) {
                    return true;
                } else {
                    // Reset attempts if the lockout time has passed
                    $this->resetFailedLogin($ipAddress);
                    return false;
                }
            }
        }

        return false;
    }
}
