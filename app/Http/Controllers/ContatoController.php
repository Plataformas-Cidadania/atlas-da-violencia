<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;


class ContatoController extends Controller
{
    public function listar(){
        $setting = DB::table('settings')->first();

        return view('contato.contato', ['setting' => $setting]);
    }

    public function email(Request $request){

        /*Config::set('mail.host', 'smtp.gmail.com');
        Config::set('mail.port', '587');
        Config::set('mail.address', 'email@dominio.com');
        Config::set('mail.name', 'Nome E-mail');
        Config::set('mail.user', 'email@dominio.com');
        Config::set('mail.password', '*********');*/
        
        $dados = $request->all();
        $settings = DB::table('settings')->first();

        //verifica se o index telefone existe no array. Senão existir irá criar um para evitar um erro.
        if (!array_key_exists("telefone", $dados)) {
            $dados += ['telefone' => ''];
        }


        //mensagem para o site///////////////////////////////////////////////////////////////////////
        Mail::send('emails.contato.mensagem', ['dados' => $dados, 'settings' => $settings], function($message) use ($settings, $dados)
        {
            $message->from($settings->email, $settings->titulo);
            $message->sender($settings->email, $settings->titulo);
            $message->to($settings->email, $dados['nome']);
            //$message->cc($address, $name = null);
            //$message->bcc($address, $name = null);
            $message->replyTo($dados['email'], $dados['nome']);
            $message->subject('Contato - '.$settings->titulo);
            //$message->priority($level);
            //$message->attach($pathToFile, array $options = []);
        });
        ////////////////////////////////////////////////////////////////////////////////////////////
        
        //resposta para o remetente/////////////////////////////////////////////////////////////////
        Mail::send('emails.contato.resposta', ['dados' => $dados, 'settings' => $settings], function($message) use ($settings, $dados)
        {
            $message->from($settings->email, $settings->titulo);
            $message->sender($settings->email, $settings->titulo);
            $message->to($dados['email'], $dados['nome']);
            $message->subject('Contato - '.$settings->titulo);
        });        
        ////////////////////////////////////////////////////////////////////////////////////////////
        
        $retorno = ["resposta" => "enviado"];
        
        return json_encode($retorno);
    }
}
