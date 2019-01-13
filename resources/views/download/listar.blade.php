@extends('.layout')
@section('title',  trans('links.downloads'))
@section('content')


    <div class="container">
        <h2>@lang('links.downloads')</h2>
        <div class="line_title bg-pri"></div>


        <div class="row">
            <br>
            <div class="col-md-12 text-right">
                <form class="form-inline" action="busca-downloads" method="post">
                    {!! csrf_field() !!}
                    <div class="form-group">
                        <label class="sr-only" for="exampleInputAmount">Busca</label>
                        <div class="input-group">
                            <input type="text" class="form-control" id="busca" name="busca" placeholder="@lang('forms.search')">
                            <div class="input-group-addon">
                                <button type="submit" value="busca-downloads" style="border: 0; background-color: inherit;"><i class="fa fa-search" aria-hidden="true"></i></button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <table class="table table-hover">

            <thead>
            <tr>
                <th>@lang('forms.name')</th>
                {{--<th>@lang('pages.serie')</th>--}}
                <th>Origem</th>
                <th>@lang('links.downloads')</th>
            </tr>
            </thead>
            <tbody>
            <?php $lang = \Illuminate\Support\Facades\App::getLocale();?>
            @foreach($downloads as $download)

                <?php
                    if($download->origem == 0){
                        $download->tituloOrigem = trans('pages.publications');
                    }
                    if($download->origem == 1){
                        $serie = DB::table('textos_series')->where('serie_id', $download->origem_id)->where('idioma_sigla', $lang)->first();
                        $download->tituloOrigem = "-------------";
                        if(!empty($serie)){
                            $download->tituloOrigem = $serie->titulo;
                        }
                    }
                    if($download->origem == 2){
                        $consulta = DB::table('idiomas_consultas')->where('consulta_id', $download->origem_id)->where('idioma_sigla', $lang)->first();
                        $download->tituloOrigem = "-------------";
                        if(!empty($consulta)){
                            $download->tituloOrigem = $consulta->titulo;
                        }
                    }


                ?>
                <tr>
                    <td>{{$download->titulo}}</td>
                    <td>{{$download->tituloOrigem}}</td>
                    <td width="100" align="center">
                        <a href="arquivos/downloads/{{$download->arquivo}}" target="_blank">
                            <i class="fa fa-cloud-download fa-2x" aria-hidden="true"></i>
                        </a>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
        <div>{{ $downloads->links() }}</div>
    </div>
@endsection

