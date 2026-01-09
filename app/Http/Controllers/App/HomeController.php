<?php

namespace App\Http\Controllers\App;

use Illuminate\Routing\Controller;

class HomeController extends Controller
{
    public function index()
    {
        return view('app.pages.home');
    }
}
