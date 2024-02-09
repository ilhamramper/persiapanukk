<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    use AuthenticatesUsers;

    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    public function username()
    {
        return 'username';
    }

    protected function redirectTo()
    {
        $user = auth()->user();

        if ($user->id_level == 1) {
            return '/order';
        } elseif ($user->id_level == 2 || $user->id_level == 3) {
            return '/user';
        }

        return '/user';
    }

    protected function authenticated(Request $request, $user)
    {
        if ($user->id_level == 1) {
            return redirect('/order');
        }

        return redirect($this->redirectTo());
    }
}
