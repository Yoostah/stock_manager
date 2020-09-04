<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\User;
use Image;

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
    public function update(Request $request) {
        $array = ['error' => ''];

        $name  = $request->input('name');
        $email  = $request->input('email');
        $password  = $request->input('password');
        $password_confirm  = $request->input('password_confirm');
        $status  = $request->input('status');

        $user = User::find($this->loggedUser['id']);

        if($name && ($name != $user->name)) {
            $user->name = $name;
            $array['updated'] = true;
        }

        $allowedStatus = [
            config('constants.STATUS.INACTIVE'),
            config('constants.STATUS.ACTIVE'),
            config('constants.STATUS.BLOCKED')
        ];

        if(isset($status) && ($status != $user->status_id)) {
            if(in_array($status, $allowedStatus)){
                $user->status_id = $status;
                $array['updated'] = true;
            }else{
                $array['error'] = 'Status not Allowed!';
                return $array;
            }
        }

        if($email) {
            if($email != $user->email) {
                $emailExists = User::where('email', $email)->count();
                if($emailExists === 0) {
                    $user->email = $email;
                    $array['updated'] = true;
                } else {
                    $array['error'] = 'This email is already in use!';
                    return $array;
                }
            }
        }

        if($password && $password_confirm) {
            if($password === $password_confirm){
                $hash = password_hash($password, PASSWORD_DEFAULT);
                $user->password = $hash;
                $array['updated'] = true;
            } else {
                $array['error'] = 'The password and password confirmation does not match!';
                return $array;
            }
        }

        $user->save();
        return $array;
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

    public function updateAvatar(Request $request) {
        $array = ['error' => ''];

        $allowedTypes = ['image/jpg', 'image/jpeg', 'image/png'];

        $image = $request->file('avatar');

        if($image){
            if(in_array($image->getClientMimeType(), $allowedTypes)) {
                $fileName = md5(time() . rand(0,99999)) . '.jpg';
                $destPath = public_path('/media/avatars');

                $img = Image::make($image->path())
                    ->fit(200,200)
                    ->save($destPath . '/' . $fileName);

                $user = User::find($this->loggedUser['id']);
                $user->avatar = $fileName;
                $user->save();

                $array['url'] = url('/media/avatars/' . $fileName);
            } else {
                $array['error'] = 'File extension not supported!';
                return $array;
            }
        } else {
            $array['error'] = 'The file was not send!';
            return $array;
        }
        return $array;
    }

    public function isAdmin() {
        $user = User::find($this->loggedUser['id']);
        return $user->is_admin;
    }

    public function setAdmin(Request $request) {
        $array = ['error' => ''];

        if($this->isAdmin()){
            $userId = $request->id ;

            $user = User::find($userId);

            if($user) {
                $user->is_admin = !$user->is_admin;
                $user->save();
                return $array;
            } else {
                $array['error'] = 'User does not exits!';
                return $array;
            }

        } else {
            $array['error'] = 'Only Administrators can perform this action!';
            return $array;
        }
    }
}
