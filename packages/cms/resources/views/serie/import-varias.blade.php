@extends('cms::layouts.app')

@section('content')
    {!! Html::script('assets-cms/js/controllers/importSerieCtrl.js') !!}
    <div ng-controller="importSerieCtrl">
        <div class="box-padrao">
            <h1><a href="cms/series"><i class="fa fa-arrow-circle-left"></i></a>&nbsp;&nbsp;Importar Várias Séries</h1>

            <div >
                <span class="texto-obrigatorio">* campos obrigatórios</span><br><br>
                {!! Form::open(['name' =>'form']) !!}

                <span class="btn btn-primary btn-file" ng-show="!fileArquivo && !arquivoBD">
                    Escolher Arquivo <input  type="file" ngf-select ng-model="fileArquivo" name="fileArquivo" accept=".xlsx,.xls,.csv" ngf-max-size="100MB" ngf-model-invalid="errorFile" ng-required="true">
                </span>
                <button class="btn btn-danger" ng-click="limparArquivo()" ng-show="fileArquivo || arquivoBD" type="button">Remover Arquivo</button>
                <a href="arquivos/artigos/<% arquivoBD %>" target="_blank" ng-show="arquivoBD"><% arquivoBD %></a>
                <a ng-show="fileArquivo"><% fileArquivo.name %></a>
                <br><br>

                <div>
                    {!! Form::label('tipo_dados', 'Tipo de Dados *') !!}<br>
                    {!! Form::select('tipo_dados',
                            [
                                0 => 'Territórios',
                                1 => 'Pontos',
                            ],
                    null, ['class'=>"form-control width-medio <% validar(tipo_dados) %>", 'ng-model'=>'tipo_dados', 'ng-required'=>'true', 'init-model'=>'tipo_dados']) !!}<br>
                </div>

                <div class="row" ng-if="tipo_dados">
                    <div class="col-md-1 col-lg-1 col-xs-3">
                        <button class="btn btn-info" type="button" ng-click="importar(fileArquivo)" ng-disabled="form.$invalid">Importar</button>
                    </div>
                    <div class="col-md-3 col-lg-3 col-xs-6">
                        <span class="progress" ng-show="picFile.progress >= 0">
                            <div style="width: <% picFile.progress %>%" ng-bind="picFile.progress + '%'"></div>
                        </span>
                        <div ng-show="processandoSalvar"><i class="fa fa-spinner fa-spin"></i> Processando...</div>
                        <div>&nbsp;&nbsp;<% mensagemSalvar %></div>
                        <span ng-show="picFile.result">{{--Upload Successful--}}</span>
                        <span class="err" ng-show="errorMsg"><% errorMsg %></span>
                    </div>
                    <div class="col-md-9 col-xs-3"></div>
                </div>
                <br><br><br>

                <div ng-if="tipo_dados==='0'">
                    <div style="display:none;">
                        <label for="modelo">Modelo de Arquivo</label>
                        <select class="form-control width-medio" name="modelo" id="modelo" ng-model="modelo" ng-required="true" ng-init="modelo='1'">
                            <option value="">Selecione</option>
                            <option value="1">Periodos em Linhas</option>
                            {{--<option value="2">Periodos em Colunas</option>--}}
                        </select>
                        <br><br>
                    </div>

                    <div id="modelo1">
                        <div><strong>Exemplo: series.csv</strong></div>
                        <div style="padding: 10px; background-color: #ccc; color:#333; width:400px;">
                            <div>serie;abrangencia;cod;nome;valor;periodo</div>
                            <div>1;3;RJ;Rio de Janeiro;21;2018</div>
                            <div>1;3;SP;São Paulo;11;2018</div>
                            <div>2;3;RJ;Rio de Janeiro;12;2018</div>
                            <div>2;3;SP;São Paulo;25;2018</div>
                        </div>
                        <br>
                    </div>

                    <div id="modelo2">

                    </div>

                    <div><strong>Abrangências</strong></div>
                    <div style="padding: 10px; background-color: #ccc; color:#333; width:400px;">
                        <div>1 - País</div>
                        <div>2 - Região</div>
                        <div>3 - UF</div>
                        <div>4 - Municípios</div>
                    </div>
                    <br>
                </div>

                <div ng-if="tipo_dados==1">
                    <div id="modelo1">
                        <div>Da coluna <strong>serie</strong> até <strong>hora</strong> são opções padrões do sistema.</div>
                        <div>As demais colunas são dinâmicas e devem ser cadastradas no menu <strong>Pontos - Filtros</strong></div>
                        <div>Após a coluna <strong>hora</strong> deverão ser colocados na primeira linha os slugs de cada filtro</div>
                        <div>Os filtros também deverá ser atribuídos às séries na tela de séries no ícone de filtros séries na listagem</div>
                        <div>Nas demais linhas os valores das colunas dinâmicas deverão ser os <strong>ids</strong> das opções de filtros</div>
                        <div>Para facilitar a criação do csv você pode consultar os ids das opções de filtros de uma série em:</div>
                        <br>
                        <div><?php echo $_SERVER['HTTP_HOST']?>/cms/valores-filtros-serie/{id} </div>
                        <br>
                        <div><strong>Exemplo: series.csv</strong></div>
                        <div style="padding: 10px; background-color: #ccc; color:#333; width:500px;">
                            <div>serie;lat;lon;titulo;endereco;data;hora;faixa_etaria;locomocao;sexo;turno</div>
                            <div>1;-22.8128022;-43.014031;aaaaaa;rua a;2019-01-01;13:16:10;17;1;8;27</div>
                            <div>1;-22.8121022;-43.011631;bbbbbb;rua b;2019-01-02;14:25:10;17;1;8;27</div>
                            <div>1;-22.8118022;-43.012631;cccccc;rua c;2019-01-03;15:34:10;17;1;8;28</div>
                            <div>1;-22.8128022;-43.013631;dddddd;rua d;2019-01-04;16:43:10;16;2;8;27</div>
                            <div>1;-22.8138022;-43.014631;eeeeee;rua e;2019-01-05;17:52:10;19;5;9;27</div>
                            <div>1;-22.8148022;-43.015631;ffffff;rua f;2019-01-06;18:21:10;19;5;9;27</div>
                        </div>
                        <br>
                    </div>

                    <div><a href="cms/atualizar-views-materializadas-pontos">Atualizar Views Materializadas</a></div>
                    <br><br><br>
                </div>


                {!! Form::close()!!}
            </div>
        </div>
    </div>
@endsection
