<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use Inertia\Response;
use Nexus\Facades\Page\Snackbar;
use Nexus\Http\Controllers\BaseController;

class HomeController extends BaseController
{
    public function index(): Response
    {
        Snackbar::text('Hello World!')->success()->icon('bi-check-circle-fill')->flash();
        return Inertia::render('Home');
    }
}
