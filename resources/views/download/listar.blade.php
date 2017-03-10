@extends('.layout')
@section('title', 'Downloads')
@section('content')
    {{--{{ Counter::count('download') }}--}}
    <div class="container">
        <h2>Downloads</h2>
        <div class="line_title bg-pri"></div>
        <div class="row">
            <br>
        {{--@foreach($downloads as $download)
                <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
                    <iframe width="100%" height="315" src="https://www.youtube.com/embed/@if(!empty($download)){{codigoYoutube($download->link_download)}}@endif" frameborder="0" allowfullscreen></iframe>
                    <div><br></div>
                </div>

        @endforeach--}}
        </div>
        <div>{{--{{ $downloads->links() }}--}}</div>

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
            <tr>
                <th scope="row">1</th>
                <td>Título arquivo</td>
                <td>2 MB</td>
                <td width="100" align="center"><i class="fa fa-cloud-download fa-2x" aria-hidden="true"></i></td>
            </tr>
            <tr>
                <th scope="row">2</th>
                <td>Título arquivo 2</td>
                <td>500 kb</td>
                <td width="100" align="center"><i class="fa fa-cloud-download fa-2x" aria-hidden="true"></i></td>
            </tr>
            <tr>
                <th scope="row">3</th>
                <td>Título arquivo 3</td>
                <td>300 kb</td>
                <td width="100" align="center"><i class="fa fa-cloud-download fa-2x" aria-hidden="true"></i></td>
            </tr>
            </tbody>
        </table>

    </div>
@endsection