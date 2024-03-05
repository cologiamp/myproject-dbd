<?php

namespace App\Http\Controllers;

use App\Actions\Fortify\PasswordValidationRules;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Inertia\Inertia;

class SetPasswordController extends Controller
{
    use PasswordValidationRules;
    public function show(User $user, Request $request)
    {
        return Inertia::render('SetPassword');
    }

    public function store(User $user, Request $request): void
    {
        $input = $request->toArray();
        Validator::make($input, [
            'password' => $this->passwordRules(),
        ], [
            'password_confirmation.password_confirmation' => __('Passwords must match'),
        ])->validateWithBag('updatePassword');

        $update_user = User::find($request->user()->id);
        $update_user->password = bcrypt($request->password);
        $update_user->has_temporary_password = false;
        $update_user->update();
    }
}
