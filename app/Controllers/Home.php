<?php

namespace App\Controllers;

class Home extends BaseController
{
    public function index(): string
    {
        return view('frontend');
    }
    public function joinus() 
    {
        return view('joinus');
    }



    public function admindashboard() 
    {
        return view('admindashboard/admindashboard');
    }

    
}
