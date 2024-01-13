<?php

namespace App\Http\Controllers;

use App\Services\AssignVariant\AbstractAssignVariantStrategy;
use Illuminate\Support\Facades\Session;

class HomeController extends Controller
{
    public function index()
    {
        return view('home', ['abTests' => Session::get(AbstractAssignVariantStrategy::SESSION_TESTS_KEY)]);
    }
}
