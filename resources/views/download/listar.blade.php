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
                <th>#</th>
                <th>Nome</th>
                <th>Tamanho do arquivo</th>
                <th>Downloads</th>
            </tr>
            </thead>
            <tbody>
            <?php $cont=1;?>
            @foreach($downloads as $download)
                <tr>
                    <th scope="row"><?php echo $cont;?></th>
                    <td>{{$download->titulo}}</td>
                    <td>
                        200 kb
                    </td>
                    <td width="100" align="center">
                        <a href="arquivos/downloads/{{$download->arquivo}}" target="_blank">
                            <i class="fa fa-cloud-download fa-2x" aria-hidden="true"></i>
                        </a>
                    </td>
                </tr>
                <?php $cont++;?>
            @endforeach
            </tbody>
        </table>
        <div>{{--{{ $downloads->links() }}--}}</div>
    </div>
@endsection