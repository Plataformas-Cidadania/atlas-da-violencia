@extends('.layout')
@section('title',  trans('links.videos'))
@section('content')
    {{--{{ Counter::count('video') }}--}}
    <div class="container">
        <h2>@lang('links.videos')</h2>
        <div class="line_title bg-pri"></div>
        <div class="row">
            <br>
            <div class="col-md-12 text-right">
                <form class="form-inline" action="/busca-videos" method="post">
                    {!! csrf_field() !!}
                    <div class="form-group">
                        <label class="sr-only" for="exampleInputAmount">Busca</label>
                        <div class="input-group">
                            <input type="text" class="form-control" id="busca" name="busca" placeholder="@lang('forms.search')">
                            <div class="input-group-addon">
                                <i class="fa fa-search" aria-hidden="true"></i>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        <div class="row">
            <br>
        @foreach($videos as $video)
                <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
                    <iframe width="100%" height="315" src="https://www.youtube.com/embed/@if(!empty($video)){{codigoYoutube($video->link_video)}}@endif" frameborder="0" allowfullscreen></iframe>
                    <div><br></div>
                </div>

        @endforeach
        </div>
        <div>{{ $videos->links() }}</div>

    </div>
@endsection