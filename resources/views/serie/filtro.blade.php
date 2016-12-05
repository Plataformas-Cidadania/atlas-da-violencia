@extends('.layout')
@section('title', 'Série')
@section('content')
    {{--{{ Counter::count('noticia') }}--}}
    <div class="container">


        <h2>Série</h2>
        <div class="line_title bg-pri"></div>


        <div class="bs-callout bs-callout-info" id="callout-type-dl-truncate">
            <h4>Dívida externa registrada</h4>
            <p>
                <strong>Frequência:</strong> Anual de 1889 até 2008<br>
                <strong>Fonte:</strong> Banco Central do Brasil, Boletim, Seção Balanço de Pagamentos (Bacen / Boletim / BP)<br>
                <strong>Unidade:</strong> US$ (milhões)<br>
                <strong>Comentário:</strong> Para 1889-1945: Abreu, Marcelo de Paiva (Org.). A ordem do progresso - cem anos de política econômica republicana. Rio de Janeiro: Campus, 1992. Obs.: A partir de mar. 2001, exclui empréstimos intercompanhias (retroativo a 2000) e contempla revisão na posição de endividamento. Compreende o setor público financeiro e não financeiro e o setor privado.<br>
                <strong>Atualizado em:</strong> 26/06/2009
            </p>
        </div>

        <div class="table-responsive">
            <table class="table table-bordered table table-hover ">
               <tbody>
                <thead class="filete-itens">
                    <tr>
                        <th colspan="3"><i class="fa fa-cog" aria-hidden="true"></i> Configuração</th>
                    </tr>
                </thead>
                <tr>
                    <th scope="row">Escolha o nível geográfico:</th>
                    <td ng-init="itensGeografico=[{id:0, value:'Brasil'}, {id:1, value:'Regiões'}]">
                        <select class="form-control" style="width: 150px;" ng-model="geografico" ng-options="item as item.value for item in itensGeografico track by item.id">
                            <option value="">Selecione</option>
                           {{-- <option value="0">Brasil</option>
                            <option value="1">Regiões</option>
                            <option value="2">Estados</option>
                            <option value="5">Municípios</option>
                            <option value="4">Mesorregiões</option>
                            <option value="3">Microrregiões</option>--}}
                        </select>
                    </td>
                    <td>Para acessar as séries selecionadas, escolha o nível geográfico de seu interesse que pode ser Brasil, estados, municípios, áreas comparáveis, regiões metropolitanas etc.</td>
                </tr>
                <tr>
                    <th scope="row">Escolha a abrangência:</th>
                    <td ng-init="itensAbrangencia=[{id:0, value:'Estado do Rio de Janeiro'}, {id:1, value:'Estado de São Paulo'}]">
                        <select class="form-control" ng-model="abrangencia" ng-options="item as item.value for item in itensAbrangencia track by item.id">
                            <option value="">Selecione</option>
                        </select>
                    </td>
                    <td>Escolha uma área de abrangência geográfica relevante que pode ser todo Brasil, uma região ou estado específico, ou uma região administrativa como Amazônia Legal, SUDENE, municípios que participam do Programa Fome Zero, entre outros.</td>
                </tr>
                <tr>
                    <th scope="row">Início:</th>
                    <td ng-init="itensInicio=[{id:0, value:'2014'}, {id:1, value:'2015'}, {id:2, value:'2016'}]">
                        <select class="form-control" ng-model="inicio" ng-options="item as item.value for item in itensInicio track by item.id">
                            <option value="">Selecione</option>
                        </select>
                    </td>
                    <td>Escolha o período inicial de interesse.</td>
                </tr>
                <tr>
                    <th scope="row">Fim:</th>
                    <td ng-init="itensFim=[{id:0, value:'2014'}, {id:1, value:'2015'}, {id:2, value:'2016'}]">
                        <select class="form-control" ng-model="fim" ng-options="item as item.value for item in itensFim track by item.id">
                            <option value="">Selecione</option>
                        </select>
                    </td>
                    <td>Escolha o período final de interesse.</td>
                </tr>
                <tr>
                    <th scope="row">Buscar por:</th>
                    <td scope="row" colspan="3">
                        <samp ng-bind="geografico.value"></samp> <samp ng-if="geografico.value"><i class="fa fa-angle-right" aria-hidden="true"></i></samp>
                        <samp ng-bind="abrangencia.value"></samp> <samp ng-if="abrangencia.value"><i class="fa fa-angle-right" aria-hidden="true"></i></samp>
                        <samp ng-bind="inicio.value"></samp> <samp ng-if="inicio.value">- </samp>
                        <samp ng-bind="fim.value"></samp> <samp ng-if="fim.value"></samp>
                    </td>
                </tr>

                </tbody>
            </table>
        </div>
        <div class="table-responsive">
            <table class="table table-bordered table table-hover ">
                <tbody>
                <thead class="filete-itens">
                <tr>
                    <th colspan="3"><i class="fa fa-map" aria-hidden="true"></i> Cartograma</th>
                </tr>
                </thead>
                <tr>
                    <td>
                        <label>
                            <input type="radio" name="blankRadio" id="blankRadio1" value="option1" aria-label="..."> Não exibir
                        </label>
                    </td>
                </tr>
                <tr>
                    <td>
                        <label>
                            <input type="radio" name="blankRadio" id="blankRadio1" value="option1" aria-label="..."> Aplicações bancárias
                        </label>
                    </td>
                </tr>
                <tr>
                    <td>Tipo:
                        <input type="radio" name="blankRadio" id="blankRadio1" value="option1" aria-label="..."> valor absoluto
                        <input type="radio" name="blankRadio" id="blankRadio1" value="option1" aria-label="..."> densidade geográfica
                        <input type="radio" name="blankRadio" id="blankRadio1" value="option1" aria-label="..."> per capita
                    </td>
                </tr>
                </tbody>
            </table>
        </div>

        <div>
            <button type="button" class="btn btn-primary"><i class="fa fa-search" aria-hidden="true"></i> Pesquisar</button>
        </div>

    </div>
@endsection