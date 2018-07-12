@extends('.layout')
@section('title',  trans('links.downloads'))
@section('content')


    <div class="container">
        <h2>@lang('links.downloads')</h2>
        <div class="line_title bg-pri"></div>

        <table class="table table-hover">
            <thead>
            <tr>
                <th>@lang('forms.name')</th>
                <th>@lang('pages.serie')</th>
                <th>@lang('links.downloads')</th>
            </tr>
            </thead>
            <tbody>
            @foreach($downloads as $download)
                <tr>
                    <td>{{$download->titulo}}</td>
                    <td>
                        <?php $serie = DB::table('series')->where('id', $download->origem_id)->first();?>

                        <?php if($serie){?>{{$serie->titulo}}<?php }else{?>Publicações Atlas<?php }?>
                    </td>
                    <td width="100" align="center">
                        <a href="arquivos/downloads/{{$download->arquivo}}" target="_blank">
                            <i class="fa fa-cloud-download fa-2x" aria-hidden="true"></i>
                        </a>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
        <div>{{--{{ $downloads->links() }}--}}</div>
    </div>
@endsection

