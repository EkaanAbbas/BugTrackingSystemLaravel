<?php

// app/Http/Controllers/Auth/LoginController.php

// app/Http/Controllers/Auth/LoginController.php

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
        $credentials = $request->only('email', 'password');
    
        if (Auth::attempt($credentials)) {
            return redirect()->route('dashboard');
        }
    
        return redirect()->back()->withErrors(['email' => 'Invalid credentials.'])->withInput();
    }
    

    public function logout(Request $request)
    {
        Auth::logout();  // Log out the user
        return redirect()->route('login'); // Redirect to the login page after logout
    }

    protected function redirectTo()
    {
        return '/dashboard';
    }
}
