<?php

namespace App\Http\Controllers;

use App\Actions\Fortify\PasswordValidationRules;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Inertia\Inertia;

class SetPasswordController extends Controller
{
    use PasswordValidationRules;
    public function show(User $user, Request $request)
    {
        return Inertia::render('Auth/SetPassword');
    }

    public function store(Request $request):RedirectResponse
    {
        $input = $request->toArray();
        Validator::make($input, [
            'password' => $this->passwordRules(),
        ], [
            'password_confirmation.password_confirmation' => __('Passwords must match'),
        ])->validateWithBag('updatePassword');

        $user = $request->user();
        $user->password = bcrypt($request->password);
        $user->has_temporary_password = false;
        $user->update();

        return to_route('login');
    }
}
