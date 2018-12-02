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

        $dados = $request->all();
        $settings = DB::table('settings')->first();


        Config::set('mail.host', $settings->email_host);
        Config::set('mail.port', $settings->email_port);
        Config::set('mail.address', $settings->email_address);
        Config::set('mail.name', $settings->email_name);
        Config::set('mail.username', $settings->email_user);
        Config::set('mail.password', $settings->email_password);
        Config::set('mail.encryption', 'tls');


        if (!array_key_exists("telefone", $dados)) {
            $dados += ['telefone' => ''];
        }


        //mensagem para o site///////////////////////////////////////////////////////////////////////
        Mail::send('emails.contato.mensagem', ['dados' => $dados, 'settings' => $settings], function($message) use ($settings, $dados)
        {
            $message->from($settings->email_address, $settings->titulo);
            $message->sender($settings->email_address, $settings->titulo);
            $message->to($settings->email_address, $dados['nome']);
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
            $message->from($settings->email_address, $settings->titulo);
            $message->sender($settings->email_address, $settings->titulo);
            $message->to($dados['email'], $dados['nome']);
            $message->subject('Contato - '.$settings->titulo);
        });        
        ////////////////////////////////////////////////////////////////////////////////////////////
        
        $retorno = ["resposta" => "enviado"];
        
        return json_encode($retorno);
    }

}
