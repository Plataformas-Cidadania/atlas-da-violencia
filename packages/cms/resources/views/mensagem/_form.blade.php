<style>
    .hr-list{
        margin: 10px 0px; padding: 0px;
    }
</style>
<div class="row">
    <div class="col-md-12">
        <br>
        <br>
        <h4><strong><% mensagem.titulo %></strong></h4>
        <hr>
    </div>
    <div class="col-md-12" ng-init="mensagem.nome='{{$mensagem->nome}}'">
        <strong>Nome: </strong> <% mensagem.nome %><hr class="hr-list">
    </div>
    <div class="col-md-12" ng-init="mensagem.email='{{$mensagem->email}}'">
        <strong>E-mail: </strong> <a href="mailto:<% mensagem.email"><% mensagem.email %></a><hr class="hr-list">
    </div>
    <div class="col-md-12" ng-init="mensagem.telefone='{{$mensagem->telefone}}'">
        <strong >Telefone: </strong> <% mensagem.telefone %><hr class="hr-list">
    </div>
    <div class="col-md-12" ng-init="mensagem.mensagem='{{$mensagem->mensagem}}'">
        <strong>Mensagem: </strong> <br><% mensagem.mensagem %>
    </div>
</div>
