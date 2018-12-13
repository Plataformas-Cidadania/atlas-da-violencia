@extends('layout')
@section('title', 'Mapa')
@section('content')
    <style>
        .map { width: 100%; height: 600px; }

        .legend {
            line-height: 18px;
            color: #555;
        }

        .legend i {
            width: 15px;
            height: 15px;
            float: left;
            margin-right: 8px;
            opacity: 1;
        }

        .info {
            padding: 6px 8px;
            font: 14px/16px Arial, Helvetica, sans-serif;
            background: white;
            background: rgba(255,255,255,0.8);
            box-shadow: 0 0 15px rgba(0,0,0,0.2);
            border-radius: 5px;
            width: 190px;
        }
        .info h4 {
            margin: 0 0 5px;
            color: #777;
        }

        .icons-groups{
            float:right;
            background-image: url(img/icons/png/icons-groups.png);
            width: 52px;
            height: 52px;
            cursor: pointer;
            margin-top: -5px;

            -webkit-transform: scale(0.8, 0.8);
            -moz-transform: scale(0.8, 0.8);
            -o-transform: scale(0.8, 0.8);
            -ms-transform: scale(0.8, 0.8);
            transform: scale(0.8, 0.8);

        }

        .icons-groups:hover{
            -webkit-transform: scale(1.0, 1.0);
            -moz-transform: scale(1.0, 1.0);
            -o-transform: scale(1.0, 1.0);
            -ms-transform: scale(1.0, 1.0);
            transform: scale(1.0, 1.0);

        }
        .icon-group-map{
            background-position: 0 0;
        }
        .icon-group-map-disable{
            background-position: 0 -52px;
        }
        .icon-group-chart{
            background-position: -52px 0;
        }
        .icon-group-chart-disable{
            background-position: -52px -52px;
        }
        .icon-group-table{
            background-position: -104px 0;
        }
        .icon-group-table-disable{
            background-position: -104px -52px;
        }
        .icon-group-rate{
            background-position: -156px 0;
        }
        .icon-group-rate-disable{
            background-position: -156px -52px;
        }
        .icon-group-calc{
            background-position: -208px 0;
        }
        .icon-group-calc-disable{
            background-position: -208px -52px;
        }
        .icon-group-print{
            background-position: -260px -52px;
        }
        .icon-group-print:hover{
            background-position: -260px 0;
        }
        .icon-group-info{
            background-position: -312px 0;
        }
        .icon-group-info-disable{
            background-position: -312px -52px;
        }
        .icon-group-download{
            background-position: -364px -52px;
        }
        .icon-group-download:hover{
            background-position: -364px 0;
        }
        .icon-group-email{
            background-position: -416px -52px;
        }
        .icon-group-email:hover{
            background-position: -416px 0;
        }
        .icon-text{
            padding-top: 7px;
        }


        .icons-charts{
            float:right;
            background-image: url(img/icons/png/icons-chart.png);
            width: 48px;
            height: 48px;
            cursor: pointer;
        }

        .icon-chart-bar{
            background-position: 0 0;
        }
        .icon-chart-bar-disable{
            background-position: 0 -48px;
        }
        .icon-chart-line{
            background-position: -48px 0;
        }
        .icon-chart-line-disable{
            background-position: -48px -48px;
        }
        .icon-chart-radar{
            background-position: -96px 0;
        }
        .icon-chart-radar-disable{
            background-position: -96px -48px;
        }
        .icon-chart-pie{
            background-position: -144px 0;
        }
        .icon-chart-pie-disable{
            background-position: -144px -48px;
        }

        .icons-arrows{
            /*float:right;*/
            background-image: url(img/icons/png/icons-arrow.png);
            width: 48px;
            height: 48px;
            cursor: pointer;
        }

        .icon-green-down{
            background-position: 0 0;
        }
        .icon-green-up{
            background-position: -48px 0;
        }
        .icon-red-down{
            background-position: -96px 0;
        }
        .icon-red-up{
            background-position: -144px 0;
        }

        .icons-list-items{
            float:right;
            background-image: url(img/icons/png/icons-list-itens.png);
            width: 40px;
            height: 44px;
            cursor: pointer;
        }

        .icon-list-item-1{
            background-position: 0 0;
        }

        .icons-list-140-150{
            background-image: url(img/icons/png/icons-list-140x150.png);
            width: 140px;
            height: 150px;
            margin: auto;
        }

        .icon-list-140-150-1{
            background-position: 0 0;
        }

        .label-valor{
            color: #fff;
            size: 48px;
            font-weight: bold;
            text-shadow: 2px 2px 2px #5d5d5d;
        }
        .icons-components{
            background-image: url(img/icons-groups-p.png);
            width: 28px;
            height: 28px;
            margin: 1px;
            float: left;
            cursor: pointer;
        }
        .icons-component-print{
            background-position: 0 56px;
        }
        .icons-component-download{
            background-position: -28px 56px;
        }
        .icons-component-csv{
            background-position: -56px 56px;
        }
        .icons-component-pdf{
            background-position: -84px 56px;
        }
        .icons-component-btn{
            background-position: -112px 56px;
        }

        .icons-component-print:hover{
            background-position: 0px 0;
        }
        .icons-component-download:hover{
            background-position: -28px 0;
        }
        .icons-component-csv:hover{
            background-position: -56px 0;
        }
        .icons-component-pdf:hover{
            background-position: -84px 0;
        }
        .icons-component-btn:hover{
            background-position: -112px 0;
        }

        .icons-component-print-active{
            background-position: 0 28px;
        }
        .icons-component-download-active{
            background-position: -28px 28px;
        }
        .icons-component-csv-active{
            background-position: -56px 28px;
        }
        .icons-component-pdf-active{
            background-position: -84px 28px;
        }
        .icons-component-btn-active{
            background-position: -112px 28px;
        }

        .print {
            display:none;
        }

        .print-off {
            display:block;
        }

        @media print{
            .print {
                display:block;
            }
            .print-off {
                display:none;
            }


        }

    </style>


    <div class="container">

        @if(!empty($series))
            <?php //$abrangencias = config('constants.abrangencias');?>
            <script>
                serie_id={{$id}};
                serie="{!! $series->titulo !!}";
                periodicidade="{!! $series->periodicidade !!}";
                tipoValores="{!! $series->tipo_valores !!}";
                unidade="{!! $series->unidade !!}";
                tipoUnidade="{!! $series->tipo_unidade !!}";
                fonte="{!! $series->fonte !!}";
                from="{!! $from !!}";
                to="{!! $to !!}";
                regions="{!! $regions !!}";
                abrangencia="{{$abrangencia}}";
                abrangenciasOk="{{$abrangenciasOk}}";
                @foreach($abrangencias as $key => $abr)
                    @if($abr['id']==$abrangencia)
                        nomeAbrangencia="{!! $abr['title'] !!}";
                    @endif
                @endforeach

                posicao_mapa={{$setting->posicao_mapa}};
                posicao_tabela={{$setting->posicao_tabela}};
                posicao_grafico={{$setting->posicao_grafico}};
                posicao_taxa={{$setting->posicao_taxa}};
                posicao_metadados={{$setting->posicao_metadados}};

                lang_map = "@lang('react.map')";
                lang_table = "@lang('react.table')";
                lang_graphics = "@lang('react.graphics')";
                lang_rates = "@lang('react.rates')";
                lang_metadata = "@lang('react.metadata')";
                lang_source = "@lang('react.source')";
                lang_information = "@lang('react.information')";

                lang_smallest_index = "@lang('react.smallest-index')";
                lang_higher_index = "@lang('react.higher-index')";
                lang_largest_drop = "@lang('react.largest-drop')";
                lang_increased_growth = "@lang('react.increased-growth')";
                lang_lower_growth = "@lang('react.lower-growth')";
                lang_lower_fall = "@lang('react.lower-fall')";

                lang_select_period = "@lang('react.select-period')";

                lang_unity = "@lang('react.unity')";
                lang_custom = "@lang('react.custom')";

                lang_parents = "@lang('react.parents')";
                lang_regions = "@lang('react.regions')";
                lang_uf = "@lang('react.uf')";
                lang_counties = "@lang('react.counties')";
                lang_filter_uf = "@lang('react.filter-uf')";

                lang_mouse_over_region = "@lang('react.mouse-over-region')";
                lang_downloads = "@lang('react.downloads')";
                lang_download = "@lang('react.download')";
                lang_close = "@lang('react.close')";
                lang_decimal_tab = "@lang('react.decimal-tab')";
                lang_in = "@lang('react.in')";
                lang_up_until = "@lang('react.up-until')";


                lang_select_territories = "@lang('react.select-territories')";
                lang_search = "@lang('react.search')";
                lang_select_states = "@lang('react.select-states')";
                lang_selected_items = "@lang('react.selected-items')";
                lang_cancel = "@lang('react.cancel')";
                lang_continue = "@lang('react.continue')";
                lang_all = "@lang('react.all')";
                lang_remove_all = "@lang('react.remove-all')";

            </script>

            <script>
                metadados="{!! str_replace('"', '\"', preg_replace( "/\r|\n/", "", $series->descricao)) !!}";
            </script>

            <div id="pgSerie"></div>


        @else
            <h1 class="text-center">Pesquisa não encontrada!</h1>
        @endif




        {{--<div class="hidden-print">
            <h4><i class="fa fa-calendar" aria-hidden="true"></i> Periodicidade</h4>
            <input type="text" id="range" value=""  name="range" ng-model="range" />
        </div>--}}
        {{--<br><br>
        <div id="mapid"></div>
        <br><br>
        <div id="listValoresSeries"></div>
        <canvas id="myChart" width="400" height="200"></canvas>--}}
        {{--<canvas id="myChartRadar" width="400" height="200"></canvas>--}}
        {{--<button onclick="getData()">Carregar</button>--}}
    </div>


    <!-- Modal -->
    <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" style="z-index: 9999999999999999999999999999;">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="myModalLabel">@lang('react.contact')</h4>
                </div>
                <div class="modal-body" ng-controller="contatoSerieCtrl" role="application">
                    <span class="texto-obrigatorio" ng-show="frmContatoSerie.$invalid">* @lang('react.required-fields')</span><br><br>
                    <form action="" name="frmContatoSerie">
                        <input type="hidden" name="_token" value="{{ csrf_token() }}">

                        <div class="row">
                            <div class="col-md-4">
                                <input type="text" ng-model="contatoSerie.nome" ng-required="true" class="form-control" placeholder="* @lang('react.name')" ><br>
                            </div>
                            <div class="col-md-4">
                                <input type="email" name="email" ng-model="contatoSerie.email"  ng-required="true" class="form-control" placeholder="* @lang('react.email')" ><br>
                            </div>
                            <div class="col-md-4">
                                <input type="text" ng-model="contatoSerie.telefone" class="form-control" placeholder="@lang('react.telephone')" mask-phone-dir><br>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <input type="text" ng-model="contatoSerie.serie" ng-required="true" class="form-control"  ng-init="contatoSerie.serie='{{$id}} - {!! $series->titulo !!}'" readonly="true"><br>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <textarea name="" ng-model="contatoSerie.mensagem" ng-required="true" cols="30" rows="10" class="form-control" placeholder="* @lang('react.message')" ></textarea>
                            </div>
                        </div>
                        <br>
                        <div class="row">
                            <div class="col-md-2 col-xs-2">
                                <button type="button" class="btn btn-primary" ng-click="inserir()" ng-disabled="frmContatoSerie.$invalid || enviandoContatoSerie">@lang('react.submit')</button>
                            </div>
                            <div class="col-md-10 col-xs-10">
                                <div class="text-primary" ng-show="enviandoContatoSerie" style="padding: 7px;"><i class="fa fa-spinner fa-pulse"></i> @lang('react.msg-email-sending')</div>
                                <div ng-show="erroContatoSerie" class="text-danger" style="padding: 7px;"><i class="fa fa-exclamation-triangle"></i> @lang('react.msg-email-erro')</div>
                                <div ng-show="enviadoContatoSerie" class="text-success" style="padding: 7px;"><i class="fa fa-check"></i> @lang('react.msg-email-send')</div>
                                <div ng-show="frmContatoSerie.email.$dirty && frmContatoSerie.email.$invalid" class="text-danger"> @lang('react.msg-email-invalid')</div>
                            </div>
                        </div>
                    </form>
                </div>

            </div>
        </div>
    </div>


@endsection