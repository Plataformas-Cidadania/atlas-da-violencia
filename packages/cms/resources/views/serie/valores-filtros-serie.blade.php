@extends('cms::layouts.app')

@section('content')
    {{--{!! Html::script('assets-cms/js/controllers/valoresSerieCtrl.js') !!}--}}
    <div {{--ng-controller="valoresSerieCtrl"--}}>
        <div class="box-padrao">
            <h1><a href="cms/series"><i class="fa fa-arrow-circle-left"></i></a>&nbsp;{{$serie->titulo}}</h1>
            <h3>Opções de Filtros</h3>
            <br>
            <div>
                <table class="table">
                    <thead>
                    <th>Filtro</th>
                    <th>Slug</th>
                    <th>Valor</th>
                    <th>Valor ID</th>
                    </thead>
                    <tbody>
                    @foreach($valores as $valor)
                        <tr>
                            <td>{{$valor->filtro}}</td>
                            <td>{{$valor->slug}}</td>
                            <td>{{$valor->valor}}</td>
                            <td>{{$valor->valor_id}}</td>
                        </tr>
                    @endforeach
                    </tbody>

                </table>
            </div>
    </div>
@endsection