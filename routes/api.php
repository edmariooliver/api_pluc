<?php

use App\Http\Controllers\api\LoginController;
use App\Http\Controllers\api\RegisterController;
use App\Http\Controllers\api\LogoutController;
use App\Http\Controllers\api\LinkController;

//Grupo de rotas para acesso direto aos links

Route::group(["middleware"=>["auth:api"]], function(){
    
    Route::post("/generate", [LinkController::class, "generate"]);
    Route::post("/open",     [LinkController::class, "open"]);
});

Route::post("/login",  [LoginController::class, "login"]);
Route::post("/save",   [RegisterController::class, "save"]);
Route::get("/logout",  [LogoutController::class, "logout"]);

