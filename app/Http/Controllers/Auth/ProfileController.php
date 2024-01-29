<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\UpdatePasswordRequest;
use App\Http\Requests\UpdateProfileRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ProfileController extends Controller
{
    public function edit():View
    {
        return view('auth.passwords.edit');
    }

    public function update(UpdatePasswordRequest $request):RedirectResponse
    {
        auth()->user()->update($request->validated());

        return redirect()->route('admin.password.edit')->with('message', 'Password Changed');
    }

    public function updateProfile(UpdateProfileRequest $request):RedirectResponse
    {
        $user = auth()->user();

        $user->update($request->validated());

        return redirect()->route('admin.password.edit')->with('message', 'Profile Updated');
    }

    public function destroy():RedirectResponse
    {
        $user = auth()->user();

        $user->update([
            'email' => time() . '_' . $user->email,
        ]);

        $user->delete();

        return redirect()->route('login')->with('message', 'Account Deleted');
    }
}
