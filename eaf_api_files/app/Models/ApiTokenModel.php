<?php

namespace App\Models;

use CodeIgniter\Model;

/**
 * ApiTokenModel
 *
 * Manages API authentication tokens for mobile app.
 * Tokens are stored in the `user_tokens` table and validated
 * on every API request via the ApiAuth filter.
 */
class ApiTokenModel extends Model
{
    protected $table            = 'user_tokens';
    protected $primaryKey       = 'token_id';
    protected $returnType       = 'object';
    protected $useAutoIncrement = true;
    protected $useTimestamps    = false;

    protected $allowedFields = [
        'user_code',
        'token',
        'device_info',
        'created_at',
        'expires_at',
        'last_used_at',
    ];

    /**
     * Generate and store a new token for a user.
     *
     * @param string $user_code
     * @param string $device_info optional device string
     * @return string the new plain token (store on mobile side)
     */
    public function createToken(string $user_code, string $device_info = ''): string
    {
        $token = bin2hex(random_bytes(32)); // 64-char hex token

        $this->insert([
            'user_code'    => $user_code,
            'token'        => hash('sha256', $token),
            'device_info'  => $device_info,
            'created_at'   => date('Y-m-d H:i:s'),
            'expires_at'   => date('Y-m-d H:i:s', strtotime('+30 days')),
            'last_used_at' => date('Y-m-d H:i:s'),
        ]);

        return $token;
    }

    /**
     * Validate a token and return the associated user record.
     *
     * @param string $token plain token from Authorization header
     * @return object|null
     */
    public function validateToken(string $token): ?object
    {
        $hashed = hash('sha256', $token);

        $db = \Config\Database::connect();

        $row = $db->table('user_tokens ut')
            ->select('u.*, ut.token_id, ut.expires_at')
            ->join('users u', 'u.user_code = ut.user_code', 'inner')
            ->where('ut.token', $hashed)
            ->where('ut.expires_at >', date('Y-m-d H:i:s'))
            ->get()
            ->getRow();

        if ($row) {
            $this->where('token', $hashed)
                ->set(['last_used_at' => date('Y-m-d H:i:s')])
                ->update();
        }

        return $row ?: null;
    }

    /**
     * Revoke a specific token (logout on current device).
     */
    public function revokeToken(string $token): void
    {
        $this->where('token', hash('sha256', $token))->delete();
    }

    /**
     * Revoke all tokens for a user (force logout all devices).
     */
    public function revokeAllForUser(string $user_code): void
    {
        $this->where('user_code', $user_code)->delete();
    }
}
