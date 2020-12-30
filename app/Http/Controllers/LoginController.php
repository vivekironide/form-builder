<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class LoginController extends Controller
{
    public function index()
    {
        return view('login');
    }

    public function logged( Request $request )
    {
        $user = User::where('email', $request->get( 'email'))->first();

        if( is_null( $user) ) {
            return back()->withErrors([
                                   'msg' => ['This email is not registered in our system.']
                               ]);
        }

        if( Hash::check( $request->get( 'password' ), $user->password) ) {
            return back()->withErrors([
                                          'msg' => ['Incorrect password.']
                                      ]);
        }

        Auth::login($user);

        return redirect()->intended('ticket.list');
    }

    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}
