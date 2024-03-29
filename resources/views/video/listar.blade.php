@extends('.layout')
@section('title',  trans('links.videos'))
@section('content')
    {{--{{ Counter::count('video') }}--}}
    <div class="container">
        <h2>
            <div class="row">
                <div class="col-md-12">
                    <a href="videos" style="color: #333;">@lang('links.videos')</a>
                </div>
            </div>
        </h2>
        <div class="line_title bg-pri"></div>

        @if($textVideos)
            <br>
            <p>{!! $textVideos->descricao !!}</p>
            <br>
        @endif

        <div class="row">
            <h3>
                <div class="col-md-6">
                    <a href="videos" style="color: #333;">@lang('links.videos-ipea')</a>
                </div>
                <div class="col-md-6 text-right">
                    <a href="videos/1" style="color: #333;">@lang('links.outros-videos')</a>
                </div>
            </h3>
        </div>
        <br>
        <div class="row">
            <br>
            <div class="col-md-12 text-right">
                <form class="form-inline" action="busca-videos" method="post">
                    <input type="hidden" name="outros" id="outros" value="{{$outros}}">
                    {!! csrf_field() !!}
                    <div class="form-group">
                        <label class="sr-only" for="exampleInputAmount">Busca</label>
                        <div class="input-group">
                            <input type="text" class="form-control" id="busca" name="busca" placeholder="@lang('forms.search')" value="{{$search}}">
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
                        <?php /*if($video->descricao){*/?>
                            <div style="height: 50px;">
                                @if($video->descricao){!! cutText($video->descricao, 140, '...') !!}@else &nbsp; @endif
                            </div>
                            <hr>

                        <p style="font-size: 12px; font-weight: bold; text-align: center; cursor:pointer;" data-toggle="modal" data-target=".bs-example-modal-lg{{$video->id}}">@lang('pages.show-more')</p>



                        <div class="modal fade bs-example-modal-lg{{$video->id}}" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel">
                            <div class="modal-dialog modal-lg" role="document">
                                <div class="modal-content">

                                    <div class="modal-header">
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                        <h4 class="modal-title" id="myModalLabel">{{$video->titulo}}</h4>
                                    </div>
                                    <div class="modal-body">
                                        <iframe width="100%" height="400" src="https://www.youtube.com/embed/@if(!empty($video)){{codigoYoutube($video->link_video)}}@endif" frameborder="0" allowfullscreen style="position: relative; z-index: 99999999999999999999999999;"></iframe>
                                        <br><br>
                                        <p>{!! $video->descricao !!}</p>
                                    </div>


                                </div>
                            </div>
                        </div>

                        <?php /*}else{*/?><!--
                        <div style="height: 50px;">
                            &nbsp;&nbsp;
                        </div>
                        <hr>
                        --><?php /*}*/?>
                    <br>
                    <br>
                    </div>
                @endforeach
        </div>
        <div>{{ $videos->links() }}</div>

    </div>
@endsection