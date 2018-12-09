@extends('cms::layouts.app')
@section('content')
    <div class="row">
        <div class="col-md-12">
            <h1><i class="fa fa-files-o"></i> Logs</h1>
            <br><br>
            @foreach($logs as $log)
                <p><a href="cms/download-log/{{$log}}" target="_blank">{{$log}}</a></p>
            @endforeach
        </div>
    </div>
@endsection