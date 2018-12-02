<div class="row">
    <div class="col-md-12">
        <p ng-class="{'alto-contraste': altoContrasteAtivo}">{!!$quem->descricao!!}</p>
    </div>
</div>

{{--////////////////////Equipe START////////////////////--}}
    @foreach($versoes as $versao)
        <?php
        $cordenadores = DB::table('integrantes')
            ->select('integrantes.id', 'integrantes.titulo', 'integrantes.imagem', 'integrantes.url')
            ->join('items_versoes', 'integrantes.id', '=', 'items_versoes.integrante_id')
            ->where('items_versoes.versao_id', $versao->id)
            ->where('items_versoes.tipo_id', 1)
            ->where('items_versoes.status', 1)
            ->get();
        $equipe = DB::table('integrantes')
            ->select('integrantes.id', 'integrantes.titulo', 'integrantes.imagem', 'integrantes.url')
            ->join('items_versoes', 'integrantes.id', '=', 'items_versoes.integrante_id')
            ->where('items_versoes.versao_id', $versao->id)
            ->where('items_versoes.tipo_id', 2)
            ->where('items_versoes.status', 1)
            ->get();

        ?>
        <h2>{{$versao->titulo}}</h2><br>
        <div><strong>Coordenadores:<br><br></strong>

            @foreach($cordenadores as $cordenador)
                <a href="{{$cordenador->url}}">
                    <div>
                        @if($cordenador->imagem)
                        <img src="imagens/integrantes/xs-{{$cordenador->imagem}}" alt="{{$cordenador->titulo}}" title="{{$cordenador->titulo}}" class="img-user">
                        @else
                        <img src="img/default-user.png" class="img-user">
                        @endif
                        {{$cordenador->titulo}}
                    </div>
                </a>
            @endforeach
        </div>
        <br>
        <div>
            <br>
            <strong>Equipe Técnica:<br><br></strong>

            <div style="clear: both;"></div>
            @foreach($equipe as $integrante)
            <div class="box-integrante">
                <a href="{{$integrante->url}}" target="_blank">
                    @if($integrante->imagem)
                        <img src="imagens/integrantes/xs-{{$integrante->imagem}}" alt="{{$integrante->titulo}}" title="{{$integrante->titulo}}" class="img-user">
                    @else
                        <img src="img/default-user.png" class="img-user">
                    @endif
                    {{$integrante->titulo}}
                </a>
            </div>
            @endforeach
             <div style="clear: both;"><br>
                 <hr></div>

        </div>
    @endforeach
{{--////////////////////Equipe END////////////////////--}}
