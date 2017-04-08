<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class SerieController extends Controller
{
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

        $serie = \App\Serie::find($id);

        return view('serie.filtros', ['serie' => $serie, 'id' => $id, 'titulo' => $titulo]);
    }

    public function listarSeries(Request $request){

        $series = DB::table('series')
            ->select(DB::raw('series.*, min(valores_series.periodo) as min, max(valores_series.periodo) as max'))
            ->join('valores_series', 'valores_series.serie_id', '=', 'series.id')
            ->where('series.titulo', 'ilike', "%$request->search%")
            ->groupBy('series.id')
            ->get();

        return $series;
    }

    public function listarSeriesRelacionadas(Request $request){

        $parameters = $request->parameters;

        $series = DB::table('series')
            ->select(DB::raw('series.*, min(valores_series.periodo) as min, max(valores_series.periodo) as max, valores_series.tipo_regiao'))
            ->join('valores_series', 'valores_series.serie_id', '=', 'series.id')
            ->where([
                ['series.id', $parameters['id']],
                ['series.indicador', $parameters['indicador']],
                ['series.territorio', $parameters['territorio']]
            ])
            ->orWhere('series.serie_id', $parameters['id'])
            ->groupBy('series.id', 'valores_series.tipo_regiao')
            ->orderBy('series.titulo')
            ->get();

        return $series;
    }

    public function dataSeries(Request $request){
        $serie = \App\Serie::find($request->id);

        //$regions = explode(',', $request->regions);

        return view('data-series', [
            'id' => $request->id,
            'series' => $serie,
            'from' => $request->from,
            'to' => $request->to,
            'regions' => $request->regions,
            'territorio' => $request->territorio,
            /*'typeRegion' => $request->typeRegion,
            'typeRegionSerie' => $request->typeRegionSerie*/
        ]);
    }

    function valoresRegiaoPrimeiroUltimoPeriodo($id, $min, $max, $regions, $territorio){
        //$typeRegionSerie: 1(regiao), 2(uf) 3(municipio)

        $regions = explode(',', $regions);

        Log:info($min.','.$max);

        $tabelas = [
            1 => 'spat.ed_territorios_paises',
            2 => 'spat.ed_territorios_regioes',
            3 => 'spat.ed_territorios_uf',
            4 => 'spat.ed_territorios_municipios',
            5 => 'spat.ed_territorios_microrregioes',
            6 => 'spat.ed_territorios_mesoregioes'
        ];

        $valoresMin = DB::table('valores_series')
            ->select(DB::raw("valores_series.valor as valor, valores_series.uf as sigla, $tabelas[$territorio].edterritorios_nome as nome"))
            ->join($tabelas[$territorio], 'valores_series.uf', '=', "$tabelas[$territorio].edterritorios_sigla")
            ->where([
                ['valores_series.serie_id', $id],
                ['valores_series.periodo', $min]
            ])
            ->whereIn('valores_series.regiao_id', $regions)
            ->orderBy("$tabelas[$territorio].edterritorios_sigla")
            ->get();

        $valoresMax = DB::table('valores_series')
            ->select(DB::raw("valores_series.valor as valor, valores_series.uf as sigla, $tabelas[$territorio].edterritorios_nome as nome"))
            ->join($tabelas[$territorio], 'valores_series.uf', '=', "$tabelas[$territorio].edterritorios_sigla")
            ->where([
                ['valores_series.serie_id', $id],
                ['valores_series.periodo', $max]
            ])
            ->whereIn('valores_series.regiao_id', $regions)
            ->orderBy("$tabelas[$territorio].edterritorios_sigla")
            ->get();

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


    function valoresPeriodoPorRegiao($id, $min, $max){
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
    }

    function valoresPeriodoRegioesSelecionadas($id, $min, $max, $regions, $territorio){

        $regions = explode(',', $regions);

        //Log::info($territorio);

        $tabelas = [
            1 => 'spat.ed_territorios_paises',
            2 => 'spat.ed_territorios_regioes',
            3 => 'spat.ed_territorios_uf',
            4 => 'spat.ed_territorios_municipios',
            5 => 'spat.ed_territorios_microrregioes',
            6 => 'spat.ed_territorios_mesoregioes'
        ];

        $rows = DB::table('valores_series')
            ->select(DB::raw("$tabelas[$territorio].edterritorios_sigla as sigla, valores_series.valor, valores_series.periodo"))
            ->join($tabelas[$territorio], 'valores_series.uf', '=', "$tabelas[$territorio].edterritorios_sigla")
            ->where([
                ['valores_series.serie_id', $id],
                ['valores_series.periodo', '>=', $min],
                ['valores_series.periodo', '<=', $max]
            ])
            ->whereIn("$tabelas[$territorio].edterritorios_codigo", $regions)
            ->orderBy('valores_series.periodo')
            ->get();

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

    public function regioes($id){
        $tipo_regiao = \App\ValorSerie::select('tipo_regiao')->where('serie_id', $id)->first();


        if($tipo_regiao->tipo_regiao==2){
            return $this->porUf($id);
        }


        /*$regioes = DB::table('valores_series')
            ->select('uf')
            ->where('serie_id', $id)
            ->distinct()
            ->orderBy('uf')
            ->get();

        return $regioes;*/
    }

    private function porRegiao(){

    }

    private function porUf($id){
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


            /*if(!array_key_exists($region->nome_regiao, $regioes)){
                $regioes[$region->nome_regiao] = [
                    'ufs' => [],
                    'sigla' => $region->sigla_regiao
                ];
            }

            array_push($regioes[$region->nome_regiao]['ufs'],[
                'uf' => $region->nome_uf,
                'sigla' => $region->sigla_uf
            ]);*/

        }


        return $regioes;
    }

    private function porMunicipio(){

    }

    private function porMicroRegiao(){

    }

    private function porMesoRegiao(){

    }

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
            6 => 'spat.ed_territorios_mesoregioes'
        ];

        $where = [['edterritorios_nome', 'ilike', "$search%"]];

        if($tipo == 4){
            if($filter){
                array_push($where, ['edterritorios_sigla', $filter]);
            }
        }

        DB::connection()->enableQueryLog();

        if($tipo > 1){
            $territorios = DB::table($tabelas[$tipo])
                ->select('edterritorios_codigo as id', 'edterritorios_nome as title', 'edterritorios_sigla as sigla')
                ->where($where)
                ->get();
            Log::info(DB::getQueryLog());
            return $territorios;
        }

        //Caso o território seja do tipo país
        $territorios = DB::table($tabelas[$tipo])
            ->select('edterritorios_codigo as id', 'edterritorios_nome as title', 'edterritorios_sigla as sigla')
            ->where('edterritorios_nome', 'like', "%$search%")
            ->whereIn('edterritorios_nome', $paises)
            ->get();
        return $territorios;

    }

    function homeChart($id){
        $rows = DB::table('valores_series')
            ->select(DB::raw("valores_series.uf, valores_series.valor, valores_series.periodo"))
            ->join('ed_territorios_uf', 'valores_series.uf', '=', 'ed_territorios_uf.edterritorios_sigla')
            ->where([
                ['valores_series.serie_id', $id],
                ['valores_series.uf', "RJ"]
            ])
            ->orderBy('valores_series.periodo')
            ->get();

        $data = [];

        foreach($rows as $row){
            $data[$row->periodo] = $row->valor;
        }

        return $data;
    }
}
