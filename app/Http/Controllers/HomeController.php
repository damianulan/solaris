<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\DataTables\Users\UsersDataTable;
use Illuminate\Contracts\Support\Responsable;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Nexus\Http\Controllers\BaseController;

class HomeController extends BaseController
{
    public function index(Request $request): Responsable
    {
        return Inertia::render('Home', [
            'table' => new UsersDataTable($request)->toArray(),
        ]);
    }
}
