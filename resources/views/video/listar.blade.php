@extends('.layout')
@section('title', 'Notícias')
@section('content')
    {{--{{ Counter::count('video') }}--}}
    <div class="container">
        <h2>Vídeos</h2>
        <div class="line_title bg-pri"></div>
        <div class="row">
            <br>
        @foreach($videos as $video)
                <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
                    <iframe width="100%" height="315" src="https://www.youtube.com/embed/@if(!empty($video)){{codigoYoutube($video->link_video)}}@endif" frameborder="0" allowfullscreen></iframe>
                </div>

        @endforeach
        </div>
        <div>{{ $videos->links() }}</div>

    </div>
@endsection