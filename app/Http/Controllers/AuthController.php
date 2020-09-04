<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function __construct() {
        $this->middleware('auth:api', ['except' => ['login','unauthorized']]);
    }

    public function unauthorized() {
        return response()->json(['error' => 'Unauthorized Access!'], 401);
    }

    public function login(Request $request) {
        $array = ['error' => ''];

        $email  = $request->input('email');
        $password  = $request->input('password');

        if( $email && $password) {
            $token = auth()->attempt([
                'email' => $email,
                'password' => $password
                ]);
            if(!$token){
                $array['error'] = 'The Access Credentials are not valid!';
                return $array;
            }

            $array['token'] = $token;

        } else {
            $array['error'] = 'All fields as required!';
        }

        return $array;

    }
    public function logout() {
        auth()->logout();
        return ['error' => ''];
    }

    public function refresh() {
        $token = auth()->refresh();
        return ['error' => '' , 'token' => $token];
    }
}
