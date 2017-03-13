@extends('.layout')
@section('title', 'Redirecionamento')
@section('content')
    <div class="container">
        <h1>{{$texto->titulo}}</h1>
        <div class="line_title bg-pri"></div>
        <br>
        <p>{!! $texto->descricao !!}</p>

        <script type="text/javascript">
            <!--
            var numero = 10;
            function chamar(){if(numero>0){document.getElementById('timers').innerHTML = --numero;}}
            setInterval("chamar();", 1000);
            setTimeout("document.location = 'link/{{$link->id}}/{{clean($link->titulo)}}';",10000);
            //-->
        </script>
        <br />
        <div align="center" style="font-family: tahoma; font-size: 16px;">
            Você será redirecionado automaticamente para a página em: <br />
            <br />
            <br />
            <div id="timers" class="btn btn-default l" style="font-family: tahoma; font-size: 56px;">10</div>
            ou
            <a class="btn btn-primary btn-lg" href="link/{{$link->id}}/{{clean($link->titulo)}}">Clique Aqui</a>
        </div>


    </div>
@endsection