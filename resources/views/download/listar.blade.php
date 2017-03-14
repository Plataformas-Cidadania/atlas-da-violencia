@extends('.layout')
@section('title', 'Downloads')
@section('content')
    {{--{{ Counter::count('download') }}--}}

    <div class="container">
        <h2>Downloads</h2>
        <div class="line_title bg-pri"></div>

        <table class="table table-hover">
            <thead>
            <tr>
                <th>Nome</th>
                <th>Serie</th>
                <th>Downloads</th>
            </tr>
            </thead>
            <tbody>
            @foreach($downloads as $download)
                <tr>
                    <td>{{$download->titulo}}</td>
                    <td>
                        <?php $serie = DB::table('series')->where('id', $download->origem_id)->first();?>

                        {{$serie->titulo}}
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

