<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Exporter;

class SerieController extends Controller
{

    private $cache;

    public function __construct(\Illuminate\Cache\Repository $cache){
        $this->indicadores = config('constants.indicadores');

        /*$this->abrangencias = config('constants.abrangencias');
        Log::info('abrangencias CONSTANTS');
        Log::info($this->abrangencias);*/

        $lang =  App::getLocale();

        $options = \DB::table('options_abrangencias')
            ->select(
                'options_abrangencias.id',
                'idiomas_options_abrangencias.title',
                'idiomas_options_abrangencias.plural',
                'options_abrangencias.on',
                'options_abrangencias.listAll',
                'options_abrangencias.height'
            )
            ->join('idiomas_options_abrangencias', 'idiomas_options_abrangencias.option_abrangencia_id', '=', 'options_abrangencias.id')
            ->get();

        foreach ($options as $option) {
            $filters = DB::table('filters_options_abrangencias')->where('option_abrangencia_id', $option->id)->get();
            if(count($filters) > 0){
                $option->filter = $filters;
            }
        }

        $this->abrangencias = json_decode(json_encode($options), true);


        $this->cache = $cache;

        $this->tabelas = [
            1 => 'spat.ed_territorios_paises',
            2 => 'spat.ed_territorios_regioes',
            3 => 'spat.ed_territorios_uf',
            4 => 'spat.ed_territorios_municipios',
            5 => 'spat.ed_territorios_microrregioes',
            6 => 'spat.ed_territorios_mesoregioes',
            7 => 'spat.ed_territorios_piaui_tds'
        ];
    }

    public function getIndicadoresSeries($serie_id){
        foreach($this->indicadores as $key => $indicador){
            //Log::info($indicador);
            /*$series = \App\Serie::where(function ($query) use ($serie_id) {
                $query->where('id', $serie_id)
                    ->orWhere('serie_id', $serie_id);
            })
            ->where('indicador', $indicador['id'])
            ->get();*/
            $series = \App\Serie::where('id', $serie_id)
                ->where('indicador', $indicador['id'])
            ->get();

            if(count($series) > 0){
                $this->indicadores[$key]['enable'] = true;
            }
        }
        return $this->indicadores;
    }

    public function getAbrangenciasSeries($serie_id){
        foreach($this->abrangencias as $key => $abrangencia){
            //Log::info($indicador);
            $series = \App\Serie::where(function ($query) use ($serie_id) {
                $query->where('id', $serie_id)
                    ->orWhere('serie_id', $serie_id);
            })
            ->where('abrangencia', $abrangencia['id'])
            ->get();

            if(count($series) > 0){
                $this->abrangencias[$key]['enable'] = true;
            }
        }
        return $this->abrangencias;
    }


    public function listar(){
        //$pages = DB::table('pages')->paginate(10);
       
        return view('serie.listar');
        //return view('page.listar', ['pages' => $pages]);
    }
    public function detalhar(/*$id*/){

        /*$page = new \App\Page;
        $page = $page->find($id);
        return view('page.detalhar', ['page' => $page]);*/

        return view('serie.detalhar');

    }

    public function filtro(){
        return view('serie.filtro');
    }

    public function filtros($id, $titulo){

        $lang =  App::getLocale();
        //$idioma = 'pt_BR';

        $serie = \App\Serie::select('series.*', 'textos_series.titulo as titulo')
            ->join('textos_series', 'series.id', '=', 'textos_series.serie_id')
            ->where('series.id', $id)
            ->where('textos_series.idioma_sigla', $lang)->first();

        //Log::info($serie);

        return view('serie.filtros', ['serie' => $serie, 'id' => $id]);
    }

    public function listarSeries(Request $request){

        $lang =  App::getLocale();

        $series = DB::table('series')
            ->select(DB::raw('series.*, periodicidades.titulo as periodicidade, min(valores_series.periodo) as min, max(valores_series.periodo) as max, textos_series.titulo as titulo'))
            ->join('valores_series', 'valores_series.serie_id', '=', 'series.id')
            ->join('periodicidades', 'series.periodicidade_id', '=', 'periodicidades.id')
            ->join('textos_series', 'series.id', '=', 'textos_series.serie_id')
            ->where('textos_series.titulo', 'ilike', "%$request->search%")
            ->where('textos_series.idioma_sigla', $lang)
            ->groupBy('series.id', 'periodicidades.titulo', 'textos_series.titulo')
            ->get();

        //Log::info($series);

        return $series;
    }

