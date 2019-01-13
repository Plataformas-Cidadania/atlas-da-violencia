@extends('layout')
@section('title', $setting->descricao_contato)
@section('content')

    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.0.2/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet@1.0.2/dist/leaflet.js"></script>
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
        <h2>@lang('links.contact')</h2>
        <div class="line_title bg-pri"></div>
        <div class="row">

            <div class="col-md-12">
                    <br>
                <div id="mapid" style="width: 100%; height: 400px;"></div>
                <script>

                    var mymap = L.map('mapid').setView([{{$setting->latitude}},{{$setting->longitude}}], 16);

                    L.tileLayer('https://api.tiles.mapbox.com/v4/{id}/{z}/{x}/{y}.png?access_token=pk.eyJ1IjoiYnJwYXNzb3MiLCJhIjoiY2l4N3l0bXF0MDFiczJ6cnNwODN3cHJidiJ9.qnfh8Jfn_be6gpo774j_nQ', {
                        maxZoom: 18,
                        attribution: 'Map data &copy; <a href="http://openstreetmap.org">OpenStreetMap</a> contributors, ' +
                        '<a href="http://creativecommons.org/licenses/by-sa/2.0/">CC-BY-SA</a>, ' +
                        'Imagery © <a href="http://mapbox.com">Mapbox</a>',
                        id: 'mapbox.streets'
                    }).addTo(mymap);



                    var popup = L.popup();

                    var atlasIcon = L.icon({
                        iconUrl: 'img/marker.png',

                        iconSize:     [40, 43], // size of the icon
                        iconAnchor:   [20, 42], // point of the icon which will correspond to marker's location
                        popupAnchor:  [0, -40] // point from which the popup should open relative to the iconAnchor
                    });

                    L.marker([{{$setting->latitude}},{{$setting->longitude}}], {icon: atlasIcon}).addTo(mymap)
                        .bindPopup("<b><img src='imagens/favicons/64x64-{{$favicons->imagem}}' alt=''></b><br />").openPopup();

                </script>
                <br><br><br>
            </div>
            <address class="col-md-4">
                <h3><i class="fa fa-home fa-fw" aria-hidden="true"></i>&nbsp;@lang('adresses.address')</h3>
                <p>{{$setting->endereco}}, {{$setting->numero}} {{$setting->complemento}} - {{$setting->bairro}}</p>
                <p>{{$setting->cidade}} - {{$setting->estado}}</p>
                <p>CEP.: {{$setting->cep}}</p>
                <br>
                <h3><i class="fa fa-phone fa-fw" aria-hidden="true"></i>&nbsp;@lang('adresses.phone')</h3>
                <abbr title="Phone">{{$setting->telefone}}</abbr><br>
                <abbr title="Phone">{{$setting->telefone2}}</abbr><br>
                <abbr title="Phone">{{$setting->telefone3}}</abbr>
                <br>
                <h3><i class="fa fa-envelope fa-fw" aria-hidden="true"></i>&nbsp;@lang('forms.email')</h3>
                <p><a href="mailto:{{$setting->email}}">{{$setting->email}}</a></p>
            </address>

            <div class="col-md-8">
                {{-- <h3>{{$setting->titulo_contato}}</h3> --}}
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
