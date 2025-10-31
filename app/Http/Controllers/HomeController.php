<?php

namespace App\Http\Controllers;

class HomeController extends Controller
{

    public function index()
    {

    $films = 1;
    return view('index', compact('films'));
    }

    public function usuarios() {

}

}
