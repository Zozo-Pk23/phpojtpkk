<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class UserController extends Controller
{
    public function createuser(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required',
            'email' =>  'required|email:rfc',
            'password' => [
                'required_with:confirmpassword',
                'same:password_confirmation',
                'min:6',
                'regex:/^.*(?=.{3,})(?=.*[a-zA-Z])(?=.*[0-9])(?=.*[\d\x])(?=.*[!$#%]).*$/',
            ],
        ]);
    }
}
