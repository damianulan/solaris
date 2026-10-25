<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use Illuminate\Contracts\Support\Responsable;
use Inertia\Inertia;
use Nexus\Http\Controllers\BaseController;

class HomeController extends BaseController
{
    public function index(): Responsable
    {
        return Inertia::render('Home');
    }
}
