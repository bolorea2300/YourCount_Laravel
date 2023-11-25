<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class UserController extends Controller
{
    function register(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:30'],
            'email' => ['required', 'string', 'email', 'max:50', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'max:24', 'regex:/^(?=.*?[a-z])(?=.*?[A-Z])(?=.*?\d)[a-zA-Z\d]{8,24}$/', 'confirmed'],
        ]);

        $name = $request->name;
        $email = $request->email;
        $password = $request->password;

        User::create([
            'name' => $name,
            'email' => $email,
            'password' => Hash::make($password)
        ]);

        if (Auth::attempt(['email' => $email, 'password' => $password])) {
            return response()->json(Auth::user(), 200);
        } else {
            return response()->json("", 500);
        }
    }

    function login(Request $request)
    {
        $validated = $request->validate([
            'email' => ['required', 'string', 'email', 'max:50'],
            'password' => ['required', 'string', 'min:8'],
        ]);

        $email = $request->email;
        $password = $request->password;

        if (Auth::attempt(['email' => $email, 'password' => $password])) {
            return response()->json(Auth::user(), 200);
        } else {
            return response()->json("", 500);
        }
    }

    function name(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:30'],
        ]);

        $name = $request->name;

        $user = Auth::user();
        $user->name = $name;
        $user->save();

        return response()->json('', 200);
    }

    function password(Request $request)
    {
        $validated = $request->validate([
            'old_password' => ['required', 'current_password'],
            'password' => ['required', 'string', 'min:8', 'max:24', 'regex:/^(?=.*?[a-z])(?=.*?[A-Z])(?=.*?\d)[a-zA-Z\d]{8,24}$/', 'confirmed'],
        ]);

        $user = Auth::user();
        $user->password = Hash::make($request->password);
        $user->save();
    }

    function delete(Request $request)
    {
        $validated = $request->validate([
            'password' => ['required', 'current_password'],
        ]);

        $user_id = Auth::id();

        $user = User::where('id', $user_id)->first();

        if (!$user) {
            return response()->json('', 500);
        }

        $user->delete();

        return response()->json('', 200);
    }
}
