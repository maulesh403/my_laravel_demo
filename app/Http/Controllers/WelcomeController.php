<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class WelcomeController extends Controller
{
    //Controller default method if we call controller from route without any method pass
    public function __invoke(){
        return view('welcome');
    }
}
