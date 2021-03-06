
<div>
    <!-- Nav tabs -->
    <ul class="nav nav-tabs" role="tablist">
        <li role="presentation" class="active"><a href="#site" aria-controls="site" role="tab" data-toggle="tab">Site</a></li>
        <li role="presentation"><a href="#home" aria-controls="home" role="tab" data-toggle="tab">Home</a></li>
        <li role="presentation"><a href="#contato" aria-controls="contato" role="tab" data-toggle="tab">Contato</a></li>
        <li role="presentation"><a href="#cor" aria-controls="cor" role="tab" data-toggle="tab">Cores</a></li>
        <li role="presentation"><a href="#redes" aria-controls="redes" role="tab" data-toggle="tab">Redes</a></li>
        <li role="presentation"><a href="#series" aria-controls="home" role="tab" data-toggle="tab">Séries</a></li>
        <li role="presentation"><a href="#pontos" aria-controls="pontos" role="tab" data-toggle="tab">Pontos</a></li>
        <li role="presentation"><a href="#emails" aria-controls="emails" role="tab" data-toggle="tab">E-mail</a></li>
        <li role="presentation"><a href="#analytics" aria-controls="analytics" role="tab" data-toggle="tab">Analytics</a></li>
    </ul>

    <!-- Tab panes -->
    <div class="tab-content">
        <div role="tabpanel" class="tab-pane active" id="site">
            <br>
            {!! Form::label('titulo', 'Nome') !!}<br>
            {!! Form::text('titulo', null, ['class'=>"form-control width-grande <% validar(setting.titulo) %>", 'ng-model'=>'setting.titulo', 'ng-required'=>'true', 'init-model'=>'setting.titulo']) !!}<br>

            {!! Form::label('descricao_contato', 'Informação contato') !!}<br>
            {!! Form::text('descricao_contato', null, ['class'=>"form-control width-grande <% validar(setting.descricao_contato) %>", 'ng-model'=>'setting.descricao_contato', 'ng-required'=>'true', 'init-model'=>'setting.descricao_contato']) !!}<br>

            {!! Form::label('rodape', 'Rodapé') !!}<br>
            {!! Form::text('rodape', null, ['class'=>"form-control width-grande <% validar(setting.rodape) %>", 'ng-model'=>'setting.rodape', 'ng-required'=>'true', 'init-model'=>'setting.rodape']) !!}<br>

            {!! Form::label('qtd_temas_home', 'Quantidade itens Home *') !!}<br>
            {!! Form::select('qtd_temas_home',
                    array(
                        '50' => '2 Itens por linha',
                        '33' => '3 Itens por linha',
                        '25' => '4 Itens por linha',
                        '20' => '5 Itens por linha',
                        '16' => '6 Itens por linha',
                    ),
            null, ['class'=>"form-control width-medio <% validar(setting.qtd_temas_home) %>", 'ng-model'=>'setting.qtd_temas_home', 'ng-required'=>'true', 'init-model'=>'setting.qtd_temas_home', 'placeholder' => '']) !!}<br>

            {!! Form::label('h1', 'H1') !!}<br>
            {!! Form::text('h1', null, ['class'=>"form-control width-grande <% validar(setting.h1) %>", 'ng-model'=>'setting.h1', 'ng-required'=>'true', 'init-model'=>'setting.h1', 'placeholder'=>'Ex: font-size:24px;']) !!}<br>

            {!! Form::label('h2', 'H2') !!}<br>
            {!! Form::text('h2', null, ['class'=>"form-control width-grande <% validar(setting.h2) %>", 'ng-model'=>'setting.h2', 'ng-required'=>'true', 'init-model'=>'setting.h2', 'placeholder'=>'Ex: font-size:24px;']) !!}<br>

            {!! Form::label('h3', 'H3') !!}<br>
            {!! Form::text('h3', null, ['class'=>"form-control width-grande <% validar(setting.h3) %>", 'ng-model'=>'setting.h3', 'ng-required'=>'true', 'init-model'=>'setting.h3', 'placeholder'=>'Ex: font-size:24px;']) !!}<br>


        </div>
        <div role="tabpanel" class="tab-pane" id="home">
            <br>
            {!! Form::label('dados_serie_home', 'Dados Série Home') !!}<br>
            {!! Form::select('dados_serie_home',
                    array(
                        '0' => 'Série',
                        '1' => 'CSV',
                    ),
            null, ['class'=>"form-control width-medio <% validar(setting.dados_serie_home) %>", 'ng-model'=>'setting.dados_serie_home', 'ng-required'=>'true', 'init-model'=>'setting.dados_serie_home', 'placeholder' => 'Selecione']) !!}<br>

            {!! Form::label('cores_serie_home', 'Cores da Série') !!}<br>
            {!! Form::text('cores_serie_home', null, ['class'=>"form-control width-grande <% validar(setting.cores_serie_home) %>", 'ng-model'=>'setting.cores_serie_home', 'ng-required'=>'true', 'init-model'=>'setting.cores_serie_home']) !!}<br>

            <div ng-show="setting.dados_serie_home==0">
                {!! Form::label('serie_id', 'Séries home *') !!}<br>
                {!! Form::select('serie_id',
                        $series,
                null, ['class'=>"form-control width-grande <% validar(setting.serie_id) %>", 'ng-model'=>'setting.serie_id', 'ng-required'=>'true', 'init-model'=>'setting.serie_id', 'placeholder' => 'Selecione']) !!}<br>
            </div>

            <div ng-show="setting.dados_serie_home==1">
                {!! Form::label('titulo_serie_home', 'Título da Série') !!}<br>
                {!! Form::text('titulo_serie_home', null, ['class'=>"form-control width-grande <% validar(setting.titulo_serie_home) %>", 'ng-model'=>'setting.titulo_serie_home', 'ng-required'=>'true', 'init-model'=>'setting.titulo_serie_home']) !!}<br>

                <span class="btn btn-primary btn-file" ng-show="!fileCsvSerie && !csvSerieBD">
                    Escolher CSV da Série <input  type="file" ngf-select ng-model="fileCsvSerie" name="fileCsvSerie" accept=".csv" ngf-max-size="100MB" ngf-model-invalid="errorFile">
                    </span>
                <button class="btn btn-danger" ng-click="limparCsvSerie()" ng-show="fileCsvSerie || csvSerieBD" type="button">Remover Arquivo</button>
                <a href="arquivos/settings/<% csvSerieBD %>" target="_blank" ng-show="csvSerieBD"><% csvSerieBD %></a>
                <a ng-show="fileCsvSerie"><% fileCsvSerie.name %></a>
                <br><br>
                <div><strong>Exemplo: teste.csv</strong></div>
                <div style="padding: 10px; background-color: #ccc; color:#333; width:400px;">
                    dataset;name;x;y<br>
                    0;Série A;2015;10<br>
                    0;Série A;2016;15<br>
                    0;Série A;2017;18<br>
                    0;Série A;2018;20<br>
                    1;Série B;2015;15<br>
                    1;Série B;2016;19<br>
                    1;Série B;2017;16<br>
                    1;Série B;2018;18<br>
                </div>
                <br>
            </div>

            {!! Form::label('video_home', 'Video') !!}<br>
            {!! Form::select('video_home',
                    array(
                        '0' => 'Não',
                        '1' => 'Sim',
                    ),
            null, ['class'=>"form-control width-medio <% validar(setting.video_home) %>", 'ng-model'=>'setting.video_home', 'ng-required'=>'true', 'init-model'=>'setting.video_home', 'placeholder' => '']) !!}<br>

            {!! Form::label('carousel', 'Carousel') !!}<br>
            {!! Form::select('carousel',
                    array(
                        '0' => 'Não',
                        '1' => 'Sim',
                    ),
            null, ['class'=>"form-control width-medio <% validar(setting.carousel) %>", 'ng-model'=>'setting.carousel', 'ng-required'=>'true', 'init-model'=>'setting.carousel', 'placeholder' => '']) !!}<br>

            {!! Form::label('links', 'Links') !!}<br>
            {!! Form::select('links',
                    array(
                        '0' => 'Não',
                        '1' => 'Sim',
                    ),
            null, ['class'=>"form-control width-medio <% validar(setting.links) %>", 'ng-model'=>'setting.links', 'ng-required'=>'true', 'init-model'=>'setting.links', 'placeholder' => '']) !!}<br>

        </div>
        <div role="tabpanel" class="tab-pane" id="contato">
            <br>

            {!! Form::label('dados_contato', 'Dados') !!}<br>
            {!! Form::textarea('dados_contato', null, ['class'=>"form-control width-grande <% validar(setting.dados_contato) %>", 'ui-tinymce'=>'tinymceOptions', 'ng-model'=>'setting.dados_contato', 'init-model'=>'setting.dados_contato']) !!}<br>

            <div style="display:none;">
                {!! Form::label('email', 'E-mail') !!}<br>
                {!! Form::text('email', null, ['class'=>"form-control width-grande <% validar(setting.email) %>", 'ng-model'=>'setting.email', 'ng-required'=>'true', 'init-model'=>'setting.email']) !!}<br>

                <div class="row">
                    <div class="col-md-2">
                        {!! Form::label('telefone', 'Telefone') !!}<br>
                        {!! Form::text('telefone', null, ['class'=>"form-control width-grande <% validar(setting.telefone) %>", 'ng-model'=>'setting.telefone', 'ng-required'=>'true', 'init-model'=>'setting.telefone']) !!}<br>
                    </div>
                    <div class="col-md-2">
                        {!! Form::label('telefone2', 'Telefone 2 ') !!}<br>
                        {!! Form::text('telefone2', null, ['class'=>"form-control width-grande <% validar(setting.telefone2) %>", 'ng-model'=>'setting.telefone2', 'ng-required'=>'true', 'init-model'=>'setting.telefone2']) !!}<br>
                    </div>
                    <div class="col-md-2">
                        {!! Form::label('telefone3', 'Telefone 3') !!}<br>
                        {!! Form::text('telefone3', null, ['class'=>"form-control width-grande <% validar(setting.telefone3) %>", 'ng-model'=>'setting.telefone3', 'ng-required'=>'true', 'init-model'=>'setting.telefone3']) !!}<br>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-5">
                        {!! Form::label('endereco', 'Endereço') !!}<br>
                        {!! Form::text('endereco', null, ['class'=>"form-control width-grande <% validar(setting.endereco) %>", 'ng-model'=>'setting.endereco', 'ng-required'=>'true', 'init-model'=>'setting.endereco']) !!}<br>
                    </div>
                    <div class="col-md-2">
                        {!! Form::label('numero', 'Numero') !!}<br>
                        {!! Form::text('numero', null, ['class'=>"form-control width-grande <% validar(setting.numero) %>", 'ng-model'=>'setting.numero', 'ng-required'=>'true', 'init-model'=>'setting.numero']) !!}<br>
                    </div>
                </div>
                {!! Form::label('complemento', 'Complemento') !!}<br>
                {!! Form::text('complemento', null, ['class'=>"form-control width-grande <% validar(setting.complemento) %>", 'ng-model'=>'setting.complemento', 'ng-required'=>'true', 'init-model'=>'setting.complemento']) !!}<br>

                {!! Form::label('bairro', 'Bairro') !!}<br>
                {!! Form::text('bairro', null, ['class'=>"form-control width-grande <% validar(setting.bairro) %>", 'ng-model'=>'setting.bairro', 'ng-required'=>'true', 'init-model'=>'setting.bairro']) !!}<br>

                {!! Form::label('cidade', 'Cidade') !!}<br>
                {!! Form::text('cidade', null, ['class'=>"form-control width-grande <% validar(setting.cidade) %>", 'ng-model'=>'setting.cidade', 'ng-required'=>'true', 'init-model'=>'setting.cidade']) !!}<br>

                {!! Form::label('estado', 'Estado') !!}<br>
                {!! Form::text('estado', null, ['class'=>"form-control width-grande <% validar(setting.estado) %>", 'ng-model'=>'setting.estado', 'ng-required'=>'true', 'init-model'=>'setting.estado']) !!}<br>

                {!! Form::label('cep', 'CEP.') !!}<br>
                {!! Form::text('cep', null, ['class'=>"form-control width-grande <% validar(setting.cep) %>", 'ng-model'=>'setting.cep', 'ng-required'=>'true', 'init-model'=>'setting.cep']) !!}<br>

                {!! Form::label('latitude', 'Latitude') !!}<br>
                {!! Form::text('latitude', null, ['class'=>"form-control width-grande <% validar(setting.latitude) %>", 'ng-model'=>'setting.latitude', 'ng-required'=>'true', 'init-model'=>'setting.latitude']) !!}<br>

                {!! Form::label('longitude', 'Longitude') !!}<br>
                {!! Form::text('longitude', null, ['class'=>"form-control width-grande <% validar(setting.longitude) %>", 'ng-model'=>'setting.longitude', 'ng-required'=>'true', 'init-model'=>'setting.longitude']) !!}<br>

            </div>

        </div>
        <div role="tabpanel" class="tab-pane" id="cor">
            <br>
            <div class="row">
                <div class="col-md-1">
                    {!! Form::label('cor1', 'Cor 1') !!}<br>
                    {!! Form::color('cor1', null, ['class'=>"form-control width-grande <% validar(setting.cor1) %>", 'ng-model'=>'setting.cor1', 'ng-required'=>'true', 'init-model'=>'setting.cor1']) !!}<br>
                </div>
                <div class="col-md-1">
                    {!! Form::label('cor2', 'Cor 2') !!}<br>
                    {!! Form::color('cor2', null, ['class'=>"form-control width-grande <% validar(setting.cor2) %>", 'ng-model'=>'setting.cor2', 'ng-required'=>'true', 'init-model'=>'setting.cor2']) !!}<br>
                </div>
                <div class="col-md-1">
                    {!! Form::label('cor3', 'Cor 3') !!}<br>
                    {!! Form::color('cor3', null, ['class'=>"form-control width-grande <% validar(setting.cor3) %>", 'ng-model'=>'setting.cor3', 'ng-required'=>'true', 'init-model'=>'setting.cor3']) !!}<br>
                </div>
                <div class="col-md-1">
                    {!! Form::label('cor4', 'Cor 4') !!}<br>
                    {!! Form::color('cor4', null, ['class'=>"form-control width-grande <% validar(setting.cor4) %>", 'ng-model'=>'setting.cor4', 'ng-required'=>'true', 'init-model'=>'setting.cor4']) !!}<br>
                </div>
                <div class="col-md-1">
                    {!! Form::label('cor5', 'Cor 5') !!}<br>
                    {!! Form::color('cor5', null, ['class'=>"form-control width-grande <% validar(setting.cor5) %>", 'ng-model'=>'setting.cor5', 'ng-required'=>'true', 'init-model'=>'setting.cor5']) !!}<br>
                </div>
            </div>
        </div>
        <div role="tabpanel" class="tab-pane" id="redes">
            <br>
            {!! Form::label('facebook', 'Facebook') !!}<br>
            {!! Form::text('facebook', null, ['class'=>"form-control width-grande <% validar(setting.facebook) %>", 'ng-model'=>'setting.facebook', 'ng-required'=>'true', 'init-model'=>'setting.facebook']) !!}<br>

            {!! Form::label('youtube', 'Youtube') !!}<br>
            {!! Form::text('youtube', null, ['class'=>"form-control width-grande <% validar(setting.youtube) %>", 'ng-model'=>'setting.youtube', 'ng-required'=>'true', 'init-model'=>'setting.youtube']) !!}<br>

            {!! Form::label('pinterest', 'Pinterest') !!}<br>
            {!! Form::text('pinterest', null, ['class'=>"form-control width-grande <% validar(setting.pinterest) %>", 'ng-model'=>'setting.pinterest', 'ng-required'=>'true', 'init-model'=>'setting.pinterest']) !!}<br>

            {!! Form::label('twitter', 'Twitter') !!}<br>
            {!! Form::text('twitter', null, ['class'=>"form-control width-grande <% validar(setting.twitter) %>", 'ng-model'=>'setting.twitter', 'ng-required'=>'true', 'init-model'=>'setting.twitter']) !!}<br>

            {!! Form::label('google', 'Google') !!}<br>
            {!! Form::text('google', null, ['class'=>"form-control width-grande <% validar(setting.google) %>", 'ng-model'=>'setting.google', 'ng-required'=>'true', 'init-model'=>'setting.google']) !!}<br>

        </div>
        <div role="tabpanel" class="tab-pane" id="series">
            <br>
            {!! Form::label('consulta_por_temas', 'Consulta por temas *') !!}<br>
            {!! Form::select('consulta_por_temas',
                    array(
                        '0' => 'Todos os temas',
                        '1' => 'Último nivel dos temas',
                    ),
            null, ['class'=>"form-control width-medio <% validar(setting.consulta_por_temas) %>", 'ng-model'=>'setting.consulta_por_temas', 'ng-required'=>'true', 'init-model'=>'setting.consulta_por_temas', 'placeholder' => '']) !!}<br>


            <br>
            {!! Form::label('consulta_filtros_indicadores', 'Consulta filtros indicadores *') !!}<br>
            {!! Form::select('consulta_filtros_indicadores',
                    array(
                        '0' => 'Não',
                        '1' => 'Sim',
                    ),
            null, ['class'=>"form-control width-medio <% validar(setting.consulta_filtros_indicadores) %>", 'ng-model'=>'setting.consulta_filtros_indicadores', 'ng-required'=>'true', 'init-model'=>'setting.consulta_filtros_indicadores', 'placeholder' => '']) !!}<br>

            {!! Form::label('padrao_abrangencia', 'Padrao abrangencia') !!}<br>
            {!! Form::text('padrao_abrangencia', null, ['class'=>"form-control width-grande <% validar(setting.padrao_abrangencia) %>", 'ng-model'=>'setting.padrao_abrangencia', 'ng-required'=>'true', 'init-model'=>'setting.padrao_abrangencia']) !!}<br>

            {!! Form::label('posicao_mapa', 'Posição mapa') !!}<br>
            {!! Form::text('posicao_mapa', null, ['class'=>"form-control width-grande <% validar(setting.posicao_mapa) %>", 'ng-model'=>'setting.posicao_mapa', 'ng-required'=>'true', 'init-model'=>'setting.posicao_mapa']) !!}<br>

            {!! Form::label('posicao_tabela', 'Posição tabela') !!}<br>
            {!! Form::text('posicao_tabela', null, ['class'=>"form-control width-grande <% validar(setting.posicao_tabela) %>", 'ng-model'=>'setting.posicao_tabela', 'ng-required'=>'true', 'init-model'=>'setting.posicao_tabela']) !!}<br>

            {!! Form::label('posicao_grafico', 'Posição gráfico') !!}<br>
            {!! Form::text('posicao_grafico', null, ['class'=>"form-control width-grande <% validar(setting.posicao_grafico) %>", 'ng-model'=>'setting.posicao_grafico', 'ng-required'=>'true', 'init-model'=>'setting.posicao_grafico']) !!}<br>

            {!! Form::label('posicao_taxa', 'Posição taxa') !!}<br>
            {!! Form::text('posicao_taxa', null, ['class'=>"form-control width-grande <% validar(setting.posicao_taxa) %>", 'ng-model'=>'setting.posicao_taxa', 'ng-required'=>'true', 'init-model'=>'setting.posicao_taxa']) !!}<br>

            {!! Form::label('posicao_metadados', 'Posição metadados') !!}<br>
            {!! Form::text('posicao_metadados', null, ['class'=>"form-control width-grande <% validar(setting.posicao_metadados) %>", 'ng-model'=>'setting.posicao_metadados', 'ng-required'=>'true', 'init-model'=>'setting.posicao_metadados']) !!}<br>

        </div>
        <div role="tabpanel" class="tab-pane" id="pontos">
            <br>
            {!! Form::label('pontos_default_regions', 'Regiões') !!}<br>
            {!! Form::text('pontos_default_regions', null, ['class'=>"form-control width-grande <% validar(setting.pontos_default_regions) %>", 'ng-model'=>'setting.pontos_default_regions', 'ng-required'=>'true', 'init-model'=>'setting.pontos_default_regions', 'placeholder' => 'Ex: 1,2,3,4,5']) !!}<br>

            {!! Form::label('pontos_tipo_default_regions', 'Tipo de Região *') !!}<br>
            {!! Form::select('pontos_tipo_default_regions',
                    $optionsAbrangencias,
            null, ['class'=>"form-control <% validar(setting.pontos_tipo_default_regions) %> width-medio", 'ng-model'=>'setting.pontos_tipo_default_regions', 'ng-change' => '', 'ng-required'=>'true', 'init-model'=>'setting.pontos_tipo_default_regions', 'placeholder' => 'Selecione']) !!}<br>


        </div>
        <div role="tabpanel" class="tab-pane" id="emails">
            <br>
            {!! Form::label('email_host', 'Host') !!}<br>
            {!! Form::text('email_host', null, ['class'=>"form-control width-grande <% validar(setting.email_host) %>", 'ng-model'=>'setting.email_host', 'ng-required'=>'true', 'init-model'=>'setting.email_host']) !!}<br>

            {!! Form::label('email_port', 'Porta') !!}<br>
            {!! Form::text('email_port', null, ['class'=>"form-control width-grande <% validar(setting.email_port) %>", 'ng-model'=>'setting.email_port', 'ng-required'=>'true', 'init-model'=>'setting.email_port']) !!}<br>

            {!! Form::label('email_address', 'E-mail') !!}<br>
            {!! Form::text('email_address', null, ['class'=>"form-control width-grande <% validar(setting.email_address) %>", 'ng-model'=>'setting.email_address', 'ng-required'=>'true', 'init-model'=>'setting.email_address']) !!}<br>

            {!! Form::label('email_name', 'Nome Usuário') !!}<br>
            {!! Form::text('email_name', null, ['class'=>"form-control width-grande <% validar(setting.email_name) %>", 'ng-model'=>'setting.email_name', 'ng-required'=>'true', 'init-model'=>'setting.email_name']) !!}<br>

            {!! Form::label('email_user', 'Usuário') !!}<br>
            {!! Form::text('email_user', null, ['class'=>"form-control width-grande <% validar(setting.email_user) %>", 'ng-model'=>'setting.email_user', 'ng-required'=>'true', 'init-model'=>'setting.email_user']) !!}<br>

            {{--{!! Form::label('email_password', 'Senha') !!}<br>
            {!! Form::password('email_password', null, ['class'=>"form-control width-grande <% validar(setting.email_password) %>", 'ng-model'=>'setting.email_password', 'ng-required'=>'true', 'init-model'=>'setting.email_password']) !!}<br>
--}}
            <label for="password">Senha *</label><br>
            <input type="password" class="form-control width-grande" ng-required="true" ng-model="setting.email_password" ng-init="setting.email_password={{$setting->email_password}}"><br>


            <br>

        </div>
        <div role="tabpanel" class="tab-pane" id="analytics">
            <br>
            {!! Form::label('analytics_tipo', 'Analytics tipo') !!}<br>
            {!! Form::select('analytics_tipo',
                    array(
                        '0' => 'Desativar',
                        '1' => 'Piwik',
                        '2' => 'Google Analytics',
                    ),
            null, ['class'=>"form-control width-medio <% validar(setting.analytics_tipo) %>", 'ng-model'=>'setting.analytics_tipo', 'init-model'=>'setting.analytics_tipo', 'placeholder' => '']) !!}<br>


            {!! Form::label('analytics_id', 'Analytics ID') !!}<br>
            {!! Form::text('analytics_id', null, ['class'=>"form-control width-grande <% validar(setting.analytics_id) %>", 'ng-model'=>'setting.analytics_id', 'init-model'=>'setting.analytics_id']) !!}<br>

            {!! Form::label('analytics_url', 'Analytics Url') !!}<br>
            {!! Form::text('analytics_url', null, ['class'=>"form-control width-grande <% validar(setting.analytics_url) %>", 'ng-model'=>'setting.analytics_url', 'init-model'=>'setting.analytics_url']) !!}<br>
            
        </div>
    </div>

</div>

{{--É NECESSÁRIO RODAR O COMANDO composer require illuminate/html E ALTERAR ACRESCENTAR LINHA NO ARQUIVO config/app.php--}}







