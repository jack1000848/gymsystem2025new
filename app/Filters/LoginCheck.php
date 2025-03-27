<?php

namespace App\Filters;

use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\Filters\FilterInterface;

class LoginCheck implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        // Check if user is not logged in
        if (!session()->get('isLoggedIn')) {  // Check if user is logged in
            return redirect()->to('/member-login')->with('error', 'You must be logged in.');
        }
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // Prevent back button access after logout
        $response->setHeader('Cache-Control', 'no-store, no-cache, must-revalidate, max-age=0');
        $response->setHeader('Pragma', 'no-cache');
    }
}
