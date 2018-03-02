<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    //

    /**
     * Login function
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Symfony\Component\HttpFoundation\Response
     */
    public function login()
    {
        $rq = \request();
        $result = [];
        if ($rq->method() != 'POST')
            return response()->setStatusCode('401')->json(['error', 'method not allowed']);

        $posts = [
            'email'     => $rq->post('email'),
            'password'     => $rq->post('password'),
        ];

        $rules = [
            'email'     => 'required|email',
            'password'  => 'required'
        ];

        $valid = Validator::make($posts, $rules);

        // valid fail or not
        if ($valid->fails())
        {
            $result['error'] = 'Please check your data again!';
        }
        else {
            // login now
            $user = User::where('email', $posts['email'])->first();

            if ($user != null)
            {
                $result['error'] = "Login failed, this email is not exists!";
            }
            else {
                if (Hash::make($posts['password']) == $user->password) {
                    // login ok
                    $result['data'] = [
                        'msg' => 'Login ok!',
                        'user_data' => $user
                    ];
                }
                else {
                    $result['error'] = "Login failed, this email is not exists!";
                }
            }
        }

        return response()->json($result);
    }

    /**
     * Register a new user
     */
    public function register()
    {
        $rq = \request();
        $result = [];
        if ($rq->method() != 'POST')
            return response()->setStatusCode('401')->json(['error', 'method not allowed']);

        // validator
        $posts = [
            'email'     => $rq->post('email'),
            'password'     => $rq->post('password'),
            'retype_password'   => $rq->post('retype_password'),
            'name'  => $rq->post('name')
        ];

        $rules = [
            'email' => 'required|email|unique:users,email',
            'password'  => 'required',
            'retype_password'  => 'required|same:password',
            'name'      => 'required'
        ];

        $valid = Validator::make($posts, $rules);

        if ($valid->fails())
        {
            $result['error'] = 'Please check again your fields!';
        }
        else {
            $user = new User;
            $user->name = $posts['name'];
            $user->email = $posts['email'];
            $user->password = $posts['password'];
            $user->save();

            if($user->id > 0)
                $result['success'] = 'Registered successfully!';
            else
                $result['error'] = 'Failed to register!';
        }

        return response()->json($result);
    }
}
