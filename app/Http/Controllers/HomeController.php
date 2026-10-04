<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Session;
use Inertia\Inertia;
use Inertia\Response;
use Nexus\Http\Controllers\BaseController;

class HomeController extends BaseController
{
    public function index(): Response
    {

        return Inertia::render('Home');
    }
}
