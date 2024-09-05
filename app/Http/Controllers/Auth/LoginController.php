<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Admin; // Make sure to include your Admin model

class LoginController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->only('first_name', 'password');

        // Attempt login
        if (Auth::guard('web')->attempt($credentials)) {
            $user = Auth::guard('web')->user();

            if ($user->role === 'admin') {
                return redirect()->intended('/'); // Redirect to staff dashboard
            } else {
                Auth::logout();
                return redirect('/login')->withErrors(['first_name' => 'Unauthorized role.']);
            }
        }

        return back()->withErrors(['first_name' => 'Invalid credentials']);
    }

    public function logout(Request $request)
    {
        Auth::guard('web')->logout();
        return redirect('/login');
    }
}
