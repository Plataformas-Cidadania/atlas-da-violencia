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
            ->where('series.id', $parameters['id'])
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
            'typeRegion' => $request->typeRegion,
            'typeRegionSerie' => $request->typeRegionSerie
        ]);
    }

    function valoresRegiaoUltimoPeriodo($id, $max, $regions, $typeRegion, $typeRegionSerie){
        //$typeRegionSerie: 1(regiao), 2(uf) 3(municipio)

        $regions = explode(',', $regions);


        if($typeRegion=='region'){
            return $this->valoresRegiaoUltimoPeriodoPorRegiao($id, $max, $regions, $typeRegionSerie);
        }

        if($typeRegion=='uf'){
            return $this->valoresRegiaoUltimoPeriodoPorUf($id, $max, $regions,$typeRegionSerie);
        }

        if($typeRegion=='municipio'){
            return $this->valoresRegiaoUltimoPeriodoPorMunicipio($id, $max, $regions, $typeRegionSerie);
        }


        /*$valores = DB::table('valores_series')
            ->select(DB::raw("valores_series.valor as total, valores_series.uf, ed_territorios_uf.edterritorios_nome as nome"))
            ->join('ed_territorios_uf', 'valores_series.uf', '=', 'ed_territorios_uf.edterritorios_sigla')
            ->where([
                ['valores_series.serie_id', $id],
                ['valores_series.periodo', $max]
            ])
            ->groupBy('valores_series.uf', 'ed_territorios_uf.edterritorios_nome', 'valores_series.valor')
            ->orderBy('valores_series.uf')
            ->get();


        return $valores;*/
    }

    private function valoresRegiaoUltimoPeriodoPorRegiao($id, $max, $regions, $typeRegionSerie){
        DB::connection()->enableQueryLog();
        if($typeRegionSerie==1){


        }
        if($typeRegionSerie==2){
            $valores = DB::table('regions_by_uf')
                ->select(DB::raw(
                    "sum(valor) as total, sigla, nome"
                ))
                ->where([
                    ['serie_id', $id],
                    ['periodo', $max]
                ])
                ->whereIn('codigo', $regions)
                ->groupBy("sigla", "nome")
                ->get();

            Log::info(DB::getQueryLog());

            return $valores;
        }
        if($typeRegionSerie==3){

        }
    }

    private function valoresRegiaoUltimoPeriodoPorUf($id, $max, $regions, $typeRegionSerie){
        if($typeRegionSerie==1){

        }
        if($typeRegionSerie==2){
            $valores = DB::table('valores_series')
                ->select(DB::raw("valores_series.valor as total, valores_series.uf as sigla, ed_territorios_uf.edterritorios_nome as nome"))
                ->join('ed_territorios_uf', 'valores_series.uf', '=', 'ed_territorios_uf.edterritorios_sigla')
                ->where([
                    ['valores_series.serie_id', $id],
                    ['valores_series.periodo', $max]
                ])
                ->whereIn('valores_series.regiao_id', $regions)
                ->groupBy('valores_series.uf', 'ed_territorios_uf.edterritorios_nome', 'valores_series.valor')
                ->orderBy('valores_series.uf')
                ->get();

            return $valores;
        }
        if($typeRegionSerie==3){

        }
    }

    private function valoresRegiaoUltimoPeriodoPorMunicipio($id, $max, $regions, $typeRegionSerie){
        if($typeRegionSerie==1){

        }
        if($typeRegionSerie==2){

        }
        if($typeRegionSerie==3){

        }
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

    function valoresPeriodoRegioesSelecionadas($id, $min, $max, $regions, $typeRegion, $typeRegionSerie){

        $regions = explode(',', $regions);

        if($typeRegion=='region'){
            return $this->valoresPeriodoRegioesSelecionadasPorRegiao($id, $min, $max, $regions, $typeRegionSerie);
        }

        if($typeRegion=='uf'){
            return $this->valoresPeriodoRegioesSelecionadasPorUf($id, $min, $max, $regions,$typeRegionSerie);
        }

        if($typeRegion=='municipio'){
            return $this->valoresPeriodoRegioesSelecionadasPorMunicipio($id, $min, $max, $regions, $typeRegionSerie);
        }

        $rows = DB::table('valores_series')
            ->select(DB::raw("valores_series.uf, valores_series.valor, valores_series.periodo"))
            ->join('ed_territorios_uf', 'valores_series.uf', '=', 'ed_territorios_uf.edterritorios_sigla')
            ->where([
                ['valores_series.serie_id', $id],
                ['valores_series.periodo', '>=', $min],
                ['valores_series.periodo', '<=', $max]
            ])
            ->whereIn('valores_series.uf', $regions)
            ->orderBy('valores_series.periodo')
            ->get();

        $data = [];

        foreach($rows as $row){
            $data[$row->uf][$row->periodo] = $row->valor;
        }

        return $data;
    }

    private function valoresPeriodoRegioesSelecionadasPorRegiao($id, $min, $max, $regions, $typeRegionSerie){
        DB::connection()->enableQueryLog();
        if($typeRegionSerie==1){


        }
        if($typeRegionSerie==2){
            $rows = DB::table('regions_by_uf')
                ->select(DB::raw(
                    "sum(valor) as total, sigla, nome, periodo"
                ))
                ->where([
                    ['serie_id', $id],
                    ['periodo', '>=', $min],
                    ['periodo', '<=', $max]
                ])
                ->whereIn('codigo', $regions)
                ->groupBy("sigla", "nome", "periodo")
                ->get();

            Log::info(DB::getQueryLog());

            $data = [];

            foreach($rows as $row){
                $data[$row->sigla][$row->periodo] = $row->total;
            }

            return $data;
        }
        if($typeRegionSerie==3){

        }
    }

    private function valoresPeriodoRegioesSelecionadasPorUf($id, $min, $max, $regions, $typeRegionSerie){
        if($typeRegionSerie==1){

        }
        if($typeRegionSerie==2){
            $rows = DB::table('valores_series')
                ->select(DB::raw("ed_territorios_uf.edterritorios_sigla as sigla, valores_series.valor, valores_series.periodo"))
                ->join('ed_territorios_uf', 'valores_series.uf', '=', 'ed_territorios_uf.edterritorios_sigla')
                ->where([
                    ['valores_series.serie_id', $id],
                    ['valores_series.periodo', '>=', $min],
                    ['valores_series.periodo', '<=', $max]
                ])
                ->whereIn('ed_territorios_uf.edterritorios_codigo', $regions)
                ->orderBy('valores_series.periodo')
                ->get();

            $data = [];

            foreach($rows as $row){
                $data[$row->sigla][$row->periodo] = $row->valor;
            }

            return $data;
        }
        if($typeRegionSerie==3){

        }
    }

    private function valoresPeriodoRegioesSelecionadasPorMunicipio($id, $min, $max, $regions, $typeRegionSerie){
        if($typeRegionSerie==1){

        }
        if($typeRegionSerie==2){

        }
        if($typeRegionSerie==3){

        }
    }



    public function teste(){

        return view('serie.teste');

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

    public function territorios($tipo){
        $paises = ['Brasil'];

        $tabelas = [
            1 => 'ed_territorios_paises',
            2 => 'ed_territorios_regioes',
            3 => 'ed_territorios_uf',
            4 => 'ed_territorios_municipios',
            5 => 'ed_territorios_microrregioes',
            6 => 'ed_territorios_mesoregioes'
        ];

        if($tipo > 1){
            $territorios = DB::table($tabelas[$tipo])
                ->select('edterritorios_codigo', 'edterritorios_nome', 'edterritorios_sigla')
                ->get();
            return $territorios;
        }

        //Caso o território seja do tipo país
        $territorios = DB::table($tabelas[$tipo])
            ->select('edterritorios_codigo', 'edterritorios_nome', 'edterritorios_sigla')
            ->whereIn('edterritorios_nome', $paises)
            ->get();
        return $territorios;

    }
}
