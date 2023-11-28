<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\URL;

class TwoFactorAuth
{
    /**
     * Handle an incoming request.
     *
     * @param Request $request
     * @param  \Closure  $next
     * @return Response|RedirectResponse|null
     */
    public function handle(Request $request, Closure $next)
    {
        if (! $request->user() || ! $request->user()->hasEnabledTwoFactorAuthentication()) {
            return $request->expectsJson()
                ? abort(403, 'This URL requires 2FA to be configured')
                : Redirect::to(URL::route('2fa-setup'));
        }
        return $next($request);
    }
}
