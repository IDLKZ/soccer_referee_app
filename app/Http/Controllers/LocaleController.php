<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;

class LocaleController extends Controller
{
    /**
     * Switch application locale
     *
     * @param string $locale
     * @return \Illuminate\Http\RedirectResponse
     */
    public function switch(string $locale)
    {
        // Validate locale
        if (!in_array($locale, ['ru', 'kk', 'en'])) {
            abort(400, 'Invalid locale');
        }

        // Set locale in session
        Session::put('locale', $locale);

        // Set application locale
        App::setLocale($locale);

        return redirect()->back();
    }
}
