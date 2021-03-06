<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    protected $fillable = [
        'imagem', 'email', 'titulo', 'rodape', 'cep', 'endereco', 'numero', 'complemento', 'bairro', 'cidade', 'estado',
        'descricao_contato', 'telefone', 'telefone2', 'telefone3', 'facebook', 'youtube', 'pinterest', 'twitter', 'cor1', 'cor2', 'cor3', 'cor4', 'cor5',
        'serie_id', 'email_host', 'email_port', 'email_address', 'email_name', 'email_user', 'email_password',
        'consulta_por_temas', 'consulta_filtros_indicadores', 'qtd_temas_home', 'padrao_abrangencia', 'google', 'latitude', 'longitude',
        'posicao_mapa', 'posicao_tabela', 'posicao_grafico', 'posicao_taxa', 'posicao_metadados', 'pontos_default_regions', 'pontos_tipo_default_regions',
        'analytics_tipo', 'analytics_id', 'analytics_url', 'h1', 'h2', 'h3', 'video_home', 'carousel', 'links', 'dados_serie_home',
        'csv_serie_home', 'titulo_serie_home', 'cores_serie_home', 'dados_contato',
    ];
}
