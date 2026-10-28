<?php

namespace App\Filters;

use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\Filters\FilterInterface;
use App\Models\ApiTokenModel;

/**
 * ApiAuth Filter
 *
 * Validates Bearer tokens for mobile API requests.
 * Attaches the authenticated user object to the request
 * as a custom property for controllers to access.
 *
 * Usage in Routes.php:
 *   $routes->group('api/v1', ['filter' => 'apiAuth'], function($routes) { ... });
 */
class ApiAuth implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        $authHeader = $request->getHeaderLine('Authorization');

        // Expect: "Bearer <token>"
        if (!$authHeader || !str_starts_with($authHeader, 'Bearer ')) {
            return service('response')
                ->setStatusCode(401)
                ->setJSON([
                    'status'  => 'error',
                    'message' => 'Missing or invalid Authorization header',
                    'code'    => 401,
                ]);
        }

        $token = substr($authHeader, 7); // strip "Bearer "

        $tokenModel = new ApiTokenModel();
        $user       = $tokenModel->validateToken(trim($token));

        if (!$user) {
            return service('response')
                ->setStatusCode(401)
                ->setJSON([
                    'status'  => 'error',
                    'message' => 'Token expired or invalid. Please login again.',
                    'code'    => 401,
                ]);
        }

        // Check user active status
        if ($user->status != '1') {
            return service('response')
                ->setStatusCode(403)
                ->setJSON([
                    'status'  => 'error',
                    'message' => 'Account is not active.',
                    'code'    => 403,
                ]);
        }

        // Attach authenticated user to request globals so controllers can read it
        // We use a singleton pattern via a global helper-style approach
        \Config\Services::request()->setGlobal('auth_user', $user);
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // Add CORS headers for mobile clients
        $response->setHeader('Access-Control-Allow-Origin', '*');
        $response->setHeader('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, OPTIONS');
        $response->setHeader('Access-Control-Allow-Headers', 'Content-Type, Authorization');
    }
}
