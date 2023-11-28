<?php

namespace App\Http\Responses;

use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;

class LoginResponse implements \Laravel\Fortify\Contracts\LoginResponse
{

    public function toResponse($request):RedirectResponse
    {
        if(Auth::user()->two_factor_confirmed_at)
        {
            return redirect()->to('dashboard');
        }
        return redirect()->to('2fa-setup');
    }
}
