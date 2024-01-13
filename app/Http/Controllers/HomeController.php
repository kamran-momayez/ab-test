<?php

namespace App\Http\Controllers;

use App\Http\Middleware\AssignAbTestVariant;
use Illuminate\Support\Facades\Session;

class HomeController extends Controller
{
    public function index()
    {
        return view('home', ['abTests' => Session::get(AssignAbTestVariant::SESSION_TESTS_KEY)]);
    }
}
