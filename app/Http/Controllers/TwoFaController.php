<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

class TwoFaController extends Controller
{
    public function show()
    {
        if(Auth::user()->hasEnabledTwoFactorAuthentication())
        {
            return redirect()->to('dashboard');
        }
        return Inertia::render('2FA', [
            'halfPageImage' => config('constants.loginImage'),
        ]);
    }

    public function store()
    {

    }
}
