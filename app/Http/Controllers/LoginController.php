<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function index()
    {
        return view('login');
    }

    public function logged( Request $request )
    {
        $user = User::where('email', $request->get( 'email'))->where('password', $request->get( 'password'))->first();

        if( is_null( $user) ) {
            return back()->withErrors([
                                   'msg' => ['Please check the email/password.']
                               ]);
        }

        Auth::login($user);

        return redirect()->intended('ticket.list');
    }
}
