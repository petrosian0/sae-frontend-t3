<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'login_name' => 'required|string',
            'password' => 'required|string',
        ]);
    
        if (Auth::attempt(['login_name' => $credentials['login_name'], 'password' => $credentials['password']])) {
            // Authentication was successful...
            return redirect()->intended('/home');
        }
    
        return back()->withErrors([
            'login_name' => 'The provided credentials do not match our records.',
        ]);
    }
    
    public function logout(Request $request)
    {
        Auth::logout();
        
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login');
    }

}
