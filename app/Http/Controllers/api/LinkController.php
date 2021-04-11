<?php

namespace App\Http\Controllers\api;

use App\Models\Link;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class LinkController extends Controller
{

    /*
        Essa função recebe um link e retorna um array de dados sobre o link
    */
    function generate(Request $request, Link $link){
            
       
        //Verifica se o link recebido tem "http://"    
        if(!preg_match("/http/", $request->link) ){
            //Se não tiver, ele adiciona

            $request->link = "https://".$request->link;
        }

        /* 
            Verificando se o link é válido
            Se estourar erro é porque o link é inválido
        */
         try {
            get_headers($request->link);

        } catch (\Throwable $th) {
            return response()->json(["erro"=>"Link inválido"]);

        }

        
        //Variável para descobrir se o link já existe no banco (será usada no bloco abaixo)
        $link_exists = $link->where("link_orig", "=", $request->link)->first();

        /*
            Verifica se o link recebido já existe no banco de dados
        */
        if($link_exists){

            //Caso exista, o bloco abaixo, irá retornar os dados sobre o link
            $response['id']            = $link_exists->id;
            $response['link_original'] = $link_exists->link_orig;
            $response['data_criacao']  = date("Y-m-d", strtotime($link_exists->created_at));
            $response['encurtado']     = $link_exists->link_encurt;
            return response()->json($response);

        }else{

            /*
                Caso não exista, o bloco abaixo irá gerar um pequeno grupo de letras 
                aleatorias que será ligado ao link recebido pela request, que irá ser 
                salvo no banco junto ao link recebido, logo após concluir os passos acima,
                o bloco retornará um json com os dados para o usuário
            */
            $string_generat = "";

            do{
                for($i=0;$i<7;$i++) {
                    $strings         = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ";
                    $string_random   = random_int(0, 54);
                    $string_generat .= $strings[$string_random]; 
                }
            }while(count($link->where("link_encurt", "=", $string_generat)->get()) != 0);

            //Gravando no banco
            $link->link_orig           = $request->link;     
            $link->clicks              = 0;        
            $link->link_encurt         = $string_generat;
            $link->created_at          = now();
            $link->save();

            //Criando o array que será retornado
            $response['id']            = $link->id;
            $response['link_original'] = $link->link_orig;
            $response['data_criacao']  = date("Y-m-d", strtotime($link->created_at));
            $response['encurtado']     = $link->link_encurt;
            
            return response()->json($response);
        }

    }

    /*
        Essa função recebe um grupo de letras aleatorias, caso esse grupo esteja ligado à algum link no banco
        os dados desse link serão retornados em json
    */
    function open(Request $request, Link $link){
        
        //Verificando se existe
        $link_exists = $link->where("link_encurt", "=", $request->link)->first();
        
        if($link_exists){
            return response()->json(["link_original"=>$link_exists->link_orig]);
        }
    }
}
