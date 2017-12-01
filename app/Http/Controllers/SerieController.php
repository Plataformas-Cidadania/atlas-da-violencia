<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class SerieController extends Controller
{

    private $cache;

    public function __construct(\Illuminate\Cache\Repository $cache){
        $this->indicadores = config('constants.indicadores');
        $this->abrangencias = config('constants.abrangencias');
        $this->cache = $cache;
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

        $idioma = 'pt_BR';

        $serie = \App\Serie::select('series.*', 'textos_series.titulo as titulo')
            ->join('textos_series', 'series.id', '=', 'textos_series.serie_id')
            ->where('series.id', $id)
            ->where('textos_series.idioma_sigla', $idioma)->first();

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

    public function dataSeries(Request $request){
        $serie = \App\Serie::select('series.id', 'textos_series.*', 'periodicidades.titulo as periodicidade')
            ->join('textos_series', 'textos_series.serie_id', '=', 'series.id')
            ->join('periodicidades', 'periodicidades.id', '=', 'series.periodicidade_id')
            ->where('series.id', $request->id)->first();

        //$regions = explode(',', $request->regions);

        return view('data-series', [
            'id' => $request->id,
            'series' => $serie,
            'from' => $request->from,
            'to' => $request->to,
            'regions' => $request->regions,
            'abrangencia' => $request->abrangencia,
            /*'typeRegion' => $request->typeRegion,
            'typeRegionSerie' => $request->typeRegionSerie*/
        ]);
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

        if(!$this->cache->has($cacheKeyMin)){
            $this->cache->put($cacheKeyMin, DB::table('valores_series')
                ->select(DB::raw("valores_series.valor as valor, $select_sigla as sigla, $tabelas[$abrangencia].edterritorios_nome as nome"))
                ->join($tabelas[$abrangencia], 'valores_series.regiao_id', '=', "$tabelas[$abrangencia].edterritorios_codigo")
                ->where([
                    ['valores_series.serie_id', $id],
                    ['valores_series.periodo', $min],
                    ['valores_series.tipo_regiao', $abrangencia]
                ])
                ->whereIn('valores_series.regiao_id', $regions)
                ->orderBy("$tabelas[$abrangencia].edterritorios_sigla")
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

        //Log::info(DB::getQueryLog());

        //Log::info($valoresMin);

        if(!$this->cache->has($cacheKeyMax)){
            $this->cache->put($cacheKeyMax, DB::table('valores_series')
                ->select(DB::raw("valores_series.valor as valor, $select_sigla as sigla, $tabelas[$abrangencia].edterritorios_nome as nome"))
                ->join($tabelas[$abrangencia], 'valores_series.regiao_id', '=', "$tabelas[$abrangencia].edterritorios_codigo")
                ->where([
                    ['valores_series.serie_id', $id],
                    ['valores_series.periodo', $max],
                    ['valores_series.tipo_regiao', $abrangencia]
                ])
                ->whereIn('valores_series.regiao_id', $regions)
                ->orderBy("$tabelas[$abrangencia].edterritorios_sigla")
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

        Log::info('valoresPeriodoRegioesSelecionadas: '.$cacheKey);

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

        if(!$this->cache->has($cacheKey)){
            $this->cache->put($cacheKey, DB::table('valores_series')
                ->select(DB::raw("$select_sigla as sigla, valores_series.valor, valores_series.periodo"))
                ->join($tabelas[$abrangencia], 'valores_series.regiao_id', '=', "$tabelas[$abrangencia].edterritorios_codigo")
                ->where([
                    ['valores_series.serie_id', $id],
                    ['valores_series.periodo', '>=', $min],
                    ['valores_series.periodo', '<=', $max],
                    ['valores_series.tipo_regiao', '<=', $abrangencia]
                ])
                ->whereIn("$tabelas[$abrangencia].edterritorios_codigo", $regions)
                ->orderBy('valores_series.periodo')
                ->get(), 720);
        }

        $rows = $this->cache->get($cacheKey);

        //Log::info($rows);

        /*$rows = DB::table('valores_series')
            ->select(DB::raw("$select_sigla as sigla, valores_series.valor, valores_series.periodo"))
            ->join($tabelas[$abrangencia], 'valores_series.regiao_id', '=', "$tabelas[$abrangencia].edterritorios_codigo")
            ->where([
                ['valores_series.serie_id', $id],
                ['valores_series.periodo', '>=', $min],
                ['valores_series.periodo', '<=', $max],
                ['valores_series.tipo_regiao', '<=', $abrangencia]
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

    /*public function regioes($id){
        $tipo_regiao = \App\ValorSerie::select('tipo_regiao')->where('serie_id', $id)->first();


        if($tipo_regiao->tipo_regiao==2){
            return $this->porUf($id);
        }

    }*/

    /*private function porUf($id){
        DB::connection()->enableQueryLog();

        $regions = DB::table('public.valores_series')
            ->select(
                'spat.ed_territorios_regioes.edterritorios_codigo as codigo_regiao',
                'spat.ed_territorios_regioes.edterritorios_nome as nome_regiao',
                'spat.ed_territorios_regioes.edterritorios_sigla as sigla_regiao',
                'spat.ed_territorios_uf.edterritorios_codigo as codigo_uf',
                'spat.ed_territorios_uf.edterritorios_nome as nome_uf',
                'spat.ed_territorios_uf.edterritorios_sigla as sigla_uf',
                'public.valores_series.tipo_regiao'
            )
            ->join('spat.ed_territorios_uf', 'spat.ed_territorios_uf.edterritorios_codigo', '=', 'public.valores_series.regiao_id')
            ->join('spat.ed_uf', 'spat.ed_uf.eduf_cd_uf', '=', 'spat.ed_territorios_uf.edterritorios_codigo')
            ->join('spat.territorio', 'spat.territorio.terregiao', '=', 'spat.ed_uf.edre_cd_regiao')
            ->join('spat.ed_territorios_regioes', 'spat.ed_territorios_regioes.edterritorios_terid', '=', 'spat.territorio.terid')
            ->where('public.valores_series.serie_id', $id)
            ->distinct()
            ->get();

        //return $regions;

        $regioes = [];
        foreach ($regions as $region) {

            $key = array_search($region->nome_regiao, array_column($regioes, 'region'));

            if($key===false){
                array_push($regioes, [
                    'region' => $region->nome_regiao,
                    'codigo' => $region->codigo_regiao,
                    'sigla' => $region->sigla_regiao,
                    'open' => false,
                    'allUfsSelected' => false,
                    'selected' => false,
                    'typeRegionSerie' => $region->tipo_regiao,
                    'ufs' => []
                ]);
                $key = array_search([
                    'region' => $region->nome_regiao,
                    'codigo' => $region->codigo_regiao,
                    'sigla' => $region->sigla_regiao,
                    'open' => false,
                    'allUfsSelected' => false,
                    'selected' => false,
                    'typeRegionSerie' => $region->tipo_regiao,
                    'ufs' => []
                ], $regioes);
            }

            array_push($regioes[$key]['ufs'],[
                'codigo' => $region->codigo_uf,
                'uf' => $region->nome_uf,
                'sigla' => $region->sigla_uf,
                'selected' => false
            ]);
        }
        return $regioes;
    }*/


    public function territorios(Request $request){

        //return $request->all();
        //return $request->parameters['option'];

        $tipo = $request->parameters['option']['id'];
        $list = $request->parameters['option']['listAll'];
        $filter = $request->parameters['filter'];

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
                ['valores_series.serie_id', $id]
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
            ->select(DB::raw("$select_sigla as sigla, valores_series.valor, valores_series.periodo"))
            ->join($tabelas[$abrangencia], 'valores_series.regiao_id', '=', "$tabelas[$abrangencia].edterritorios_codigo")
            ->where($where)
            ->whereIn("$tabelas[$abrangencia].edterritorios_codigo", $regions)
            ->orderBy('valores_series.periodo')
            ->get();

        $data = [];


        $cont = 0;
        foreach($rows as $row){
            $data[$cont] = [$row->sigla, $row->periodo, $row->valor];
            $cont++;
        }

        return view('serie.download', ['data' => $data, 'filename' => $filename.'.csv']);
    }
}
