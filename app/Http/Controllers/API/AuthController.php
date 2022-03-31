<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Providers\RouteServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\ViewModels\Response;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\Auth\Events\Registered;

use App\Models\User;

class AuthController extends Controller
{
    private $response;
    public function __construct(){
        $this->response = new Response();
    }
    
    public function login(LoginRequest $request){
        $auth = $request->api_authenticate();
        if(Auth::check()){
            $user = Auth::user();
            $token = $user->createToken("token");

            $this->response->status_code = 200;
            $this->response->message = "success";
            $this->response->data = [
                "user" => $user,
                "token" => $token->plainTextToken
            ];

            return response()->json($this->response);
        }else{
            $this->response->status_code = 401;
            $this->response->message = "error";
            $this->response->data = "Error Logging In";

            return response()->json($this->response);
        }
    }

    public function register(Request $request){
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);
        $token = $user->createToken("token");
        $this->response->status_code = 200;
        $this->response->message = "success";
        $this->response->data = [
            "user" => $user,
            "token" => $token->plainTextToken
        ];
        
        return response()->json($this->response);
    }
}