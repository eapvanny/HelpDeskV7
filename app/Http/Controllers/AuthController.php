<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'username' => 'required|string', 
            'password' => 'required',
        ]);        

        if ($validator->fails()) {
            return back()->withInput()->withErrors($validator);
        }

        $credentials = $request->only('username', 'password');
        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            return redirect()->route('dashboard.index')->with('success', 'Welcome to AdminPanel.');
        }

        return back()->withInput()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ]);
    }

    public function logout() 
    {
        Auth::logout();

        return redirect()->route('login');
    }
}
