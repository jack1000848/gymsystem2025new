<?php

namespace App\Filters;

use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\Filters\FilterInterface;
use App\Models\CustomerModel;

class FrozenCheck implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        $customerID = session()->get('CustomerID');
        if ($customerID) {
            $model = new CustomerModel();
            $client = $model->find($customerID);
            if ($client && $client['is_frozen']) {
                session()->destroy();
                return redirect()->to('/login')->with('error', 'Your account is frozen. Contact admin.');
            }
        }
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // Nothing
    }
}
