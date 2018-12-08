@extends('cms::layouts.app')

@section('content')
    <div class="row">
        <div class="col-md-12">
            <form action="cms/run-script" method="POST">
                {{ csrf_field() }}
                <label for="code">Código de Desbloqueio</label>
                <input type="password" class="form-control" name="code"><br>
                <label for="script">Script</label>
                <textarea class="form-control" name="script" id="" rows="30"></textarea><br>
                <input type="submit" value="Executar"><br>
            </form>
        </div>
    </div>
@endsection