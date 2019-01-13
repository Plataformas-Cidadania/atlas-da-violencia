<div class="row">
    <div class="col-md-12">
        <p ng-class="{'alto-contraste': altoContrasteAtivo}">{!!$quem->descricao!!}</p>
    </div>
</div>

{{--////////////////////Equipe START////////////////////--}}
    @foreach($versoes as $versao)
        <?php
        $cordenadores = DB::table('integrantes')
            ->select('integrantes.id', 'integrantes.titulo', 'integrantes.imagem', 'integrantes.url', 'items_versoes.funcao')
            ->join('items_versoes', 'integrantes.id', '=', 'items_versoes.integrante_id')
            ->where('items_versoes.versao_id', $versao->id)
            ->where('items_versoes.tipo_id', 1)
            ->where('items_versoes.status', 1)
            ->get();
        $equipe = DB::table('integrantes')
            ->select('integrantes.id', 'integrantes.titulo', 'integrantes.imagem', 'integrantes.url', 'items_versoes.funcao')
            ->join('items_versoes', 'integrantes.id', '=', 'items_versoes.integrante_id')
            ->where('items_versoes.versao_id', $versao->id)
            ->where('items_versoes.tipo_id', 2)
            ->where('items_versoes.status', 1)
            ->orderBy('integrantes.titulo')
            ->get();

        ?>
        <h2>{{$versao->titulo}}</h2><br>

        <div class="row">
            <div class="col-md-12">
                <div><strong>Coordenadores:<br><br></strong>

                    @foreach($cordenadores as $cordenador)
                        <a href="{{$cordenador->url}}">
                            <div>
                                <div style="float: left;">
                                    @if($cordenador->imagem)
                                        <img src="imagens/integrantes/xs-{{$cordenador->imagem}}" alt="{{$cordenador->titulo}}" title="{{$cordenador->titulo}}" class="img-user">
                                    @else
                                        <img src="img/default-user.png" class="img-user">
                                    @endif
                                </div>
                                <div style="float: left;">
                                    <h4 class="title-equipe">{{$cordenador->titulo}}</h4>
                                    <div class="funcao-equipe">{{$cordenador->funcao}}</div>
                                </div>
                            </div>
                        </a>
                    @endforeach
                </div>
                <br>
            </div>
            <div class="col-md-12">
                <div>
                    <br><br>

                    <strong>Equipe Técnica:<br><br></strong>

                    <div style="clear: both;"></div>
                    @foreach($equipe as $integrante)
                        <div class="box-integrante">
                            <a href="{{$integrante->url}}" target="_blank">
                                <div style="float: left;">
                                    @if($integrante->imagem)
                                        <img src="imagens/integrantes/xs-{{$integrante->imagem}}" alt="{{$integrante->titulo}}" title="{{$integrante->titulo}}" class="img-user">
                                    @else
                                        <img src="img/default-user.png" class="img-user">
                                    @endif
                                </div>
                                <div style="float: left;">
                                    <h4 class="title-equipe">{{$integrante->titulo}}</h4>
                                    <div class="funcao-equipe">{{$integrante->funcao}}</div>
                                </div>
                            </a>
                        </div>
                    @endforeach
                    <div style="clear: both;"><br>
                        <hr></div>

                </div>
            </div>
        </div>


    @endforeach
{{--////////////////////Equipe END////////////////////--}}
<style>
    .title-equipe{
        font-size: 16px;
    }
    .funcao-equipe{
        clear: both;
        font-size: 12px;
        margin-top: -9px;
    }
</style>