<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class TesteController extends Controller
{
    //

    function login(Request $request){
        $email = $request->email;
        $senha  = $request->senha;

        if(Auth::attempt(['email'=>$email, 'password'=>$senha])){
            
            return redirect("auth");

        }else{
            return response()->json("Acesso liberado!");
        }
        
    }

    function save(User $user, Request $request){
        
        $user->created_at = Date("h:m:s Y/m/d");
        $user->updated_at = Date("h:m:s Y/m/d");
        $user->email = $request->email;
        $user->password = Hash::make($request->senha);
        $user->save();
        
    }
    
    function auth(){
        return response("hehe");
    }

    function logout(){
        
        Auth::logout();
        return response("Saiu");
    }
}