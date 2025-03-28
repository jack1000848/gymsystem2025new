<?php

namespace App\Controllers;
use App\Models\CustomerModel;

class Home extends BaseController
{
/*************  ✨ Codeium Command ⭐  *************/
    /**
     * Displays the frontend view.
     *
     * @return string The rendered frontend view.
     */

/******  31585f8d-a9b7-4d68-a87a-9d385a4150a1  *******/
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