    public function listarSeriesRelacionadas(Request $request){

        $parameters = $request->parameters;


        $series = DB::table('series')
            ->select(DB::raw('series.*, periodicidades.titulo as periodicidade, min(valores_series.periodo) as min, max(valores_series.periodo) as max, valores_series.tipo_regiao'))
            ->join('valores_series', 'valores_series.serie_id', '=', 'series.id')
            ->join('periodicidades', 'series.periodicidade_id', '=', 'periodicidades.id')
	        ->where(function ($query) use ($parameters) {
                $query->where('series.id', $parameters['id'])
                      ->orWhere('series.serie_id', $parameters['id']);
            })
            ->where([                
                ['series.indicador', $parameters['indicador']],
                ['series.abrangencia', $parameters['abrangencia']]
            ])
            //->orWhere('series.serie_id', $parameters['id'])
            ->groupBy('series.id', 'valores_series.tipo_regiao', 'periodicidades.titulo')
            ->orderBy('series.titulo')
            ->get();

        return $series;
    }

//    public function dataSeries(Request $request){
//        $serie = \App\Serie::select('series.id', 'textos_series.*', 'periodicidades.titulo as periodicidade')
//            ->join('textos_series', 'textos_series.serie_id', '=', 'series.id')
//            ->join('periodicidades', 'periodicidades.id', '=', 'series.periodicidade_id')
//            ->where('series.id', $request->id)->first();
//
//        //$regions = explode(',', $request->regions);
//
//        return view('data-series', [
//            'id' => $request->id,
//            'series' => $serie,
//            'from' => $request->from,
//            'to' => $request->to,
//            'regions' => $request->regions,
//            'abrangencia' => $request->abrangencia,
//            /*'typeRegion' => $request->typeRegion,
//            'typeRegionSerie' => $request->typeRegionSerie*/
//        ]);
//    }

    public function dataSeries($serie_id){
        $lang =  App::getLocale();
        $setting = DB::table('settings')->orderBy('id', 'desc')->first();

        $serie = \App\Serie::select('series.id', 'textos_series.*', 'periodicidades.titulo as periodicidade', 'fontes.titulo as fonte', 'idiomas_unidades.titulo as unidade', 'unidades.tipo as tipo_unidade')
            ->join('textos_series', 'textos_series.serie_id', '=', 'series.id')
            ->join('periodicidades', 'periodicidades.id', '=', 'series.periodicidade_id')
            //->join('idiomas_periodicidades', 'idiomas_periodicidades.id', '=', 'periodicidades.id')
            ->join('fontes', 'fontes.id', '=', 'series.fonte_id')
            ->join('unidades', 'unidades.id', '=', 'series.unidade')
            ->join('idiomas_unidades', 'idiomas_unidades.unidade_id', '=', 'unidades.id')
            ->where('textos_series.idioma_sigla', $lang)
            //->where('idiomas_periodicidades.idioma_sigla', $lang)
            ->where('idiomas_unidades.idioma_sigla', $lang)
            ->where('series.id', $serie_id)->first();

        //$regions = explode(',', $request->regions);

        Log::info('SERIE_ID: '.$serie_id);

        Log::info('###SERIES##################################################');
        Log::info($serie);
        Log::info('###########################################################');

        //$abrangencias = Config::get('constants.PADRAO_ABRANGENCIA');
        $abrangenciasSettings = \App\Setting::find(1)->padrao_abrangencia;
        $abrangencias = explode(',', $abrangenciasSettings);

        Log::info('###PADRÃO ABRANGENCIAS#####################################');
        Log::info($abrangencias);
        Log::info('###########################################################');

        $indiceAbrangencia = 0;
        $abrangencia = $abrangencias[$indiceAbrangencia];

        Log::info('###ABRANGÊNCIA#############################################');
        Log::info($abrangencia);
        Log::info('###########################################################');


        //alem de pegar os valores de from e to também serve para verificar se existem valores nesta abrangência
        $fromTo = $this->fromToPeriodo($abrangencias, $indiceAbrangencia, $abrangencia, $serie_id);

        Log::info('###FROM TO#################################################');
        Log::info($fromTo);
        Log::info('###########################################################');

        //senão existe valores em nenhuma das abrangências pesquisadas.
        if($fromTo==0){
            $serie = null;
        }

        $from = $fromTo[0];
        $to = $fromTo[1];
        $abrangencia = $fromTo[2];

        $regions = $this->getRegions($abrangencia);

        $regions = implode(",", $regions);

        $abrangenciasOk = $this->verifyExistsRegions($abrangencias, $serie_id);

        return view('data-series', [
            'id' => $serie_id,
            'series' => $serie,
            'from' => $from,
            'to' => $to,
            'regions' => $regions,
            'abrangencia' => $abrangencia,
            'abrangenciasOk' => $abrangenciasOk,
            'abrangencias' => $this->abrangencias,
            'setting' => $setting,
        ]);
    }

    private function verifyExistsRegions($abrangencias, $serie_id){

        $abrangenciasOk = [];

        foreach ($abrangencias as $abrangencia){
            $qtd = DB::table('valores_series')
                ->where([
                    ['serie_id', $serie_id],
                    ['tipo_regiao', $abrangencia],
                ])->count();
            if($qtd > 0){
                array_push($abrangenciasOk, $abrangencia);
            }
        }
        return implode(',', $abrangenciasOk);
    }

