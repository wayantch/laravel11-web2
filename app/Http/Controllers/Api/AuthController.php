<?php
 
namespace App\Http\Controllers\api;
 
use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
 
class AuthController extends Controller
{
 
    public function register(RegisterRequest $request)
    {
        //response default
        $response = [
            'success' => false,
            'message' => '',
            'data' => []
        ];
 
        try {
            $data = $request->validated();
 
            //insert data ke database
            $user = new User();
            $user->name = $data['name'];
            $user->email = $data['email'];
            $user->password = bcrypt($data['password']);
            $user->save();
   
            //ubah $response
            $response['success'] = true;
            $response['data'] = $user;
            $response['message'] = 'Register Success';
        } catch (Exception $e) {
            $response['message'] = $e->getMessage();
        }
 
        //return json
        return response()->json($response);
    }
 
    public function login(LoginRequest $request)
    {
        //response default
        $response = [
            'success' => false,
            'message' => '',
            'data' => []
        ];
 
        try {
            $data = $request->validated();
 
            //login
            if(!auth()->attempt($data)){
                throw new Exception('Email and password does not match');
            }
 
            $user = auth()->user();
            $token = $user->createToken('auth_token')->plainTextToken;
            $response['success'] = true;
            $response['message'] = 'Login Success';
            $response['data'] = [
                'user' => $user,
                'token' => $token,
                'token_type' => 'Bearer',
            ];
        } catch (Exception $e) {
            $response['message'] = $e->getMessage();
        }
 
        return response()->json($response);
    }
 
}