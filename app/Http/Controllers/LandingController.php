<?php

namespace App\Http\Controllers;

class LandingController extends Controller
{
    /**
     * Página principal pública.
     */
    public function __invoke()
    {
        return view('welcome');
    }
}
