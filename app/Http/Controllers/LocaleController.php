<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;

class LocaleController extends Controller
{
    public function index(string $locale): RedirectResponse
    {
        \App::setLocale($locale);

        \Session::put('locale', $locale);

        return redirect()->back();
    }
}