    private function fromToPeriodo($abrangencias, $indiceAbrangencia, $abrangencia, $serie_id){
        $from = DB::table('valores_series')
            ->where([
                ['serie_id', $serie_id],
                ['tipo_regiao', $abrangencia],
            ])->min('periodo');

        $to = DB::table('valores_series')
            ->where([
                ['serie_id', $serie_id],
                ['tipo_regiao', $abrangencia],
            ])->max('periodo');

        if(!empty($from)){
            return [$from, $to, $abrangencia];
        }

        //verifica se existem valores nesta abrangência. Senão existirem irá tentar outra abrangência
        if(empty($from)){
            $indiceAbrangencia++;
            if(!array_key_exists($indiceAbrangencia, $abrangencias)){
                return 0;
            }
            $abrangencia = $abrangencias[$indiceAbrangencia];
            return $this->fromToPeriodo($abrangencias, $indiceAbrangencia, $abrangencia, $serie_id);
        }


    }

    private function getPadraoTerritorios(){

        $padraoTerritoriosDB = DB::table('padrao_territorios')->get();

        $padraoTerritorios = [];

        foreach ($padraoTerritoriosDB as $padraoTerritorioDB) {
            $padraoTerritorios[$padraoTerritorioDB->option_abrangencia_id] = explode(',', $padraoTerritorioDB->territorios);
        }

        return $padraoTerritorios;
    }

    private function getRegions($abrangencia){
        //$padraoTerritorios = Config::get('constants.PADRAO_TERRITORIOS');
        $padraoTerritorios = $this->getPadraoTerritorios();

        $regions = $padraoTerritorios[$abrangencia];

        //se a abrangência for de municipio então irá pegar os municipios de um determinado estado se o codigo nao for 0
        if($abrangencia==4){
            if($regions[0]==0){//pegar todos os municipios
                return $this->getAllRegions($abrangencia);
            }
            return $this->getMunicipios($regions[0]);
        }

        if($regions[0]==0){
            return $this->getAllRegions($abrangencia);
        }

        return $regions;
    }

    private function getMunicipios($codUF){
        $return = DB::table('spat.ed_territorios_municipios')
            ->select('edterritorios_codigo')
            ->where('edterritorios_sigla', $codUF)
            ->get();

        $regions = [];

        foreach ($return as $item) {
            array_push($regions,$item->edterritorios_codigo);
        }

        return $regions;
    }

    private function getAllRegions($abrangencia){
        $regionTable = $this->tabelas[$abrangencia];

        $return = DB::table($regionTable)->select('edterritorios_codigo')->get();

        $regions = [];

        foreach ($return as $item) {
            array_push($regions,$item->edterritorios_codigo);
        }

        return $regions;
    }

