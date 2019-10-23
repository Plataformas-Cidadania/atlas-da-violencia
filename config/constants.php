<?php

return [
    'TIPOS_DADOS_SERIES' => [
        0 => 'Territórios',
        1 => 'Pontos',
        2 => 'Territórios e Pontos',
        3 => 'Arquivo',
        4 => 'Territórios e Arquivo',
        5 => 'Pontos e Arquivo',
        6 => 'Territorios, Pontos e Arquivo',
    ],
    'indicadores' => [
        ['id' => '1', 'title' => 'Quantidade', 'on' => false, 'enable' => false],
        ['id' => '2', 'title' => 'Taxa por 100 mil Habitantes', 'on' => false, 'enable' => false],
        ['id' => '3', 'title' => 'Proporção', 'on' => false, 'enable' => false]
    ],
    //As tabelas de ED_TERRITORIOS possuem uma coluna EDTERRITORIOS_TNIVID que define o tipo do território. Como no sistema
    //os tipos de territorios são definidos de uma forma e nessa tabela de outra, então essa constante serve para converter
    //o tipo de territorio informado pelo sistema para o tnivid desta coluna afim de
    /*'TIPO_TERRITORIO_TO_EDTERRITORIOS_TNIVID' => [
        1 => 98,//País
        2 => 1,//Região
        3 => 2,//UF
        4 => 5,//Município
    ],*/
    /*'abrangencias' => [
        ['id'=> 1, 'title'=> 'País..', 'plural'=> ' os Países', 'on'=>false, 'enable'=>false, 'listAll'=>1, 'height'=> '250px'],
        ['id'=> 2, 'title'=> 'Região', 'plural'=> 'as Regiões', 'on'=>false, 'enable'=>false, 'listAll'=>1, 'height'=> '250px'],
        ['id'=> 3, 'title'=> 'UF', 'plural'=> 'os Estados', 'on'=>false, 'enable'=>false, 'listAll'=>1, 'height'=> '400px'],
        ['id'=> 4, 'title'=> 'Município', 'plural'=> 'os Municípios', 'on'=>false, 'enable'=>false, 'listAll'=>0, 'height'=> '400px',
            'filter'=>[
                    ['id'=> 12, 'title'=> 'Acre'],
                    ['id'=> 27, 'title'=> 'Alagoas'],
                    ['id'=> 13, 'title'=> 'Amazonas'],
                    ['id'=> 16, 'title'=> 'Amapá'],
                    ['id'=> 29, 'title'=> 'Bahia'],
                    ['id'=> 23, 'title'=> 'Ceará'],
                    ['id'=> 53, 'title'=> 'Distrito Federal'],
                    ['id'=> 32, 'title'=> 'Espirito Santo'],
                    ['id'=> 52, 'title'=> 'Goiás'],
                    ['id'=> 21, 'title'=> 'Maranhão'],
                    ['id'=> 50, 'title'=> 'Mato Grosso do Sul'],
                    ['id'=> 51, 'title'=> 'Mato Grosso'],
                    ['id'=> 31, 'title'=> 'Minas Gerais'],
                    ['id'=> 15, 'title'=> 'Pará'],
                    ['id'=> 41, 'title'=> 'Paraná'],
                    ['id'=> 25, 'title'=> 'Paraíba'],
                    ['id'=> 26, 'title'=> 'Pernambuco'],
                    ['id'=> 22, 'title'=> 'Piauí'],
                    ['id'=> 33, 'title'=> 'Rio de Janeiro'],
                    ['id'=> 24, 'title'=> 'Rio Grande do Norte'],
                    ['id'=> 43, 'title'=> 'Rio Grande do Sul'],
                    ['id'=> 11, 'title'=> 'Rondônia'],
                    ['id'=> 14, 'title'=> 'Roraima'],
                    ['id'=> 42, 'title'=> 'Santa Catarina'],
                    ['id'=> 35, 'title'=> 'São Paulo'],
                    ['id'=> 28, 'title'=> 'Sergipe'],
                    ['id'=> 17, 'title'=> 'Tocantins'],
            ]
        ]
    ],*/
    //'PADRAO_ABRANGENCIA' => [3,2,1,4],//3 - UF | 4 - Municipios | 2 - Regiões | 1 - País | 7 - Territórios
    /*'PADRAO_TERRITORIOS' => [
        1 => [0],// 1076 (era 203) - Brasil
        2 => [0],//0 - Todos,
        3 => [0],//0 - Todos | colocar os códigos das ufs que deseja carregar por padrão
        4 => [0],//Código do estado. Desta forma irá pegar todos os munínicios do estado | 0 para todos
        7 => [0],//0 - Todos | Territórios do Piauí
    ],*/
    'FAVICONS_SIZES' => ['16x16', '32x32', '48x48', '64x64', '72x72', '96x96', '114x114', '114x114', '128x128', '144x144', '512x512'],
    'TYPES_PRESENTATION_ELEMENTS' => [
        '1' => 'Texto',
        '2' => 'Gráfico',
        '3' => 'Imagem',
        '4' => 'HTML',
    ],
    'CHART_TYPES_PRESENTATION_ELEMENTS' => [
        '1' => 'Barra',
        '2' => 'Linha',
    ],
];
