<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class RegisterController extends Controller
{
    use RegistersUsers;

    public function __construct()
    {
        $this->middleware('auth');
    }

    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => ['required', 'string', 'max:255'],
            'username' => ['required', 'string', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'level_id' => ['required', 'exists:levels,id'],
        ]);
    }

    protected function create(array $data)
    {
        $this->validator($data)->validate();

        $user = User::create([
            'nama_user' => $data['name'],
            'username' => $data['username'],
            'password' => Hash::make($data['password']),
            'id_level' => $data['level_id'],
        ]);

        return redirect()->route('masakan')->with('success', 'User added successfully');
    }
}
