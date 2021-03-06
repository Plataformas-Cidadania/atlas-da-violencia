<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class PontosController extends Controller
{

    private $territorios = [
        1 => ['nome' => "País", 'tabela' => "spat.ed_territorios_paises", 'view' => ""],
        2 => ['nome' => "Região", 'tabela' => "spat.ed_territorios_regioes", 'view' => "mvw_series_points_by_region"],
        3 => ['nome' => "UF", 'tabela' => "spat.ed_territorios_uf", 'view' => "mvw_series_points_by_uf"],
        4 => ['nome' => "Município", 'tabela' => "spat.ed_territorios_municipios", 'view' => ""],
    ];

    private $months = [null, 'Jan', 'Fev', 'Mar', 'Abr', 'Mai', 'Jun', 'Jul', 'Ago', 'Set', 'Out', 'Nov', 'Dez'];

    private $aliasFilter = ['a', 'b', 'c', 'd', 'e', 'f', 'g', 'h', 'i', 'j', 'k', 'l', 'm', 'n', 'o', 'p', 'q', 'r', 's', 't', 'u', 'v', 'w', 'x', 'y', 'z'];

    public function index($serie_id){

        $lang =  App::getLocale();

        $setting = \App\Setting::firstOrFail();

        $serie = \App\Serie::select('series.id','textos_series.titulo')
            ->join('textos_series', 'textos_series.serie_id', '=', 'series.id')
            ->where('textos_series.idioma_sigla', $lang)
            ->where('series.id', $serie_id)
            ->first();

        return view('pontos', ['setting' => $setting, 'serie' => $serie]);
    }

    public function filtrosSerie($serie_id){
        $filtros = \App\Filtro::select('filtros.id', 'filtros.titulo', 'filtros.slug')
            ->join('filtros_series', 'filtros_series.filtro_id', '=', 'filtros.id')
            ->where('filtros_series.serie_id', $serie_id)
            ->get();

        return $filtros;
    }

    public function valoresFiltros(Request $request){
        $valores = \App\ValorFiltro::select('id', 'titulo AS title')
            ->where('filtro_id', $request->filtro_id)
            ->where('titulo', 'ilike', "$request->search%")
            ->get();

        return $valores;
    }

    public function valoresMapa(Request $request){

        $min = '2017-01-15';
        $max = '2017-01-15';

        $regioes = DB::table('spat.ed_territorios_regioes')
            ->select(DB::raw("ST_X(spat.ed_territorios_regioes.edterritorios_centroide) as lng, ST_Y(spat.ed_territorios_regioes.edterritorios_centroide) as lat, spat.ed_territorios_regioes.edterritorios_sigla as sigla"))
            ->get();

        $ufs = DB::table('spat.ed_territorios_uf')
            ->select(DB::raw("ST_X(spat.ed_territorios_uf.edterritorios_centroide) as lng, ST_Y(spat.ed_territorios_uf.edterritorios_centroide) as lat, spat.ed_territorios_uf.edterritorios_sigla as sigla"))
            ->get();

        $valores = DB::table('valores_series')
            ->select(DB::raw("ST_X(spat.ed_territorios_municipios.edterritorios_centroide) as lng, ST_Y(spat.ed_territorios_municipios.edterritorios_centroide) as lat, spat.ed_territorios_regioes.edterritorios_sigla as sigla_regiao, spat.ed_territorios_uf.edterritorios_sigla as uf, spat.ed_territorios_municipios.edterritorios_nome, valores_series.valor"))
            ->join('spat.ed_territorios_municipios', 'valores_series.regiao_id', '=', 'spat.ed_territorios_municipios.edterritorios_codigo')
            ->join('spat.ed_territorios_uf', DB::Raw('CAST(spat.ed_territorios_municipios.edterritorios_sigla AS INTEGER)'), '=', 'spat.ed_territorios_uf.edterritorios_codigo')
            ->join('spat.ed_uf', 'spat.ed_territorios_uf.edterritorios_codigo', '=', 'spat.ed_uf.eduf_cd_uf')
            ->join('spat.territorio', 'spat.ed_uf.edre_cd_regiao', '=', 'spat.territorio.terregiao')
            ->join('spat.ed_territorios_regioes', 'spat.territorio.terid', '=', 'spat.ed_territorios_regioes.edterritorios_terid')
            ->join('series', 'series.id', '=', 'valores_series.serie_id')
            ->where([
                ['valores_series.serie_id', $request->serie_id],
                ['valores_series.periodo', '>=', $min],
                ['valores_series.periodo', '<=', $max]
            ])
            ->get();

        return ['regioes' => $regioes, 'valores' => $valores, 'ufs' => $ufs];

    }

    public function totalPorTerritorio(Request $request){
        $start = $request->start;
        $end = $request->end;
        $filters = $request->filters;
        $whereFilters = false;
        if(!empty($filters)){
            foreach($filters as $filter){
                if(array_key_exists('valores', $filter)){
                    $whereFilters = true;
                }
            }
        }

        //se houver filtros com valores ira preencher os outros com todos os valores dos mesmos
        if($whereFilters){
            foreach($filters as $index => $filter){
                if(!array_key_exists('valores', $filter)){
                    //$filter->valores = [];
                    $valores = \App\ValorFiltro::where('filtro_id', $filter['id'])->get()->toArray();
                    $filters[$index]['valores'] = $valores;
                    //log::info($filter['valores']);
                }
            }
        }

        $tipoTerritorioSelecionado = $request->tipoTerritorioSelecionado;

        $codigoTerritorioSelecionado = $request->codigoTerritorioSelecionado;
        $tabelaTerritorioSelecionado = $this->territorios[$request->tipoTerritorioSelecionado]['tabela'];

        //$viewTerritorioSelecionado = $this->territorios[$request->tipoTerritorioSelecionado]['view'];

        $tipoTerritorioAgrupamento = $request->tipoTerritorioAgrupamento;
        $tabelaTerritorioAgrupamento = $this->territorios[$request->tipoTerritorioAgrupamento]['tabela'];

        $viewTerritorioAgrupamento = $this->territorios[$request->tipoTerritorioAgrupamento]['view'];

        $codigoTerritorio = 'codigo_territorio_nivel_acima';
        if($tipoTerritorioAgrupamento == $tipoTerritorioSelecionado){
            $codigoTerritorio = 'edterritorios_codigo';
        }

        //Log::info($codigoTerritorioSelecionado);
        //Log::info($tabelaTerritorioSelecionado);
        //Log::info($tabelaTerritorioAgrupamento);
        //Log::info($viewTerritorioAgrupamento);

        $aliasFilter = $this->aliasFilter;

        DB::connection()->enableQueryLog();

       $valores = DB::table("$viewTerritorioAgrupamento")
            ->select(DB::raw("
            ST_X($viewTerritorioAgrupamento.centroide_territorio) as lng, 
            ST_Y($viewTerritorioAgrupamento.centroide_territorio) as lat,
            $viewTerritorioAgrupamento.edterritorios_sigla as sigla,
            $viewTerritorioAgrupamento.edterritorios_nome as nome,
            $viewTerritorioAgrupamento.edterritorios_codigo as codigo,
            '$tipoTerritorioAgrupamento' as tipo_territorio,
            COUNT(*) as total
            "))
            ->when($whereFilters, function($query) use ($filters, $aliasFilter){
                foreach ($filters as $index => $filter){
                    $slug = $filter['slug'];
                    $query->join("geovalores_valores_filtros as $slug", "$slug.geovalor_id", '=', 'geovalores.id');
                }
                return $query;
            })
            ->when($whereFilters, function($query) use ($filters, $aliasFilter){
                foreach ($filters as $index => $filter) {
                    $query->where(function($query) use ($filter, $aliasFilter, $index){
                        if(array_key_exists('valores', $filter)){
                            if(count($filter['valores']) > 0){
                                foreach ($filter['valores'] as $valor) {
                                    $slug = $filter['slug'];
                                    $query->orWhere("$slug.valor_filtro_id", $valor['id']);
                                }
                                return $query;
                            }
                        }
                    });

                }
                return $query;

            })
            ->where([
                ["$viewTerritorioAgrupamento.serie_id", $request->serie_id],
                ["$viewTerritorioAgrupamento.data_ponto", '>=', $start],
                ["$viewTerritorioAgrupamento.data_ponto", '<=', $end]
            ])
            ->when(!empty($codigoTerritorioSelecionado), function($query) use ($codigoTerritorio, $tabelaTerritorioSelecionado, $codigoTerritorioSelecionado, $viewTerritorioAgrupamento){
                return $query->whereIn("$viewTerritorioAgrupamento.$codigoTerritorio", $codigoTerritorioSelecionado);
            })            
            ->groupBy(DB::Raw("
            ST_X($viewTerritorioAgrupamento.centroide_territorio), 
            ST_Y($viewTerritorioAgrupamento.centroide_territorio),
            $viewTerritorioAgrupamento.edterritorios_sigla,
            $viewTerritorioAgrupamento.edterritorios_nome,
            $viewTerritorioAgrupamento.edterritorios_codigo
            "))
            ->get();

        /*$valores2 = DB::table("geovalores")
            ->select(DB::raw("
            ST_X(agrupamento.edterritorios_centroide) as lng, 
            ST_Y(agrupamento.edterritorios_centroide) as lat,
            agrupamento.edterritorios_sigla as sigla,
            agrupamento.edterritorios_nome as nome,
            agrupamento.edterritorios_codigo as codigo,
            '$tipoTerritorioAgrupamento' as tipo_territorio,
            COUNT(DISTINCT geovalores.id) as total
            "))
            ->join('series', 'series.id', '=', 'geovalores.serie_id')
            ->join("$tabelaTerritorioAgrupamento as agrupamento", DB::Raw("ST_Contains(agrupamento.edterritorios_geometry, geovalores.ponto)"), '=', DB::Raw("true"))
            ->join("$tabelaTerritorioSelecionado as selecionado", DB::Raw("ST_Contains(selecionado.edterritorios_geometry, agrupamento.edterritorios_centroide)"), '=', DB::Raw("true"))
            ->leftJoin('geovalores_valores_filtros', 'geovalores_valores_filtros.geovalor_id', '=', 'geovalores.id')//trocar serie_id por geovalor_id
            ->when($whereFilters, function($query) use ($filters, $aliasFilter){
                foreach ($filters as $index => $filter){
                    //$query->join("geovalores_valores_filtros as $aliasFilter[$index]", "$aliasFilter[$index].geovalor_id", '=', 'geovalores.id');
                    $slug = $filter['slug'];
                    $query->join("geovalores_valores_filtros as $slug", "$slug.geovalor_id", '=', 'geovalores.id');
                }
                return $query;
            })
            ->when($whereFilters, function($query) use ($filters, $aliasFilter){
                foreach ($filters as $index => $filter) {
                    $query->where(function($query) use ($filter, $aliasFilter, $index){
                        if(array_key_exists('valores', $filter)){
                            if(count($filter['valores']) > 0){
                                foreach ($filter['valores'] as $valor) {
                                    //Log::info([$valor]);
                                    //$query->orWhere("$aliasFilter[$index].valor_filtro_id", $valor['id']);
                                    $slug = $filter['slug'];
                                    $query->orWhere("$slug.valor_filtro_id", $valor['id']);
                                }
                                return $query;
                            }
                        }
                    });

                }
                return $query;

            })
            ->where([
                ['geovalores.serie_id', $request->serie_id],
                ['geovalores.data', '>=', $start],
                ['geovalores.data', '<=', $end]
            ])
            ->when(!empty($codigoTerritorioSelecionado), function($query) use ($tabelaTerritorioSelecionado, $codigoTerritorioSelecionado){
                return $query->whereIn("selecionado.edterritorios_codigo", $codigoTerritorioSelecionado);
            })            
            ->groupBy(DB::Raw("
            ST_X(agrupamento.edterritorios_centroide),
            ST_Y(agrupamento.edterritorios_centroide),
            agrupamento.edterritorios_sigla,
            agrupamento.edterritorios_nome,
            agrupamento.edterritorios_codigo
            "))
            ->get();*/

        //Log::info($filters);
        //Log::info("========================================================");
        //Log::info(DB::getQueryLog());

        return ['valores' => $valores];
    }

    public function pontosPorTerritorio(Request $request){


        $start = $request->start;
        $end = $request->end;
        $filters = $request->filters;
        $whereFilters = false;
        if(!empty($filters)){
            foreach($filters as $filter){
                if(array_key_exists('valores', $filter)){
                    $whereFilters = true;
                }
            }
        }

        //se houver filtros com valores ira preencher os outros com todos os valores dos mesmos
        if($whereFilters){
            foreach($filters as $index => $filter){
                if(!array_key_exists('valores', $filter)){
                    //$filter->valores = [];
                    $valores = \App\ValorFiltro::where('filtro_id', $filter['id'])->get()->toArray();
                    $filters[$index]['valores'] = $valores;
                    //log::info($filter['valores']);
                }
            }
        }


        $paginate = $request->paginate;

        $codigoTerritorioSelecionado = $request->codigoTerritorioSelecionado;
        $tabelaTerritorioSelecionado = $this->territorios[$request->tipoTerritorioSelecionado]['tabela'];


        //Log::info($codigoTerritorioSelecionado);
        //Log::info($tabelaTerritorioSelecionado);
        //Log::info($tabelaTerritorioAgrupamento);

        DB::connection()->enableQueryLog();

        $aliasFilter = $this->aliasFilter;

        //Para trazer os filtros com seus respectivos valores
        $allFilters = \App\Filtro::select('filtros.id', 'filtros.titulo', 'filtros.slug')
            ->join('filtros_series', 'filtros_series.filtro_id', '=', 'filtros.id')
            ->where('filtros_series.serie_id', $request->serie_id)
            ->get();
        $selectFilters = "";
        $slugs = [];
        foreach($allFilters as $index => $filter){
            array_push($slugs, $filter->slug);
            $slug_geovalor_valor_filtro = $filter->slug;
            $slug_valor_filtro = $filter->slug.'_v';
            $selectFilters .= ", $slug_valor_filtro.titulo as $slug_geovalor_valor_filtro";
        }

        $valores = DB::table("geovalores")
            ->select(DB::raw("
            geovalores.id, 
            ST_X(geovalores.ponto) as lng, 
            ST_Y(geovalores.ponto) as lat,
            geovalores.endereco,             
            geovalores.data, 
            geovalores.hora, 
            filtros.slug as filtro,
            valores_filtros.titulo as valor_filtro                        
            "))
            ->distinct()
            ->join('series', 'series.id', '=', 'geovalores.serie_id')
            ->join($tabelaTerritorioSelecionado, DB::Raw("ST_Contains($tabelaTerritorioSelecionado.edterritorios_geometry, geovalores.ponto)"), '=', DB::Raw("true"))
            ->leftJoin('geovalores_valores_filtros', 'geovalores_valores_filtros.geovalor_id', '=', 'geovalores.id')
            ->leftJoin('valores_filtros', 'valores_filtros.id', '=', 'geovalores_valores_filtros.valor_filtro_id')
            ->leftJoin('filtros', 'filtros.id', '=', 'valores_filtros.filtro_id')
            ->when($whereFilters, function($query) use ($filters, $aliasFilter){
                foreach ($filters as $index => $filter){
                    //$query->join("geovalores_valores_filtros as $aliasFilter[$index]", "$aliasFilter[$index].geovalor_id", '=', 'geovalores.id');
                    $slug = $filter['slug'];
                    $query->join("geovalores_valores_filtros as $slug", "$slug.geovalor_id", '=', 'geovalores.id');
                }
                return $query;
            })
            ->when($whereFilters, function($query) use ($filters, $aliasFilter){
                foreach ($filters as $index => $filter) {
                    $query->where(function($query) use ($filter, $aliasFilter, $index){
                        if(array_key_exists('valores', $filter)){
                            if(count($filter['valores']) > 0){
                                foreach ($filter['valores'] as $valor) {
                                    //Log::info([$valor]);
                                    //$query->orWhere("$aliasFilter[$index].valor_filtro_id", $valor['id']);
                                    $slug_geovalor_valor_filtro = $filter['slug'];
                                    $query->orWhere("$slug_geovalor_valor_filtro.valor_filtro_id", $valor['id']);
                                }
                                return $query;
                            }
                        }
                    });

                }
                return $query;

            })
            ->where([
                ['geovalores.serie_id', $request->serie_id],
                ['geovalores.data', '>=', $start],
                ['geovalores.data', '<=', $end]
            ])
            ->when(!empty($codigoTerritorioSelecionado), function($query) use ($tabelaTerritorioSelecionado, $codigoTerritorioSelecionado){
                return $query->whereIn($tabelaTerritorioSelecionado.".edterritorios_codigo", $codigoTerritorioSelecionado);
            })
            ->orderBy('geovalores.id');

        /*$valores = DB::table("geovalores")
            ->select(DB::raw("
            geovalores.id,
            ST_X(geovalores.ponto) as lng,
            ST_Y(geovalores.ponto) as lat,
            geovalores.endereco,
            geovalores.data,
            geovalores.hora,
            filtros.slug as filtro,
            valores_filtros.titulo as valor_filtro
            "))
            ->distinct()
            ->join('series', 'series.id', '=', 'geovalores.serie_id')
            ->join($tabelaTerritorioSelecionado, DB::Raw("ST_Contains($tabelaTerritorioSelecionado.edterritorios_geometry, geovalores.ponto)"), '=', DB::Raw("true"))
            ->leftJoin('geovalores_valores_filtros', 'geovalores_valores_filtros.geovalor_id', '=', 'geovalores.id')
            ->leftJoin('valores_filtros', 'valores_filtros.id', '=', 'geovalores_valores_filtros.valor_filtro_id')
            ->leftJoin('filtros', 'filtros.id', '=', 'valores_filtros.filtro_id')
            ->when($whereFilters, function($query) use ($filters, $aliasFilter){
                foreach ($filters as $index => $filter){
                    //$query->join("geovalores_valores_filtros as $aliasFilter[$index]", "$aliasFilter[$index].geovalor_id", '=', 'geovalores.id');
                    $slug = $filter['slug'];
                    $query->join("geovalores_valores_filtros as $slug", "$slug.geovalor_id", '=', 'geovalores.id');
                }
                return $query;
            })
            ->when($whereFilters, function($query) use ($filters, $aliasFilter){
                foreach ($filters as $index => $filter) {
                    $query->where(function($query) use ($filter, $aliasFilter, $index){
                        if(array_key_exists('valores', $filter)){
                            if(count($filter['valores']) > 0){
                                foreach ($filter['valores'] as $valor) {
                                    //Log::info([$valor]);
                                    //$query->orWhere("$aliasFilter[$index].valor_filtro_id", $valor['id']);
                                    $slug_geovalor_valor_filtro = $filter['slug'];
                                    $query->orWhere("$slug_geovalor_valor_filtro.valor_filtro_id", $valor['id']);
                                }
                                return $query;
                            }
                        }
                    });

                }
                return $query;

            })
            ->where([
                ['geovalores.serie_id', $request->serie_id],
                ['geovalores.data', '>=', $start],
                ['geovalores.data', '<=', $end]
            ])
            ->when(!empty($codigoTerritorioSelecionado), function($query) use ($tabelaTerritorioSelecionado, $codigoTerritorioSelecionado){
                return $query->whereIn($tabelaTerritorioSelecionado.".edterritorios_codigo", $codigoTerritorioSelecionado);
            })
            ->orderBy('geovalores.id');*/

            if(!$paginate){
                $valores = $valores->get();
                $last_id = $valores[0]->id;
                $vals = $valores;
            }

            if($paginate){
                $valores = $valores->paginate(30);
                //Log::info($valores->items());
                $vals = $valores->items();
            }

            //Pega os filtros de cada registro de um mesmo id e cria um registro do id com todos os filtros
            if(!empty($vals)){

                $last_id = $vals[0]->id;
                $array_valores = [];
                $novo_valor = new \stdClass();
                //Log::info($vals);
                foreach($vals as $index => $valor){

                    if($last_id != $valor->id){
                        array_push($array_valores, $novo_valor);
                        $novo_valor = new \stdClass();
                        $last_id = $valor->id;
                    }

                    $novo_valor->data = $valor->data;
                    $novo_valor->endereco = $valor->endereco;
                    $novo_valor->hora = $valor->hora;
                    $novo_valor->id = $valor->id;
                    $novo_valor->lat = $valor->lat;
                    $novo_valor->lng = $valor->lng;
                    $novo_valor->{$valor->filtro} = $valor->valor_filtro;

                    if($index == count($vals)-1){
                        array_push($array_valores, $novo_valor);
                    }

                }
                //Log::info($array_valores);

                if($paginate) {
                    //recria os items paginados com os novos valores
                    $valores = new \Illuminate\Pagination\LengthAwarePaginator(
                        $array_valores,
                        $valores->total(),
                        $valores->perPage(),
                        $valores->currentPage(), [
                            'path' => \Request::url(),
                            'query' => [
                                'page' => $valores->currentPage()
                            ]
                        ]
                    );
                }else{
                    //apenas substitui os valores
                    $valores = $array_valores;
                }

            }





            $icones = DB::table('filtros')
                ->select('filtros.titulo', 'valores_filtros.titulo', 'valores_filtros.imagem', 'geovalores.id')
                ->join('valores_filtros', 'valores_filtros.filtro_id', '=', 'filtros.id')
                ->join('geovalores_valores_filtros', 'geovalores_valores_filtros.valor_filtro_id', '=', 'valores_filtros.id')
                ->join('geovalores', 'geovalores_valores_filtros.geovalor_id', '=', 'geovalores.id')
                ->where([
                    ['geovalores.serie_id', $request->serie_id],
                    ['geovalores.data', '>=', $start],
                    ['geovalores.data', '<=', $end]
                ])
                ->when(!empty($filters), function($query1) use ($filters){
                    return $query1->where(function($query) use ($filters){
                        foreach ($filters as $filter) {
                            if(array_key_exists('valores', $filter)){
                                if(count($filter['valores']) > 0){
                                    foreach ($filter['valores'] as $valor) {
                                        $query->orWhere('geovalores_valores_filtros.valor_filtro_id', $valor['id']);
                                    }
                                }
                            }
                        }

                        return $query;
                    });

                })
                ->get();






            /*foreach ($valores as $valor) {
                //Log::info([$valor]);
                $valor->dados = [];
                foreach ($icones as $icone) {
                    if($icone->id == $valor->id){
                        array_push($valor->dados, $icone);
                    }
                }
            }*/


            /*Log::info(DB::getQueryLog());
           //Log::info(">>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>");
           //Log::info(">>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>");
           //Log::info([$icones]);
           //Log::info("<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<");
           //Log::info("<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<");*/




        //Log::info($filters);
        //Log::info(DB::getQueryLog());



        return ['valores' => $valores, 'filtros' => $allFilters];
    }

    public function pontosPorPais(Request $request){


        $start = $request->start;
        $end = $request->end;
        $pais = $request->pais;
        $type = $request->type;


        //Log::info($codigoTerritorioSelecionado);
        //Log::info($tabelaTerritorioSelecionado);
        //Log::info($tabelaTerritorioAgrupamento);

        $valores = DB::table("geovalores")
            ->select(DB::raw("
            ST_X(geovalores.ponto) as lng, 
            ST_Y(geovalores.ponto) as lat,
            geovalores.endereco, 
            geovalores.tipo, 
            geovalores.tipo_acidente, 
            geovalores.sexo, 
            geovalores.turno, 
            geovalores.data, 
            geovalores.hora, 
            geovalores.faixa_etaria
            "))
            ->join('series', 'series.id', '=', 'geovalores.serie_id')
            //->join('spat.ed_territorios_paises', DB::Raw("ST_Contains(spat.ed_territorios_paises.edterritorios_geometry, geovalores.ponto)"), '=', DB::Raw("true"))
            ->where([
                ['geovalores.serie_id', $request->serie_id],
                ['geovalores.data', '>=', $start],
                ['geovalores.data', '<=', $end]
                //['spat.ed_territorios_paises.edterritorios_sigla', $pais]
            ])
            ->when($type > 0, function($query) use ($type){
                return $query->where('geovalores.tipo', $type);
            })
            ->get();

        return ['valores' => $valores];
    }


    public function valoresTerritorio(Request $request){

        $min = '1996-01-01';
        $max = '1996-01-01';

        $regioes = DB::table('spat.ed_territorios_regioes')
            ->select(DB::raw("ST_X(spat.ed_territorios_regioes.edterritorios_centroide) as lng, ST_Y(spat.ed_territorios_regioes.edterritorios_centroide) as lat, spat.ed_territorios_regioes.edterritorios_sigla as sigla"))
            ->get();

        $ufs = DB::table('spat.ed_territorios_uf')
            ->select(DB::raw("ST_X(spat.ed_territorios_uf.edterritorios_centroide) as lng, ST_Y(spat.ed_territorios_uf.edterritorios_centroide) as lat, spat.ed_territorios_uf.edterritorios_sigla as sigla"))
            ->get();

        $valores = DB::table('valores_series')
            ->select(DB::raw("ST_X(spat.ed_territorios_municipios.edterritorios_centroide) as lng, ST_Y(spat.ed_territorios_municipios.edterritorios_centroide) as lat, spat.ed_territorios_regioes.edterritorios_sigla as sigla_regiao, spat.ed_territorios_uf.edterritorios_sigla as uf, spat.ed_territorios_municipios.edterritorios_nome, valores_series.valor"))
            ->join('spat.ed_territorios_municipios', 'valores_series.regiao_id', '=', 'spat.ed_territorios_municipios.edterritorios_codigo')
            ->join('spat.ed_territorios_uf', DB::Raw('CAST(spat.ed_territorios_municipios.edterritorios_sigla AS INTEGER)'), '=', 'spat.ed_territorios_uf.edterritorios_codigo')
            ->join('spat.ed_uf', 'spat.ed_territorios_uf.edterritorios_codigo', '=', 'spat.ed_uf.eduf_cd_uf')
            ->join('spat.territorio', 'spat.ed_uf.edre_cd_regiao', '=', 'spat.territorio.terregiao')
            ->join('spat.ed_territorios_regioes', 'spat.territorio.terid', '=', 'spat.ed_territorios_regioes.edterritorios_terid')
            ->join('series', 'series.id', '=', 'valores_series.serie_id')
            ->where([
                ['valores_series.serie_id', $request->serie_id],
                ['valores_series.periodo', '>=', $min],
                ['valores_series.periodo', '<=', $max]
            ])
            ->get();

        return ['regioes' => $regioes, 'valores' => $valores, 'ufs' => $ufs];

    }


    public function types(Request $request){
        $types = [
            ['id' => '0', 'title' => 'Não Informado', 'icon' => 'outros.png'],
            ['id' => '1', 'title' => 'Automóvel', 'icon' => 'automovel.png'],
            ['id' => '2', 'title' => 'Motocicleta', 'icon' => 'motocicleta.png'],
            ['id' => '3', 'title' => 'Pedestre', 'icon' => 'pedestre.png'],
            ['id' => '4', 'title' => 'Ônibus', 'icon' => 'onibus.png'],
            ['id' => '5', 'title' => 'Caminhao', 'icon' => 'caminhao.png'],
            ['id' => '6', 'title' => 'Bicicleta', 'icon' => 'bicicleta.png'],
            ['id' => '7', 'title' => 'Outros', 'icon' => 'outros.png'],

        ];

        if(!empty($request->search)){
            $foundTypes = [];

            foreach($types as $type){
                if(stripos($type['title'], $request->search) !== false){
                    array_push($foundTypes, $type);
                }
            }

            return $foundTypes;
        }


        return $types;
    }

    public function typesAccident(){
        $types = [
            ['id' => '0', 'title' => 'Não Informado'],
            ['id' => '1', 'title' => 'Atropelamento'],
        ];

        return $types;
    }

    public function genders(){
        $types = [
            ['id' => '0', 'title' => 'Não Informado'],
            ['id' => '1', 'title' => 'Masculino'],
            ['id' => '2', 'title' => 'Feminino'],
        ];

        return $types;
    }

    public function regions(Request $request){

        $tipo_territorio = !empty($request->tipo_territorio) ? $request->tipo_territorio : 2;//2 - Regiao


        $regions = DB::table('todos_territorios')
            ->select('edterritorios_codigo as id', 'edterritorios_nome as title', 'sigla', 'tipo_territorio')
            //->where('edterritorios_nome', 'ilike', "$request->search%")
            ->when(!empty($tipo_territorio), function($query) use ($tipo_territorio){
                return $query->where('tipo_territorio', $tipo_territorio);
            })
            ->get();

        return $regions;
    }

    public function defaultRegions(Request $request){

        $tipo_territorio = $request->tipo_territorio;

        $regions = DB::table('todos_territorios')
            ->select('edterritorios_codigo as id', 'edterritorios_nome as title', 'tipo_territorio')
            ->when(!empty($tipo_territorio), function($query) use ($tipo_territorio){
                return $query->where('tipo_territorio', $tipo_territorio);
            })
            ->whereIn('edterritorios_codigo', $request->ids)
            ->get();

        return $regions;
    }

    public function years(Request $request){

        $id = $request->id;

        $years = [];

        $rows = DB::table('geovalores')
            ->select(DB::Raw('extract(year from data) as year'))
            ->where('serie_id', $id)
            ->distinct()
            ->orderby('year')
            ->get();


        foreach($rows as $row){
            array_push($years, $row->year);
        }

        return $years;
    }

    public function months(Request $request){

        $id = $request->id;
        $year = $request->year;

        //return $year;

        DB::enableQueryLog();

        $months = [];

        $rows = DB::table('geovalores')
            ->select(DB::Raw('extract(month from data) as month'))
            ->where(DB::Raw('extract(year from data)'), $year)
            ->where('serie_id', $id)
            ->distinct()
            ->get();

       //Log::info(DB::getQueryLog());

       //Log::info($rows);

        $rows = collect($rows)->sortBy('month')->toArray();

        //return $rows;

       //Log::info($rows);


        foreach($rows as $row){
            array_push($months, $this->months[$row->month]);
        }

       //Log::info($months);


        return $months;
    }

    public function valuesForTypes(Request $request){

        $serie_id = $request->id;
        $start = $request->start;
        $end = $request->end;

        $codigoTerritorioSelecionado = $request->codigoTerritorioSelecionado;
        $tabelaTerritorioSelecionado = $this->territorios[$request->tipoTerritorioSelecionado]['tabela'];

        $valores = DB::table('geovalores')
            ->select(DB::Raw('geovalores.tipo as type, count(geovalores.tipo) as value'))
            ->join('series', 'series.id', '=', 'geovalores.serie_id')
            ->join($tabelaTerritorioSelecionado, DB::Raw("ST_Contains($tabelaTerritorioSelecionado.edterritorios_geometry, geovalores.ponto)"), '=', DB::Raw("true"))
            ->where([
                ['geovalores.serie_id', $serie_id],
                ['geovalores.data', '>=', $start],
                ['geovalores.data', '<=', $end]
            ])
            ->when(!empty($codigoTerritorioSelecionado), function($query) use ($tabelaTerritorioSelecionado, $codigoTerritorioSelecionado){
                return $query->whereIn("$tabelaTerritorioSelecionado.edterritorios_codigo", $codigoTerritorioSelecionado);
            })
            ->groupBy('geovalores.tipo')
            ->get();

        return $valores;
    }

    public function valuesForGender(Request $request){

        $serie_id = $request->id;
        $start = $request->start;
        $end = $request->end;

        $codigoTerritorioSelecionado = $request->codigoTerritorioSelecionado;
        $tabelaTerritorioSelecionado = $this->territorios[$request->tipoTerritorioSelecionado]['tabela'];

        $valores = DB::table('geovalores')
            ->select(DB::Raw('geovalores.sexo as type, count(geovalores.sexo) as value'))
            ->join('series', 'series.id', '=', 'geovalores.serie_id')
            ->join($tabelaTerritorioSelecionado, DB::Raw("ST_Contains($tabelaTerritorioSelecionado.edterritorios_geometry, geovalores.ponto)"), '=', DB::Raw("true"))
            ->where([
                ['geovalores.serie_id', $serie_id],
                ['geovalores.data', '>=', $start],
                ['geovalores.data', '<=', $end]
            ])
            ->when(!empty($codigoTerritorioSelecionado), function($query) use ($tabelaTerritorioSelecionado, $codigoTerritorioSelecionado){
                return $query->whereIn("$tabelaTerritorioSelecionado.edterritorios_codigo", $codigoTerritorioSelecionado);
            })
            ->groupBy('geovalores.sexo')
            ->get();

        return $valores;
    }

    public function valuesForRegions(Request $request){

        $serie_id = $request->id;
        $start = $request->start;
        $end = $request->end;
        $types = $request->types;
        $typesAccident = $request->typesAccident;
        $genders = $request->genders;


        $codigoTerritorioSelecionado = $request->codigoTerritorioSelecionado;
        $tabelaTerritorioSelecionado = $this->territorios[$request->tipoTerritorioSelecionado]['tabela'];

        $tipoTerritorioAgrupamento = $request->tipoTerritorioAgrupamento;
        $tabelaTerritorioAgrupamento = $this->territorios[$request->tipoTerritorioAgrupamento]['tabela'];

        //Log::info($codigoTerritorioSelecionado);
        //Log::info($tabelaTerritorioSelecionado);
        //Log::info($tabelaTerritorioAgrupamento);

        $valores = DB::table("geovalores")
            ->select(DB::Raw("
            agrupamento.edterritorios_sigla as type,
            agrupamento.edterritorios_nome as nome,
            agrupamento.edterritorios_codigo as codigo,
            '$tipoTerritorioAgrupamento' as tipo_territorio,
            count(geovalores.id) as value
            "))
            ->join('series', 'series.id', '=', 'geovalores.serie_id')
            ->join("$tabelaTerritorioAgrupamento as agrupamento", DB::Raw("ST_Contains(agrupamento.edterritorios_geometry, geovalores.ponto)"), '=', DB::Raw("true"))
            ->join("$tabelaTerritorioSelecionado as selecionado", DB::Raw("ST_Contains(selecionado.edterritorios_geometry, agrupamento.edterritorios_centroide)"), '=', DB::Raw("true"))
            ->where([
                ['geovalores.serie_id', $serie_id],
                ['geovalores.data', '>=', $start],
                ['geovalores.data', '<=', $end]
            ])
            ->when(!empty($codigoTerritorioSelecionado), function($query) use ($tabelaTerritorioSelecionado, $codigoTerritorioSelecionado){
                return $query->whereIn("selecionado.edterritorios_codigo", $codigoTerritorioSelecionado);
            })
            ->when($types != null, function($query) use ($types){
                return $query->whereIn('geovalores.tipo', $types);
            })
            ->when($typesAccident != null, function($query) use ($typesAccident){
                return $query->whereIn('geovalores.tipo_acidente', $typesAccident);
            })
            ->when($genders != null, function($query) use ($genders){
                return $query->whereIn('geovalores.sexo', $genders);
            })
            ->groupBy(DB::Raw("
            agrupamento.edterritorios_sigla,
            agrupamento.edterritorios_nome,
            agrupamento.edterritorios_codigo
            "))
            ->get();

        return $valores;
    }

    public function arraysTransito(Request $request){
        $types = $this->types($request);
        $typesAccident = $this->typesAccident();
        $genders = $this->genders();

        return ['types'=>$types, 'typesAccident'=>$typesAccident, 'genders'=>$genders];
    }

    public function valuesChartFilters(Request $request){

        $serie_id = $request->serie_id;
        $start = $request->start;
        $end = $request->end;
        $filters = $request->filters;
        $whereFilters = false;
        if(!empty($filters)){
            foreach($filters as $filter){
                if(array_key_exists('valores', $filter)){
                    $whereFilters = true;
                }
            }
        } 

        //se houver filtros com valores ira preencher os outros com todos os valores dos mesmos
        if($whereFilters){
            foreach($filters as $index => $filter){
                if(!array_key_exists('valores', $filter)){
                    //$filter->valores = [];
                    $valores = \App\ValorFiltro::where('filtro_id', $filter['id'])->get()->toArray();
                    $filters[$index]['valores'] = $valores;
                    //log::info($filter['valores']);
                }
            }
        }   

        //log::info($filters);
        //

        $aliasFilter = $this->aliasFilter;


        $filtersDB = \App\Filtro::select('filtros.*', DB::Raw('count(valores_filtros.filtro_id) as qtd'))
            ->join('valores_filtros', 'filtros.id', '=', 'valores_filtros.filtro_id')
            ->join('filtros_series', 'filtros.id', '=', 'filtros_series.filtro_id')
            ->where('serie_id', $request->serie_id)
            ->groupBy('valores_filtros.filtro_id', 'filtros.id')
            ->orderBy(DB::Raw('count(valores_filtros.filtro_id)'), 'desc')
            ->distinct()
            ->get();

        //DB::connection()->enableQueryLog();


        foreach($filtersDB as $filterDB){
                     

            $values = \App\GeoValor::select('valores_filtros.titulo', DB::Raw('count(valores_filtros.id) as value'))
                ->join('geovalores_valores_filtros', 'geovalores_valores_filtros.geovalor_id', '=', 'geovalores.id')
                ->when($whereFilters, function($query) use ($filters, $aliasFilter){
                    foreach ($filters as $index => $filter){
                        $query->join("geovalores_valores_filtros as $aliasFilter[$index]", "$aliasFilter[$index].geovalor_id", '=', 'geovalores.id');
                    }
                    return $query;
                })
                ->join('valores_filtros', 'valores_filtros.id', '=', 'geovalores_valores_filtros.valor_filtro_id')
                ->join('filtros', 'filtros.id', '=', 'valores_filtros.filtro_id')
                ->where('geovalores.serie_id', $serie_id)
                ->where('filtros.id', $filterDB->id)
                ->where([
                    ['geovalores.serie_id', $serie_id],
                    ['geovalores.data', '>=', $start],
                    ['geovalores.data', '<=', $end]
                ])
                /*->when($whereFilters, function($query) use ($filters){
                    return $query->whereIn('geovalores_valores_filtros.geovalor_id', $geovaloresArray);
                })*/
                ->when($whereFilters, function($query) use ($filters, $aliasFilter){
                    foreach ($filters as $index => $filter) {
                        $query->where(function($query) use ($filter, $aliasFilter, $index){
                            if(array_key_exists('valores', $filter)){
                                if(count($filter['valores']) > 0){
                                    foreach ($filter['valores'] as $valor) {
                                        //Log::info([$valor]);
                                        $query->orWhere("$aliasFilter[$index].valor_filtro_id", $valor['id']);
                                    }
                                    return $query;
                                }
                            }
                        });

                    }
                    return $query;

                })
                ->groupBy('valores_filtros.id')
                ->orderBy('valores_filtros.id')
                ->get();

            $filterDB->values = $values;
        }

        //Log::info(DB::getQueryLog());

        return $filtersDB;

    }

    public function gerarCsvExemplo(){
        $csv = "serie;lat;lon;titulo;endereco;data;hora;faixa_etaria;locomocao;sexo;turno"."<br>";

        for($i=0;$i<2000;$i++){
            $ramdom1 = rand(5, 30);
            $ramdom2 = rand(1234567, 7654321);
            $ramdom3 = rand(39, 60);
            $ramdom4 = rand(1234567, 7654321);
            $csv .= "10;-$ramdom1.".$ramdom2.";-$ramdom3.".$ramdom4.";aaaaaa;rua a;2019-01-01;13:16:10;17;1;8;27"."<br>";
        }

        for($i=0;$i<5500;$i++) {
            $ramdom1 = rand(5, 30);
            $ramdom2 = rand(1524786, 9853214);
            $ramdom3 = rand(39, 60);
            $ramdom4 = rand(1245037, 6478103);
            $csv .= "10;-$ramdom1." . $ramdom2 . ";-$ramdom3." . $ramdom4 . ";bbbbbb;rua a;2019-01-01;13:16:10;17;1;8;27" . "<br>";
        }

        for($i=0;$i<800;$i++) {
            $ramdom1 = rand(5, 30);
            $ramdom2 = rand(6478134, 1795432);
            $ramdom3 = rand(39, 60);
            $ramdom4 = rand(1392475, 3412547);
            $csv .= "10;-$ramdom1." . $ramdom2 . ";-$ramdom3." . $ramdom4 . ";cccccc;rua a;2019-01-01;13:16:10;17;1;8;27" . "<br>";

        }

        for($i=0;$i<200;$i++){
            $ramdom1 = rand(5, 30);
            $ramdom2 = rand(7123547, 2344736);
            $ramdom3 = rand(39, 60);
            $ramdom4 = rand(3458714, 9632147);
            $csv .= "10;-$ramdom1.".$ramdom2.";-$ramdom3.".$ramdom4.";ddddddd;rua a;2019-01-01;13:16:10;17;1;8;27"."<br>";

        }



        return $csv;

    }
}
