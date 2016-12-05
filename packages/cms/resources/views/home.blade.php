@extends('cms::layouts.app')


@section('content')

    <?php
    $mes1 = \Cms\Models\Contador::comparaMes(date('m')-1);
    $mes2 = \Cms\Models\Contador::comparaMes(date('m')-2);
    $mes3 = \Cms\Models\Contador::comparaMes(date('m')-3);

    $nomeMes1 = nomeMes(date('m')-1, 'mes_extenso');
    $nomeMes2 = nomeMes(date('m')-2, 'mes_extenso');
    $nomeMes3 = nomeMes(date('m')-3, 'mes_extenso');

    ?>

    {!! Html::script('assets-cms/js/controllers/alterarSettingCtrl.js') !!}
    <div ng-controller="alterarSettingCtrl">
        <div class="box-padrao">
            <h1><i class="fa fa-fw fa-dashboard"></i>&nbsp;Dashboard</h1>
            <br><br>
            <div class="row text-center">
                <div class="col-md-3">
                    <div class="col-md-12 bs-callout bs-callout-primary">
                        <p>Visitantes hoje</p>
                        <h2><strong>{{\Cms\Models\Contador::visitasHoje()}}</strong></h2>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="col-md-12 bs-callout bs-callout-success">
                        <p>Visitantes do mês</p>
                        <h2><strong>{{\Cms\Models\Contador::visitasMes()}}</strong></h2>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="col-md-12 bs-callout bs-callout-warning">
                        <p>Visitantes do ano</p>
                        <h2><strong>{{\Cms\Models\Contador::visitasAno()}}</strong></h2>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="col-md-12 bs-callout bs-callout-danger">
                        <p>Visitantes total</p>
                        <h2><strong>{{\Cms\Models\Contador::visitas()}}</strong></h2>
                    </div>
                </div>
            </div>
            <br><br><br>
            <div class="row text-center bg-pri-gray" >
                <br><br>
                <h2>Comparação nos últimos 3 meses</h2>
                <br><br><br>

                <div class="row text-center">
                    <div class="col-md-4">
                            <!-- Percentage Circle >>> -->
                            <div class="c100
                            @if($mes1>100)
                                p100
                            @else
                                p{{round($mes1)}}
                            @endif
                            big">
                            <span>{{$mes1}}%</span>
                                <div class="slice">
                                    <div class="bar"></div>
                                    <div class="fill"></div>
                                </div>
                            </div>
                            <!-- Percentage Circle <<<-->
                        <div class="col-md-12">
                            <h2>{{$nomeMes1}}</h2>
                        </div>
                    </div>


                    <div class="col-md-4">
                            <!-- Percentage Circle >>> -->
                            <div class="c100
                            @if($mes2>100)
                                p100
                            @else
                                p{{round($mes2)}}
                            @endif
                            big">
                                <span>{{$mes2}}%</span>
                                <div class="slice">
                                    <div class="bar"></div>
                                    <div class="fill"></div>
                                </div>
                            </div>
                            <!-- Percentage Circle <<<-->
                        <div class="col-md-12">
                            <h2>{{$nomeMes2}}</h2>
                        </div>
                    </div>

                    <div class="col-md-4">
                            <!-- Percentage Circle >>> -->
                            <div class="c100
                            @if($mes3>100)
                                p100
                            @else
                                p{{round($mes3)}}
                            @endif
                            big">
                            <span>{{$mes3}}%</span>
                                <div class="slice">
                                    <div class="bar"></div>
                                    <div class="fill"></div>
                                </div>
                            </div>
                            <!-- Percentage Circle <<<-->
                        <div class="col-md-12">
                            <h2>{{$nomeMes3}}</h2>
                        </div>
                    </div>

                    <br>&nbsp;<br>&nbsp;<br>
                </div>

            </div>

        </div>
    </div>
@endsection