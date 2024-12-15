<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;

class CoachController extends BaseController
{
    public function index()
    {
        return view ("coach", $data);
    }
}
