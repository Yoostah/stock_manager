<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\User;

class UserController extends Controller
{
    private $loggedUser;

    public function __construct() {
        $this->middleware('auth:api', ['except' => ['store']]);
        $this->loggedUser = auth()->user();
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create() {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request) {

        $array = ['error' => ''];

        $name  = $request->input('name');
        $email  = $request->input('email');
        $password  = $request->input('password');

        if( $name && $email && $password) {
            $userExists = User::where('email', $email)->count();
            if($userExists === 0){

                $hash = password_hash($password, PASSWORD_DEFAULT);

                $newUser = new User();
                $newUser->name = $name;
                $newUser->email = $email;
                $newUser->password = $hash;
                $newUser->status_id = config('constants.STATUS.ACTIVE');
                $newUser->save();

                $token = auth()->attempt([
                    'email' => $email,
                    'password' => $password
                    ]);
                if(!$token){
                    $array['error'] = 'An Error Occurred!';
                    return $array;
                }
                $array['token'] = $token;

            } else {
                $array['error'] = 'Email already in use!';
                return $array;
            }
        } else {
            $array['error'] = 'All fields as required!';
        }

        return $array;
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id) {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id) {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id) {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id) {
        //
    }
}
