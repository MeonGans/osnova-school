<?php

namespace App\Http\Controllers;

use App\Models\Admin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class RegisterController extends Controller
{
    /**
     * Register api
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'login' => 'required',
        ]);

        if($validator->fails()){
            return $this->sendError('Validation Error.', $validator->errors());
        }

        $input = $request->all();
        $input['password'] = bcrypt('osnova_admin');
        $admin = Admin::query()->create($input);
        $success['login'] =  $admin->login;

        return $this->sendResponse($success, 'Admin register successfully.');
    }

    /**
     * Login api
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(Request $request)
    {
        if(Auth::attempt(['login' => $request->login, 'password' => $request->password] )){
            $user = Auth::user();
            $success['token'] =  $user->createToken('osnova')-> accessToken;
            $success['login'] =  $user->login;

            return $this->sendResponse($success, 'Admin login successfully.');
        }
        else{
            return $this->sendError('Unauthorised.', ['error'=>'Unauthorised']);
        }
    }

    public function logout()
    {
        $user = Auth::guard('api')->user()->token();
        $user->revoke();
        $success['login'] = $user->login;
        return $this->sendResponse($success, 'User logout successfully.');
        //return UserResource::collection($user);
        //   return $user->json;
//        if (Auth::check()) {
//            $user = Auth::user()->token();
//            $user->revoke();
//            $success['name'] = $user->name;
//            return $this->sendResponse($success, 'User logout successfully.');
//        }
//        else{
//            return $this->sendError('Unauthorised.', ['error'=>'Unauthorised']);
//        }
    }
}
