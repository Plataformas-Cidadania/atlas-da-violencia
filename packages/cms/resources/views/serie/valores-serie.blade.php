@extends('cms::layouts.app')

@section('content')
    {{--{!! Html::script('assets-cms/js/controllers/valoresSerieCtrl.js') !!}--}}
    <div {{--ng-controller="valoresSerieCtrl"--}}>
        <div class="box-padrao">
            <h1><a href="cms/series"><i class="fa fa-arrow-circle-left"></i></a>&nbsp;{{$serie_id}} - {{$textos_series->titulo}}</h1>

            <div>
                <select name="abrangencia" id="abrangencia"></select>
            </div>

            <div>
                <table class="table">
                    <thead>
                    <th>id</th>
                    <th>territorio</th>
                    <th>periodo</th>
                    <th>valor</th>
                    </thead>
                    <tbody>

                    </tbody>
                </table>
            </div>
    </div>
@endsection