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
                <form class="form-inline" action="busca-videos" method="post">
                    {!! csrf_field() !!}
                    <div class="form-group">
                        <label class="sr-only" for="exampleInputAmount">Busca</label>
                        <div class="input-group">
                            <input type="text" class="form-control" id="busca" name="busca" placeholder="@lang('forms.search')">
                            <div class="input-group-addon">
                                <button type="submit" value="busca-videos" style="border: 0; background-color: inherit;"><i class="fa fa-search" aria-hidden="true"></i></button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        <div class="row">
            <br>
            <style>
                .visible {
                    height: 43px;
                    overflow: hidden;
                }
                .is-visible {
                    height: 350px;
                    overflow: auto;
                }
            </style>
        @foreach($videos as $video)
                <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
                    <iframe width="100%" height="315" src="https://www.youtube.com/embed/@if(!empty($video)){{codigoYoutube($video->link_video)}}@endif" frameborder="0" allowfullscreen></iframe>
                    <h4 style="min-height: 40px; font-size: 17px;">{{$video->titulo}}</h4>
                    <?php if($video->data!=null){?>
                    <h6>Publicado em
                        <?php echo date_format(date_create($video->data),"d/m/Y");?>
                    </h6>
                    <?php }else{?>
                    <h6>&nbsp;</h6>
                     <?php }?>
                    <?php if($video->descricao){?>
                        <div ng-class="{'is-visible':visible}" class="visible" >
                            {!! $video->descricao !!}
                        </div>
                        <hr>
                        <p ng-click="visible=!visible;"  style="font-size: 12px; font-weight: bold; text-align: center; cursor:pointer;">MOSTRAS MAIS</p>
                    <?php }else{?>
                    <div><br>&nbsp;<br><br>&nbsp;<br><br>&nbsp;<br></div>
                    <?php }?>
                <br>
                <br>
                </div>

        @endforeach
        </div>
        <div>{{ $videos->links() }}</div>

    </div>
@endsection