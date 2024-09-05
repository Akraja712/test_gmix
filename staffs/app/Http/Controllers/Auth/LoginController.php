<?php
namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Staffs; // Ensure you include your Staff model


class LoginController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        // Validate the request
        $request->validate([
            'mobile' => 'required|numeric', // Ensure 'mobile' is a number
            'password' => 'required|string', // Ensure 'password' is a plain text string
        ]);
    
        // Extract credentials from the request
        $credentials = $request->only('mobile', 'password');
        
    
        // Attempt to authenticate using the staff guard
        if (Auth::guard('staffs')->attempt($credentials)) {
            // Redirect to intended route or default route
            return redirect()->intended('/');
        }
    
        // If authentication fails, redirect back with an error
        return back()->withErrors(['mobile' => 'Invalid credentials']);
    }
    

    public function logout(Request $request)
    {
        Auth::guard('staffs')->logout();
        return redirect('/login');
    }
}
