@extends('cms::layouts.app')
@section('content')
    <div class="row">
        <div class="col-md-12">
            <h1><i class="fa fa-files-o"></i> Logs</h1>
            <br><br>
            @foreach($logs as $log)
                @if(substr($log, -4)!=".zip")
                <p><a href="cms/download-log/{{substr($log, 0, -4)}}" target="_blank">{{$log}}</a></p>
                @endif
            @endforeach
        </div>
    </div>
@endsection