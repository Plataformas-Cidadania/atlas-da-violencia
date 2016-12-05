<div style="background-color: #EEEEEE;">
    <table width="600px" cellpadding="20" cellspacing="20" align="center">
        <tr>
            <td>
                <div style="background-color: #FFFFFF; width: 600px; margin: 20px; padding: 30px;">
                    <div><img src="http://<?php echo $_SERVER['HTTP_HOST'];?>/img/{{$settings->imagem}}" alt="{{$settings->titulo}}" title="{{$settings->titulo}}" class="logo"></div>
                    <div style="height: 1px; background-color: #CCCCCC; margin: 10px;"></div>
                    <p>Nome: {{$dados['nome']}}</p>
                    <p>E-mail: {{$dados['email']}}</p>
                    <p>Telefone: {{$dados['telefone']}}</p>
                    <p>{!!str_replace("\n", "<br>", $dados['mensagem'])!!}</p>
                    <br>
                </div>
            </td>
        </tr>
    </table>
</div>