<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use Inertia\Response;
use Nexus\Facades\Nav\Sidebar;

class HomeController extends Controller
{
    public function index(): Response
    {
        dd(Sidebar::getInstance(), request()->path(), Route::currentRouteName());
        return Inertia::render('Home');
    }
}
