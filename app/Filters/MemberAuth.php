<?php

namespace App\Filters;

use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\Filters\FilterInterface;

class MemberAuth implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        // ❌ Not logged in member
        if (!session()->get('member_id') && !session()->get('is_member') && !session()->get('user_id')) {
            return redirect()->to(base_url('/'));
        }
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // optional
    }
}
