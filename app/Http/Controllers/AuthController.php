<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Validator;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|min:3|email|unique:users,email',
            'password' => 'required',
            'c_password' => 'required|same:password',
        ]);
   
        if($validator->fails()){
            return response()->json([
                'success' => false,
                'message' => 'Gagal Daftar !',
                'data'    => $validator->errors(),
            ], 422);     
        }
   
        $input = $request->all();
        $input['password'] = bcrypt($input['password']);
        $input['role'] = 'admin';

        $user = User::create($input);

        $success['token'] =  $user->createToken('MyApp')->plainTextToken;
        $success['name'] =  $user->name;
        
   
        return response()->json([
            'success' => true,
            'message' => 'Register Berhasil !',
            'data'    => $success
        ], 201);  
    }

    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required',
        ]);
   
        if($validator->fails()){
            return response()->json([
                'success' => false,
                'message' => 'Gagal Login !',
                'data'    => $validator->errors(),
            ], 422);     
        }

        if(Auth::attempt(['email' => $request->email, 'password' => $request->password])){ 
            $user = Auth::user(); 
            $success['token'] =  $user->createToken('MyApp')->plainTextToken; 
            $success['name'] =  $user->name;
            // $success['email'] =  $user->email;
   
            return response()->json([
                'success' => true,
                'message' => 'Login Berhasil !',
                'data'    => $success
            ], 200);  
        } 
        else{ 
            return response()->json([
                'success' => false,
                'message' => 'Login Gagal !',
                'data'    => ['error'=>'User / Password Tidak Sesuai !']
            ], 403);  
        } 
    }

    public function logout(Request $request)
    {
        $data = $request->user()->currentAccessToken()->delete();

        return response()->json([
            'success' => true,
            'message' => 'Logout Berhasil !',
            'data'    => $data
        ], 200);  

    }

}
