<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use Illuminate\View\View;

class AdminController
{
    public function index(): View
    {
        return view('admin.dashboard');
    }
}