    function valoresRegiaoPrimeiroUltimoPeriodo($id, $min, $max, $regions, $abrangencia){
        //$typeRegionSerie: 1(regiao), 2(uf) 3(municipio)

        $cacheKeyMin = sha1('valores-regiao-'.$id.'-'.$min.'-'.str_replace(',', '', $regions).'-'.$abrangencia);
        $cacheKeyMax = sha1('valores-regiao-'.$id.'-'.$max.'-'.str_replace(',', '', $regions).'-'.$abrangencia);

        $regions = explode(',', $regions);

        //Log::info('valoresRegiaoPrimeiroUltimoPeriodo Min: '.$cacheKeyMin);
        //Log::info('valoresRegiaoPrimeiroUltimoPeriodo Max: '.$cacheKeyMax);

        $tabelas = [
            1 => 'spat.ed_territorios_paises',
            2 => 'spat.ed_territorios_regioes',
            3 => 'spat.ed_territorios_uf',
            4 => 'spat.ed_territorios_municipios',
            5 => 'spat.ed_territorios_microrregioes',
            6 => 'spat.ed_territorios_mesoregioes',
            7 => 'spat.ed_territorios_piaui_tds'
        ];


        $select_sigla = "$tabelas[$abrangencia].edterritorios_sigla";
        if($abrangencia == 4){
            $select_sigla = "$tabelas[$abrangencia].edterritorios_nome";
        }

        //DB::enableQueryLog();

        //exclui o cache. Utilizar apenas para testes.
        $this->cache->forget($cacheKeyMin);

        if(!$this->cache->has($cacheKeyMin)){
            $this->cache->put($cacheKeyMin, DB::table('valores_series')
                ->select(DB::raw("valores_series.valor as valor, $select_sigla as sigla, $tabelas[$abrangencia].edterritorios_nome as nome"))
                ->join($tabelas[$abrangencia], 'valores_series.regiao_id', '=', "$tabelas[$abrangencia].edterritorios_codigo")
                ->where([
                    ['valores_series.serie_id', $id],
                    ['valores_series.periodo', $min],
                    ['valores_series.tipo_regiao', $abrangencia]
                ])
                /*->when(!empty($regions), function($query) use ($regions){
                    return $query->whereIn('valores_series.regiao_id', $regions);
                })*/
                ->when($regions[0]!=0, function($query) use ($regions){
                    return $query->whereIn('valores_series.regiao_id', $regions);
                })
                /*->whereIn('valores_series.regiao_id', $regions)*/
                ->orderBy("$tabelas[$abrangencia].edterritorios_codigo")
                ->get(), 720);
        }

        $valoresMin = $this->cache->get($cacheKeyMin);

        /*$valoresMin = DB::table('valores_series')
            ->select(DB::raw("valores_series.valor as valor, $select_sigla as sigla, $tabelas[$abrangencia].edterritorios_nome as nome"))
            ->join($tabelas[$abrangencia], 'valores_series.regiao_id', '=', "$tabelas[$abrangencia].edterritorios_codigo")
            ->where([
                ['valores_series.serie_id', $id],
                ['valores_series.periodo', $min],
                ['valores_series.tipo_regiao', $abrangencia]
            ])
            ->whereIn('valores_series.regiao_id', $regions)
            ->orderBy("$tabelas[$abrangencia].edterritorios_sigla")
            ->get();*/

        /*Log::info("================valoresRegiaoPrimeiroUltimoPeriodo()==========================");
        Log::info("===========valores-regiao/id/min/max/regions/abrangencia======================");
        Log::info(DB::getQueryLog());
        Log::info("===============================================================================");
        Log::info("===============================================================================");*/

        //Log::info($valoresMin);

        //exclui o cache. Utilizar apenas para testes.
        $this->cache->forget($cacheKeyMax);

        if(!$this->cache->has($cacheKeyMax)){
            $this->cache->put($cacheKeyMax, DB::table('valores_series')
                ->select(DB::raw("valores_series.valor as valor, $select_sigla as sigla, $tabelas[$abrangencia].edterritorios_nome as nome"))
                ->join($tabelas[$abrangencia], 'valores_series.regiao_id', '=', "$tabelas[$abrangencia].edterritorios_codigo")
                ->where([
                    ['valores_series.serie_id', $id],
                    ['valores_series.periodo', $max],
                    ['valores_series.tipo_regiao', $abrangencia]
                ])
                ->when($regions[0]!=0, function($query) use ($regions){
                    return $query->whereIn('valores_series.regiao_id', $regions);
                })
                /*->whereIn('valores_series.regiao_id', $regions)*/
                ->orderBy("$tabelas[$abrangencia].edterritorios_codigo")
                ->get(), 720);
        }

        $valoresMax = $this->cache->get($cacheKeyMax);

        /*$valoresMax = DB::table('valores_series')
            ->select(DB::raw("valores_series.valor as valor, $select_sigla as sigla, $tabelas[$abrangencia].edterritorios_nome as nome"))
            ->join($tabelas[$abrangencia], 'valores_series.regiao_id', '=', "$tabelas[$abrangencia].edterritorios_codigo")
            ->where([
                ['valores_series.serie_id', $id],
                ['valores_series.periodo', $max],
                ['valores_series.tipo_regiao', $abrangencia]
            ])
            ->whereIn('valores_series.regiao_id', $regions)
            ->orderBy("$tabelas[$abrangencia].edterritorios_sigla")
            ->get();*/

        $valores = [
            'min' => [
                'periodo' => $min,
                'valores' => $valoresMin
            ],
            'max' => [
                'periodo' => $max,
                'valores' => $valoresMax
            ]
        ];

        return $valores;
    }


    /*function valoresPeriodoPorRegiao($id, $min, $max){
        $valores = DB::table('valores_series')
            ->select(DB::raw("sum(valores_series.valor) as total, valores_series.periodo"))
            ->join('ed_territorios_uf', 'valores_series.uf', '=', 'ed_territorios_uf.edterritorios_sigla')
            ->where([
                ['valores_series.serie_id', $id],
                ['valores_series.periodo', '>=', $min],
                ['valores_series.periodo', '<=', $max]
            ])
            ->groupBy('valores_series.periodo')
            ->orderBy('valores_series.periodo')
            ->get();

        return $valores;
    }*/

