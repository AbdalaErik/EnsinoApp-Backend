<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\Response;
use App\Models\User;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Facades\JWTAuth;

class ApiController extends Controller
{
    public function register(Request $req) {

        $data = $req->only('name', 'email', 'password');

        $messages = [
            'email' => 'Formato inválido para o email.',
            'email.unique' => 'Esse email já foi registrado.',
            'password.min' => 'A senha precisa ter ao menos 6 caracteres.'
        ];

        $validator = Validator::make($data, [
            'name' => 'required|string',
            'email' => 'required|email|unique:users',
            'password' => 'required|string|min:6'
        ], $messages);

        if ($validator->fails()) {
            return response()->json([
                'code' => 2,
                'message' => $validator->errors()
            ], 200);
        }

        $user = User::create([
            'name' => $req->name,
            'email' => $req->email,
            'password' => $req->password
        ]);

        if($user) {

            return response()->json([
                'code' => 1,
                'success' => true,
                'message' => 'Usuário registrado com sucesso!',
                'data' => $user
            ], Response::HTTP_OK);

        }
    }

    public function login(Request $request) {

        $credentials = $request->only('email', 'password');

        $validator = Validator::make($credentials, [

            'email' => 'required|email',
            'password' => 'required|string|min:6|max:50'

        ]);

        if($validator->fails()) {

            return response()->json(['error' => $validator->errors()], 200);

        }

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

        return response()->json([

            'success' => true,
            'code' => 1,
            'message' => 'Login Successfully',
            'token' => $token,
            'user_details' => $credentials['email']

        ]);

    }

    public function logout(Request $request) {

        if(empty($request->token)) {

            return response()->json([
                
                'success' => false,
                'code' => 2,
                'message' => 'Um token é necessário!'

            ]);

        }

        try {

            JWTAuth::invalidate($request->token);

            return response()->json([
                    
                'success' => true,
                'code' => 1,
                'message' => 'O usuário foi desconectado com sucesso!'

            ]);

        } catch (JWTException $exception) {

            return response()->json([
                
                'success' => false,
                'code' => 2,
                'message' => 'Desculpe, mas o usuário não pode ser desconectado.'
                
            ], Response::HTTP_INTERNAL_SERVER_ERROR);

        }

    }

}
