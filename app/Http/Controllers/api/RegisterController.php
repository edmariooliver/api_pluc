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
        $user->save();
        
    }

}