    function valoresPeriodoRegioesSelecionadas($id, $min, $max, $regions, $abrangencia){

        //Log::info('periodo-'.$id.'-'.$min.'-'.$max.'-'.str_replace(',', '', $regions).'-'.$abrangencia);
        $cacheKey = sha1('periodo-'.$id.'-'.$min.'-'.$max.'-'.str_replace(',', '', $regions).'-'.$abrangencia);

        //Log::info('valoresPeriodoRegioesSelecionadas: '.$cacheKey);

        $regions = explode(',', $regions);

        //Log::info($abrangencia);

        $tabelas = [
            1 => 'spat.ed_territorios_paises',
            2 => 'spat.ed_territorios_regioes',
            3 => 'spat.ed_territorios_uf',
            4 => 'spat.ed_territorios_municipios',
            5 => 'spat.ed_territorios_microrregioes',
            6 => 'spat.ed_territorios_mesoregioes',
            7 => 'spat.ed_territorios_piaui_tds'
        ];

        $select_sigla = "$tabelas[$abrangencia].edterritorios_sigla";
        if($abrangencia == 4){
            $select_sigla = "$tabelas[$abrangencia].edterritorios_nome";
        }

        //DB::enableQueryLog();

        //exclui o cache. Utilizar apenas para testes.
        $this->cache->forget($cacheKey);

        if(!$this->cache->has($cacheKey)){
            $this->cache->put($cacheKey, DB::table('valores_series')
                ->select(DB::raw("$select_sigla as sigla, valores_series.valor, valores_series.periodo"))
                ->join($tabelas[$abrangencia], 'valores_series.regiao_id', '=', "$tabelas[$abrangencia].edterritorios_codigo")
                ->where([
                    ['valores_series.serie_id', $id],
                    ['valores_series.periodo', '>=', $min],
                    ['valores_series.periodo', '<=', $max],
                    ['valores_series.tipo_regiao', $abrangencia]
                ])
                ->when($regions[0]!=0, function($query) use ($regions, $tabelas, $abrangencia){
                    return $query->whereIn("$tabelas[$abrangencia].edterritorios_codigo", $regions);
                })
                /*->whereIn("$tabelas[$abrangencia].edterritorios_codigo", $regions)*/
                ->orderBy(DB::Raw($tabelas[$abrangencia].'.edterritorios_codigo, valores_series.periodo'))
                ->get(), 720);
        }


        $rows = $this->cache->get($cacheKey);

        /*Log::info('==============valoresPeriodoRegioesSelecionadas==============');
        Log::info('=============================================================');
        Log::info(DB::getQueryLog());
        Log::info($rows);
        Log::info('=============================================================');
        Log::info('=============================================================');*/

        /*$rows = DB::table('valores_series')
            ->select(DB::raw("$select_sigla as sigla, valores_series.valor, valores_series.periodo"))
            ->join($tabelas[$abrangencia], 'valores_series.regiao_id', '=', "$tabelas[$abrangencia].edterritorios_codigo")
            ->where([
                ['valores_series.serie_id', $id],
                ['valores_series.periodo', '>=', $min],
                ['valores_series.periodo', '<=', $max],
                ['valores_series.tipo_regiao', '=', $abrangencia]
            ])
            ->whereIn("$tabelas[$abrangencia].edterritorios_codigo", $regions)
            ->orderBy('valores_series.periodo')
            ->get();*/

        $data = [];

        foreach($rows as $row){
            $data[$row->sigla][$row->periodo] = $row->valor;
        }

        return $data;
    }


    public function teste(){
        return view('serie.teste');
    }
    public function teste2(){
        return view('serie.ipea-selecao');
    }

    public function getOptionsAbrangencia(){
        //$options = Config::get('constants.abrangencias');

        $lang =  App::getLocale();

        $options = \DB::table('options_abrangencias')
            ->select(
                'options_abrangencias.id',
                'idiomas_options_abrangencias.title',
                'idiomas_options_abrangencias.plural',
                'options_abrangencias.on',
                'options_abrangencias.listAll',
                'options_abrangencias.height'
                )
            ->join('idiomas_options_abrangencias', 'idiomas_options_abrangencias.option_abrangencia_id', '=', 'options_abrangencias.id')
            ->get();

        foreach ($options as $option) {
            $filters = DB::table('filters_options_abrangencias')->where('option_abrangencia_id', $option->id)->get();
            if(count($filters) > 0){
                $option->filter = $filters;
            }
        }

        return $options;
    }

