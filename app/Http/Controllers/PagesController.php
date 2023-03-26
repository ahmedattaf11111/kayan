<?php

namespace App\Http\Controllers;

class PagesController extends Controller
{

    public function index()
    {
        return view('welcome');
    }
    
    public function admin()
    {
        return view('admin');
    }
    
}
