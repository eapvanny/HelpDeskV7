<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
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
            return redirect()->route('dashboard.index')->with('success', 'Welcome to AdminPanel.')->with('show_popup', true);
        }

        return back()->withInput()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ]);
    }

    public function forgetPassword()
    {
        return view('backend.user.forget-password');
    }

    public function forgetPasswordPost(Request $request)
    {
        $request->validate([
            'email' => "required|email|exists:users",
        ]);

        $token = Str::random(16);

        DB::table('password_resets')->insert([
            'email' => $request->email,
            'token' => $token,
            'created_at' => Carbon::now(),
        ]);

        Mail::send("backend.email.forget-password", ['token' => $token], function ($message) use ($request) {
            $message->to($request->email);
            $message->subject("Reset Password");
        });

        return redirect()->to(route('forget.password'))->with('success', 'We have send an email to reset password');
    }

    public function resetPassword($token)
    {
        return view('backend.user.new-password', compact('token'));
    }

    public function resetPasswordPost(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users',
            'password' => 'required|string|min:6',
            'password_confirmation' => 'required|same:password',
        ]);
        

        $updatePassword = DB::table('password_resets')
            ->where([
                'email' => $request->email,
                'token' => $request->token,
            ])->first();

        if(!$updatePassword) {
            return redirect()->to(route('reset.password'))->with('error','Invalid');
        }

        User::where('email', $request->email)
            ->update(['password'=> Hash::make($request->password)]);

        DB::table('password_resets')
            ->where(['email' => $request->email])
            ->delete();

        return redirect()->to(route('login'))->with('succcess', 'Password reset successfully');
    }

    public function logout()
    {
        Auth::logout();

        return redirect()->route('login');
    }
}