    public function territorios(Request $request){

        //return $request->all();
        //return $request->parameters['option'];

        if(!array_key_exists('option', $request->parameters)){
            return [];
        }

        $tipo = $request->parameters['option']['id'];
        $list = $request->parameters['option']['listAll'];
        $filter = $request->parameters['filter'];

        $conditions = null;
        if($request->conditions){
            $conditions = $request->conditions;
        }

        $search = $request->search;

        if(($list==0 && $filter==0) && empty($search)){
            return [];
        }

        //Log::info($tipo);

        $paises = ['Brasil'];

        $tabelas = [
            1 => 'spat.ed_territorios_paises',
            2 => 'spat.ed_territorios_regioes',
            3 => 'spat.ed_territorios_uf',
            4 => 'spat.ed_territorios_municipios',
            5 => 'spat.ed_territorios_microrregioes',
            6 => 'spat.ed_territorios_mesoregioes',
            7 => 'spat.ed_territorios_piaui_tds'
        ];

        $where = [['edterritorios_nome', 'ilike', "$search%"]];

        //utilizado para seleção em UF de um estado em particular
        if($request->itemDefault){
            array_push($where, ['edterritorios_codigo', $request->itemDefault]);
        }

        if($tipo == 4){
            if($filter){
                array_push($where, ['edterritorios_sigla', $filter]);
            }
        }

        DB::connection()->enableQueryLog();

        if($tipo > 1){
            $abrangencias = DB::table($tabelas[$tipo])
                ->select('edterritorios_codigo as id', 'edterritorios_nome as title', 'edterritorios_sigla as sigla')
                ->where($where)
                ->when($conditions, function($query) use ($conditions, $tabelas, $tipo){
                    return $query->join('valores_series', 'valores_series.regiao_id', '=', $tabelas[$tipo].'.edterritorios_codigo')
                        ->where('valores_series.serie_id', $conditions['id'])
                        ->where('valores_series.tipo_regiao', $tipo);
                })
                ->distinct()
                ->get();
            //Log::info(DB::getQueryLog());

            return $abrangencias;
        }

        //Caso o território seja do tipo país
        $abrangencias = DB::table($tabelas[$tipo])
            ->select('edterritorios_codigo as id', 'edterritorios_nome as title', 'edterritorios_sigla as sigla')
            ->where('edterritorios_nome', 'like', "%$search%")
            ->whereIn('edterritorios_nome', $paises)
            ->get();
        return $abrangencias;

    }

    function homeChart($id){
        $rows = DB::table('valores_series')
            ->select(DB::raw("spat.ed_territorios_paises.edterritorios_nome, valores_series.valor, valores_series.periodo"))
            ->join('spat.ed_territorios_paises', 'valores_series.regiao_id', '=', 'spat.ed_territorios_paises.edterritorios_codigo')
            ->where([
                ['valores_series.serie_id', $id],
                ['valores_series.tipo_regiao', 1]
            ])
            ->orderBy('valores_series.periodo')
            ->get();

        $data = [];

        foreach($rows as $row){
            $data[$row->periodo] = $row->valor;
        }

        return $data;
    }

    public function downloadDados(Request $request){


        $id = $request->id;
        $serie = $request->serie;
        $min = $request->from;
        $max = $request->to;
        $regions = $request->regions;
        $abrangencia = $request->abrangencia;

        $regions = explode(',', $regions);

        //Log::info($abrangencia);

        $tabelas = [
            1 => 'spat.ed_territorios_paises',
            2 => 'spat.ed_territorios_regioes',
            3 => 'spat.ed_territorios_uf',
            4 => 'spat.ed_territorios_municipios',
            5 => 'spat.ed_territorios_microrregioes',
            6 => 'spat.ed_territorios_mesoregioes',
            7 => 'spat.ed_territorios_piaui_tds'
        ];

        $select_sigla = "$tabelas[$abrangencia].edterritorios_sigla";
        if($abrangencia == 4){
            $select_sigla = "$tabelas[$abrangencia].edterritorios_nome";
        }

        $filename = clean($serie);

        $where = [['valores_series.serie_id', $id]];
        if(!empty($min)){
            array_push($where, ['valores_series.periodo', '>=', $min]);
            array_push($where, ['valores_series.periodo', '<=', $max]);
            $filename .= "-$min-$max";
        }

        $rows = DB::table('valores_series')
            ->select(DB::raw("$tabelas[$abrangencia].edterritorios_codigo as cod, $select_sigla as sigla, valores_series.valor, valores_series.periodo"))
            ->join($tabelas[$abrangencia], 'valores_series.regiao_id', '=', "$tabelas[$abrangencia].edterritorios_codigo")
            ->where($where)
            ->where("valores_series.tipo_regiao", $abrangencia)
            ->when($regions[0]!=0, function($query) use ($regions, $tabelas, $abrangencia){
                return $query->whereIn("$tabelas[$abrangencia].edterritorios_codigo", $regions);
            })
            //->whereIn("$tabelas[$abrangencia].edterritorios_codigo", $regions)
            ->orderBy('valores_series.periodo')
            ->get();

        $data = [];


        $cont = 1;
        $data[0] = ['cod', 'nome', 'período', 'valor'];

        if($request->downloadType=='ods'){

            foreach($rows as $row){
                $data[$cont] = [$row->cod, $row->sigla, $this->formatPeriodicidade('anual', $row->periodo), floatval($row->valor)];
                $cont++;
            }

            $data = collect($data);

            $ods = Exporter::make('OpenOffice');
            return view('serie.download', ['data' => $data, 'filename' => $filename.'.ods', 'ods' => $ods]);
        }



        foreach($rows as $row){

            if($request->decimal==','){
                $row->valor = number_format($row->valor, '2', ',', '');
            }

            $data[$cont] = [$row->cod, $row->sigla, $this->formatPeriodicidade('anual', $row->periodo), $row->valor];
            $cont++;
        }


        return view('serie.download', ['data' => $data, 'filename' => $filename.'.csv']);
    }

