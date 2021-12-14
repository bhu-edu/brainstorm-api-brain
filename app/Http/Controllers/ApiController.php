<?php

namespace App\Http\Controllers;

use JWTAuth;
use App\Models\User;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Exceptions\JWTException;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Validator;

class ApiController extends Controller
{
    public function brainAuthenticate(Request $request)
    {
        $requestBody = $request->only('socialProvider', 'socialusers');
        $validator = Validator::make($requestBody, [
            'socialProvider' => 'required|string',
            'socialusers' => 'required'
        ]);
        if ($validator->fails()) {
            return response()->json(['error' => 'Something went wrong'], 500);
        }
        $credentials=['email'=>$requestBody['socialusers']['email'],'password'=>'firsttest'];
        //Crean token
        try {
            if (! $token = JWTAuth::attempt($credentials)) {
                return response()->json([
                	'success' => false,
                	'message' => 'User is not registered.',
                ], 400);
            }
        } catch (JWTException $e) {
            return response()->json([
                	'success' => false,
                	'message' => 'Could not create token.',
                ], 500);
        }
        $user = User::where('email', $credentials['email'])->first();
        if($user['role'] == 'bks-admin' && $user['active'] == 1){
             return response()->json([
            'success' => true,
            'token' => $token,
        ]);
        }else{
            return response()->json([
                'success' => false,
                'message' => 'Not Authorized',
            ], 500);
        }
    }

    public function brainLogout(Request $request)
    {
        try {
            JWTAuth::invalidate($request->bearerToken());
            return response()->json([
                'success' => true,
                'message' => 'User has been logged out'
            ]);
        } catch (JWTException $exception) {
            return response()->json([
                'success' => false,
                'message' => 'Sorry, user cannot be logged out'
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }


















    public function register(Request $request)
    {
    	//Validate data
        $data = $request->only('name', 'email', 'password');
        $validator = Validator::make($data, [
            'name' => 'required|string',
            'email' => 'required|email|unique:users',
            'password' => 'required|string|min:6|max:50'
        ]);

        //Send failed response if request is not valid
        if ($validator->fails()) {
            return response()->json(['error' => $validator->messages()], 200);
        }

        //Request is valid, create new user
        $user = User::create([
        	'name' => $request->name,
        	'email' => $request->email,
        	'password' => bcrypt($request->password)
        ]);

        //User created, return success response
        return response()->json([
            'success' => true,
            'message' => 'User created successfully',
            'data' => $user
        ], Response::HTTP_OK);
    }

    public function authenticate(Request $request)
    {
        /*$credentials = $request->only('email', 'password');

        //valid credential
        $validator = Validator::make($credentials, [
            'email' => 'required|email',
            'password' => 'required|string|min:6|max:50'
        ]);

        //Send failed response if request is not valid
        if ($validator->fails()) {
            return response()->json(['error' => $validator->messages()], 200);
        }

        //Request is validated
        //Crean token
        try {
            if (! $token = JWTAuth::attempt($credentials)) {
                return response()->json([
                	'success' => false,
                	'message' => 'Login credentials are invalid.',
                ], 400);
            }
        } catch (JWTException $e) {
    	return $credentials;
            return response()->json([
                	'success' => false,
                	'message' => 'Could not create token.',
                ], 500);
        }

 		//Token created, return with success response and jwt token
        return response()->json([
            'success' => true,
            'token' => $token,
        ]);*/
        $requestBody = $request->only('socialProvider', 'socialusers');
        //valid credential
        $validator = Validator::make($requestBody, [
            'socialProvider' => 'required|string',
            'socialusers' => 'required'
        ]);

        //Send failed response if request is not valid
        if ($validator->fails()) {
            return response()->json(['error' => $validator->messages()], 200);
        }
        $credentials=['email'=>$requestBody['socialusers']['email'],'password'=>'firsttest'];

        //Crean token
        try {
            if (! $token = JWTAuth::attempt($credentials)) {
                return response()->json([
                	'success' => false,
                	'message' => 'User is not registered.',
                ], 400);
            }
        } catch (JWTException $e) {
    	return $credentials;
            return response()->json([
                	'success' => false,
                	'message' => 'Could not create token.',
                ], 500);
        }

        //Token created, return with success response and jwt token
        return response()->json([
            'success' => true,
            'token' => $token,
        ]);
    }

    /*public function logout(Request $request)
    {
        //valid credential
        //$validator = Validator::make($request->bearerToken(), [
        //    'brain_token' => 'required'
        //]);

        //Send failed response if request is not valid
        //if ($validator->fails()) {
        //    return response()->json(['error' => $validator->messages()], 200);
        //}

		//Request is validated, do logout
        try {
            JWTAuth::invalidate($request->bearerToken());
            //JWTAuth::invalidate($request->brain_token);

            return response()->json([
                'success' => true,
                'message' => 'User has been logged out'
            ]);
        } catch (JWTException $exception) {
            return response()->json([
                'success' => false,
                'message' => 'Sorry, user cannot be logged out'
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }*/

    public function get_user(Request $request)
    {
        $this->validate($request, [
            'token' => 'required'
        ]);

        $user = JWTAuth::authenticate($request->token);

        return response()->json(['user' => $user]);
    }
}
