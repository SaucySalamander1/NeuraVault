<?php

namespace App\Http\Controllers;

class HomeController extends Controller
{
    public function landing()
    {
        return view('landing');
    }

    public function about()
    {
        return view('about');
    }
}