    private function formatPeriodicidade($periodicidade, $periodo){
        if($periodicidade=='anual'){
            return substr($periodo, 0, 4);
        }
    }

    public function getRegionsByAbrangencia($abrangencia){

        $table = $this->tabelas[$abrangencia];

        //$padraoTerritorios = Config::get('constants.PADRAO_TERRITORIOS');
        $padraoTerritorios = $this->getPadraoTerritorios();

        $uf = $padraoTerritorios[4];

        /*->when(!empty($codigoTerritorioSelecionado), function($query) use ($tabelaTerritorioSelecionado, $codigoTerritorioSelecionado){
            return $query->where($tabelaTerritorioSelecionado.".edterritorios_codigo", $codigoTerritorioSelecionado);
        })*/

        if($uf[0]==0){
            return [0];
        }

        //DB::enableQueryLog();

        //Log::info([$padraoTerritorios]);

        $result = DB::table($table)->select('edterritorios_codigo')
            ->when($abrangencia==4 && $uf[0]!=0, function($query) use ($uf){
                return $query->where('edterritorios_sigla', $uf);
            })
            ->get();

        //Log::info(DB::getQueryLog());

        $regions = [];


        foreach ($result as $item) {
            array_push($regions, $item->edterritorios_codigo);
        }

        return $regions;
    }


    ////////////////////////////////////////////////////////////////////
    //////////////////////////COMPARAR SERIES///////////////////////////
    ////////////////////////////////////////////////////////////////////

    public function validarCompararSeries(Request $request){
        $ids = explode(',', $request->ids);

        return 1;
    }

    public function dataSeriesComparadas($ids){
        $lang =  App::getLocale();

        $ids = explode(',', $ids);

        $series = [];

        foreach ($ids as $id) {
            $serie = \App\Serie::select('series.id', 'textos_series.titulo', 'textos_series.idioma_sigla', 'periodicidades.titulo as periodicidade', 'fontes.titulo as fonte', 'unidades.titulo as unidade', 'unidades.tipo as tipo_unidade')
                ->join('textos_series', 'textos_series.serie_id', '=', 'series.id')
                ->join('periodicidades', 'periodicidades.id', '=', 'series.periodicidade_id')
                ->join('fontes', 'fontes.id', '=', 'series.fonte_id')
                ->join('unidades', 'unidades.id', '=', 'series.unidade')
                ->where('textos_series.idioma_sigla', $lang)
                ->where('series.id', $id)->first();

            //$regions = explode(',', $request->regions);

            //$abrangencias = Config::get('constants.PADRAO_ABRANGENCIA');
            $abrangenciasSettings = \App\Setting::find(1)->padrao_abrangencia;
            $abrangencias = explode(',', $abrangenciasSettings);

            $indiceAbrangencia = 0;
            $abrangencia = $abrangencias[$indiceAbrangencia];


            //alem de pegar os valores de from e to também serve para verificar se existem valores nesta abrangência
            $fromTo = $this->fromToPeriodo($abrangencias, $indiceAbrangencia, $abrangencia, $id);

            //senão existe valores em nenhuma das abrangências pesquisadas.
            if($fromTo==0){
                $serie = null;
            }

            $serie->from = $fromTo[0];
            $serie->to = $fromTo[1];
            $serie->abrangencia = $fromTo[2];


            $regions = $this->getRegions($serie->abrangencia);

            $serie->regions = implode(",", $regions);

            $serie->abrangenciasOk = $this->verifyExistsRegions($abrangencias, $id);


            array_push($series, $serie);
        }

        $ids = "";
        $periodicidade = null;
        $tipoValores = null;
        $tipoUnidade = null;
        $from = null;
        $to = null;
        $regions = null;
        $abrangencia = null;
        $abrangenciasOk = null;

        $ids = $this->idsSeries($series);
        $from = $this->fromSeries($series);
        $to = $this->toSeries($series);
        $regions = $series[0]->regions;
        $abrangencia = $series[0]->abrangencia;
        $abrangenciasOk = $series[0]->abrangenciasOk;
        $periodicidade = $series[0]->periodicidade;



        return view('data-series-comparadas', [
            'ids' => $ids,
            'from'          => $from,
            'to'            => $to,
            'regions'       => $regions,
            'abrangencia'   => $abrangencia,
            'abrangenciasOk'   => $abrangenciasOk,
            'periodicidade'   => $periodicidade,
        ]);
    }

