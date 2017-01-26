@extends('layout')
@section('title', "")
@section('content')

    <style>

        input[type=checkbox] {
            display:none;
        }


        input[type=checkbox] + label{
            background: url("/img/checkbox_off.png") no-repeat;
            height: 22px;
            width: 22px;
            display:inline-block;
            padding: 0;
            cursor: pointer;
        }
        input[type=checkbox]:checked + label{
            background: url("/img/checkbox_on.png") no-repeat;
            height: 22px;
            width: 22px;
            display:inline-block;
            padding: 0;
        }
        label {
            display: inherit;
            max-width: 100%;
            margin-bottom: 0;
            font-weight: inherit;

        }
        .animacao{
            transition: all linear 0.5s;
            transition-duration: 1s;
        }
    </style>

    <div class="container">
        <h2 class="h1_title" aria-label=", "></h2>
        <div class="line_title bg-pri"></div>
        <div>
            <div ng-show="marcarLinha2" class="animacao">
                <p class="bg-danger msg text-danger"><i class="fa fa-exclamation-triangle" aria-hidden="true"></i> Não é possível exibir e exportar séries de frequências diferentes.</p>
            </div>
            <div class="table-responsive">
                <table class="table table-bordered table table-hover ">
                    <thead>
                    <tr>
                        <th>
                            <input type='checkbox' name='thingMaster' value='valuable' id="thingMaster"  ng-model="master"/>
                            <label for="thingMaster"></label>
                        </th>
                        <th>
                            <div class="btn-group">
                                <a class="btn dropdown-toggle" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <strong>Nome</strong> <span class="caret"></span>
                                </a>
                                <ul class="dropdown-menu">
                                    <li><a><strong>Ordenar</strong></a></li>
                                    <li role="separator" class="divider"></li>
                                    <li><a href="#"><i class="fa fa-sort" aria-hidden="true"></i> Nome</a></li>
                                    <li><a href="#"><i class="fa fa-sort" aria-hidden="true"></i> Unidade</a></li>
                                    <li><a href="#"><i class="fa fa-sort" aria-hidden="true"></i> Frequencia</a></li>
                                    <li><a href="#"><i class="fa fa-sort" aria-hidden="true"></i> Periodo</a></li>
                                </ul>
                            </div>

                        </th>
                        <th>

                            <div class="btn-group">
                                <a class="btn dropdown-toggle" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <strong>Unidade</strong> <span class="caret"></span>
                                </a>
                                <ul class="dropdown-menu">
                                    <li><a><strong>Filtro</strong></a></li>
                                    <li role="separator" class="divider"></li>
                                    <li><a href="#">% PIB</a></li>
                                    <li><a href="#">US$</a></li>
                                </ul>
                            </div>

                        </th>
                        <th>
                            <div class="btn-group">
                                <a class="btn dropdown-toggle" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <strong>Frequencia</strong> <span class="caret"></span>
                                </a>
                                <ul class="dropdown-menu">
                                    <li><a><strong>Filtro</strong></a></li>
                                    <li role="separator" class="divider"></li>
                                    <li><a href="#">Anual</a></li>
                                    <li><a href="#">Mestral</a></li>
                                    <li><a href="#">Semanal</a></li>
                                </ul>
                            </div>
                            </th>
                        <th>
                            <div class="btn-group">
                                <a class="btn dropdown-toggle" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <strong>Período</strong> <span class="caret"></span>
                                </a>
                                <i class="fa fa-times-circle-o fa-remove-item" aria-hidden="true"></i>
                                <ul class="dropdown-menu">
                                    <li><a><strong>Filtro</strong></a></li>
                                    <li role="separator" class="divider"></li>
                                    <li><a href="#">Mais Antiga</a></li>
                                    <li><a href="#">Mais nova</a></li>
                                </ul>
                            </div>
                        </th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr>
                        <th scope="row" ng-class="{'line-success': marcarLinha}">
                            <label>
                                {{--<input type="checkbox" id="blankCheckbox" ng-checked="master" ng-model="marcarLinha" value="option1" class="hidden">--}}
                                {{--<i class="fa fa-square-o fa-2x" ng-hide="master || marcarLinha" aria-hidden="true" ng-model="marcarLinha"></i>
                                <i class="fa fa-check-square-o fa-2x" ng-show="master || marcarLinha" aria-hidden="true" ng-model="marcarLinha"></i>--}}
                                <input type='checkbox' name='thing0' value='valuable' id="thing0" ng-checked="master" ng-model="marcarLinha"/>
                                <label for="thing0"></label>
                            </label>

                        </th>
                        <td ng-class="{'line-success': marcarLinha}">Dívida externa - não registrada</td>
                        <td ng-class="{'line-success': marcarLinha}">US$</td>
                        <td ng-class="{'line-success': marcarLinha}">Anual</td>
                        <td ng-class="{'line-success': marcarLinha}">1956-2008</td>
                    </tr>
                    <tr>
                        <th scope="row" ng-class="{'line-danger': marcarLinha2}">
                            <input type='checkbox' name='thing1' value='valuable' id="thing1" ng-checked="master" ng-model="marcarLinha2"/>
                            <label for="thing1"></label>
                        </th>
                        <td ng-class="{'line-danger': marcarLinha2}">Dívida externa - privada - curto prazo</td>
                        <td ng-class="{'line-danger': marcarLinha2}">US$</td>
                        <td ng-class="{'line-danger': marcarLinha2}">Trimestral</td>
                        <td ng-class="{'line-danger': marcarLinha2}">1992-2010.04T</td>
                    </tr>
                    <tr>
                        <th scope="row">
                            <input type='checkbox' name='thing2' value='valuable' id="thing2" ng-checked="master"/>
                            <label for="thing2"></label>
                        </th>
                        <td>Dívida externa - privada - médio / longo prazos</td>
                        <td>US$</td>
                        <td>Trimestral</td>
                        <td>1982-2010.04T</td>
                    </tr>
                    <tr>
                        <th scope="row">
                            <input type='checkbox' name='thing3' value='valuable' id="thing3" ng-checked="master"/>
                            <label for="thing3"></label>
                        </th>
                        <td>Dívida externa - privada - registrada</td>
                        <td>US$</td>
                        <td>Anual</td>
                        <td>1978-2008</td>
                    </tr>
                    <tr>
                        <th scope="row">
                            <input type='checkbox' name='thing4' value='valuable' id="thing4" ng-checked="master"/>
                            <label for="thing4"></label>
                        </th>
                        <td>Dívida externa - pública - curto prazo</td>
                        <td>US$</td>
                        <td>Trimestral</td>
                        <td>	1992-2009.04T</td>
                    </tr>
                    </tbody>
                </table>
            </div>

        </div>
    </div>
@endsection
