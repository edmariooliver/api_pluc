<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class LoginController extends Controller
{
    //
    function login(Request $request){
        
        $email = $request->email;
        $senha  = $request->senha;

        if(Auth::attempt(['email'=>$email, 'password'=>$senha])){
            $user = $request->user();
            $response['token'] = $user->createToken("token")->accessToken;
            $response["nome"] = $user->name;
            return response()->json($response);

        }else{

            return response()->json("Acesso não liberado!", 403);
        }
        
    }
}
