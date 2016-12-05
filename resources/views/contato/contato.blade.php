@extends('layout')
@section('title', $setting->descricao_contato)
@section('content')
    {{--{{ Counter::count('contato') }}--}}
    <style>
        /*input.ng-invalid {
            border-color: #d43f3a;
        }
        textarea.ng-invalid {
            border-color: #d43f3a;
        }*/
        input.email.error{
            color: #d43f3a;
        }
    </style>
    <div class="container" ng-controller="contatoCtrl" role="application">
        <h2>Contato</h2>
        <div class="line_title bg-pri"></div>
        <div class="row">
            <address class="col-md-4">
                <h3><i class="fa fa-home fa-fw" aria-hidden="true"></i>&nbsp;Endereço</h3>
                <p>{{$setting->endereco}}, {{$setting->numero}}{{-- - {{$setting->complemento}}--}} - {{$setting->bairro}}</p>
                <p>{{$setting->cidade}} - {{$setting->estado}}</p>
                <p>CEP.: {{$setting->cep}}</p>
                <br>
                <h3><i class="fa fa-phone fa-fw" aria-hidden="true"></i>&nbsp;Telefone</h3>
                <abbr title="Phone">{{$setting->telefone}}</abbr><br>
                <abbr title="Phone">{{$setting->telefone2}}</abbr><br>
                <abbr title="Phone">{{$setting->telefone3}}</abbr>
                <br>
                <h3><i class="fa fa-envelope fa-fw" aria-hidden="true"></i>&nbsp;E-mail</h3>
                <p><a href="mailto:{{$setting->email}}">{{$setting->email}}</a></p>
            </address>
            <div class="col-md-8">
                {{--<h3>{{$setting->titulo_contato}}</h3>--}}
                <br>
                <p>{{$setting->descricao_contato}}</p>
                <br>
                <span class="texto-obrigatorio" ng-show="frmContato.$invalid">* campos obrigatórios</span><br><br>
                <form action="" name="frmContato">
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">

                    <div class="row">
                        <div class="col-md-4">
                            <input type="text" ng-model="contato.nome" ng-required="true" class="form-control" placeholder="* Nome" ><br>
                        </div>
                        <div class="col-md-4">
                            <input type="email" name="email" ng-model="contato.email"  ng-required="true" class="form-control" placeholder="* E-mail" ><br>
                        </div>
                        <div class="col-md-4">
                            <input type="text" ng-model="contato.telefone" class="form-control" placeholder="Telefone" mask-phone-dir><br>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <textarea name="" ng-model="contato.mensagem" ng-required="true" cols="30" rows="10" class="form-control" placeholder="* Mensagem" ></textarea>
                        </div>
                    </div>
                    <br>
                    <div class="row">
                        <div class="col-md-2 col-xs-2">
                            <button type="button" class="btn btn-primary" ng-click="inserir()" ng-disabled="frmContato.$invalid || enviandoContato">Enviar</button>
                        </div>
                        <div class="col-md-10 col-xs-10">
                            <div class="text-primary" ng-show="enviandoContato" style="padding: 7px;"><i class="fa fa-spinner fa-pulse"></i> enviando e-mail</div>
                            <div ng-show="erroContato" class="text-danger" style="padding: 7px;"><i class="fa fa-exclamation-triangle"></i> Ocorreu um erro. Tente novamente!</div>
                            <div ng-show="enviadoContato" class="text-success" style="padding: 7px;"><i class="fa fa-check"></i> Enviado com sucesso!</div>
                            <div ng-show="frmContato.email.$dirty && frmContato.email.$invalid" class="text-danger">e-mail inválido</div>
                        </div>
                    </div>
                </form>
            </div>

        </div>
    </div>
@endsection