    private function idsSeries($series){
        $ids = "";
        foreach ($series as $serie) {
            $ids .= $serie->id.',';
        }
        $ids = substr($ids, 0, -1);
        return $ids;
    }

    private function fromSeries($series){
        $minFrom = $series[0]->from;
        foreach ($series as $serie) {
            if($serie->from < $minFrom){
                $minFrom = $serie->from;
            }
        }
        return $minFrom;
    }

    private function toSeries($series){
        $maxTo = $series[0]->to;
        foreach ($series as $serie) {
            if($serie->to < $maxTo){
                $maxTo = $serie->to;
            }
        }
        return $maxTo;
    }

    public function compararValoresPeriodoRegioesSelecionadas($ids, $min, $max, $regions, $abrangencia){

        $lang =  App::getLocale();

        //Log::info('periodo-'.$id.'-'.$min.'-'.$max.'-'.str_replace(',', '', $regions).'-'.$abrangencia);


        //Log::info('valoresPeriodoRegioesSelecionadas: '.$cacheKey);

        $arrayRegions = explode(',', $regions);
        $ids = explode(',', $ids);

        //Log::info($abrangencia);

        $tabelas = [
            1 => 'spat.ed_territorios_paises',
            2 => 'spat.ed_territorios_regioes',
            3 => 'spat.ed_territorios_uf',
            4 => 'spat.ed_territorios_municipios',
            5 => 'spat.ed_territorios_microrregioes',
            6 => 'spat.ed_territorios_mesoregioes',
            7 => 'spat.ed_territorios_piaui_tds'
        ];

        $select_sigla = "$tabelas[$abrangencia].edterritorios_sigla";
        if($abrangencia == 4){
            $select_sigla = "$tabelas[$abrangencia].edterritorios_nome";
        }

        //DB::enableQueryLog();



        $series = [];

        foreach($ids as $id){

            $cacheKey = sha1('periodo-'.$id.'-'.$min.'-'.$max.'-'.str_replace(',', '', $regions).'-'.$abrangencia);
            //exclui o cache. Utilizar apenas para testes.
            $this->cache->forget($cacheKey);

            if(!$this->cache->has($cacheKey)){
                $this->cache->put($cacheKey, DB::table('valores_series')
                    ->select(DB::raw("series.id, textos_series.titulo, $select_sigla as sigla, valores_series.valor, valores_series.periodo"))
                    ->join($tabelas[$abrangencia], 'valores_series.regiao_id', '=', "$tabelas[$abrangencia].edterritorios_codigo")
                    ->join('series', 'valores_series.serie_id', '=', "series.id")
                    ->join('textos_series', 'textos_series.serie_id', '=', "series.id")
                    ->where([
                        ['valores_series.serie_id', $id],
                        ['valores_series.periodo', '>=', $min],
                        ['valores_series.periodo', '<=', $max],
                        ['valores_series.tipo_regiao', $abrangencia],
                        ['textos_series.idioma_sigla', $lang]
                    ])
                    ->when($regions[0]!=0, function($query) use ($arrayRegions, $tabelas, $abrangencia){
                        return $query->whereIn("$tabelas[$abrangencia].edterritorios_codigo", $arrayRegions);
                    })
                    /*->whereIn("$tabelas[$abrangencia].edterritorios_codigo", $regions)*/
                    ->orderBy(DB::Raw($tabelas[$abrangencia].'.edterritorios_codigo, valores_series.periodo'))
                    ->get(), 720);
            }

            $rows = $this->cache->get($cacheKey);

            //Log::info(DB::getQueryLog());

            $data = [];

            foreach($rows as $row){
                //$data[$row->sigla][$row->periodo] = $row->valor;
                $data[$row->periodo] = $row->valor;
            }

            //array_push($series, $data);
            if(count($rows) > 0){
                $series[$rows[0]->titulo] = $data;
            }
            

        }

        $periodos = [];
        foreach ($series as $serie) {
            foreach ($data as $periodo => $valor) {
                if(!array_key_exists($periodo, $periodos)){
                    array_push($periodos, $periodo);
                }
            }            
        }

        foreach ($periodos as $periodo) {        
            foreach ($series as $key => $serie) {
                if(!array_key_exists($periodo, $series[$key])){
                    $series[$key][$periodo] = null;
                }
            }
        }

        foreach ($series as $key => $serie) {
            ksort($serie);
            Log::info($serie);
            $series[$key] = $serie;
        }
        

        return $series;
    }

    public function getRegionsByIds(Request $request){
        $ids = explode(',', $request->ids);
        $abrangencia = $request->abrangencia;
        $regions = DB::table($this->tabelas[$abrangencia])
        ->select('edterritorios_codigo as id', 'edterritorios_nome as nome', 'edterritorios_sigla as sigla')
        ->whereIn('edterritorios_codigo', $ids)
        ->get();

        return $regions;
    }

}


