<?php

namespace App\Http\Controllers;

use App\Http\Middleware\AbTestMiddleware;
use Illuminate\Support\Facades\Session;

class HomeController extends Controller
{
    public function index()
    {
        // I sent
        return view('home', [
            'abTestName' => Session::get(AbTestMiddleware::SESSION_TEST_NAME_KEY),
            'variantName' => Session::get(AbTestMiddleware::SESSION_VARIANT_NAME_KEY)
            ]);
    }
}
