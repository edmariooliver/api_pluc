<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class RegisterController extends Controller
{

    //
    function save(User $user, Request $request){
        
        $user->created_at = Date("h:m:s Y/m/d");
        $user->updated_at = Date("h:m:s Y/m/d");
        $user->name = $request->name;
        $user->email = $request->email;
        $user->password = Hash::make($request->senha);

        //Verifica se o e-mail já pertence à alguma conta, caso pertença, é retornado um aviso.
        if($user->where("email", "=", $request->email)->first()){
            return response()->json(["message"=>"Email já existente", "status"=>"0"], 406);
        }

        //Verifica se o email é válido
        if(!filter_var($request->email, FILTER_VALIDATE_EMAIL)){
            return response()->json(["message"=>"Email inválido", "status"=>"0"], 406);
        }
        /* Se não cair em nenhuma das condições acima, os dados são guardados no 
        banco de dados, e é retornado uma mensagem de sucesso
        */
        $user->save();
        return response()->json(["message"=>"Cadastrado com sucesso", "status"=>"1"], 202);
    }

}