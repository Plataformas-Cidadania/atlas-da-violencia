<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class PontosController extends Controller
{

    private $territorios = [
        1 => ['nome' => "País", 'tabela' => "spat.ed_territorios_paises"],
        2 => ['nome' => "Região", 'tabela' => "spat.ed_territorios_regioes"],
        3 => ['nome' => "UF", 'tabela' => "spat.ed_territorios_uf"],
        4 => ['nome' => "Município", 'tabela' => "spat.ed_territorios_municipios"],
    ];

    private $months = [null, 'Jan', 'Fev', 'Mar', 'Abr', 'Mai', 'Jun', 'Jul', 'Ago', 'Set', 'Out', 'Nov', 'Dez'];

    public function filtrosSerie($serie_id){
        $filtros = \App\Filtro::select('filtros.id', 'filtros.titulo')
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
                ['valores_series.serie_id', 1],
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
            ->select(DB::raw("
            ST_X(agrupamento.edterritorios_centroide) as lng, 
            ST_Y(agrupamento.edterritorios_centroide) as lat,
            agrupamento.edterritorios_sigla as sigla,
            agrupamento.edterritorios_nome as nome,
            agrupamento.edterritorios_codigo as codigo,
            '$tipoTerritorioAgrupamento' as tipo_territorio,
            count(geovalores.id) as total
            "))
            ->join('series', 'series.id', '=', 'geovalores.serie_id')
            ->join("$tabelaTerritorioAgrupamento as agrupamento", DB::Raw("ST_Contains(agrupamento.edterritorios_geometry, geovalores.ponto)"), '=', DB::Raw("true"))
            ->join("$tabelaTerritorioSelecionado as selecionado", DB::Raw("ST_Contains(selecionado.edterritorios_geometry, agrupamento.edterritorios_centroide)"), '=', DB::Raw("true"))
            ->leftJoin('geovalores_valores_filtros', 'geovalores_valores_filtros.geovalor_id', '=', 'geovalores.id')//trocar serie_id por geovalor_id
            ->when(count($filters) > 0, function($query) use ($filters){
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
            })
            ->where([
                ['geovalores.serie_id', 1],
                ['geovalores.data', '>=', $start],
                ['geovalores.data', '<=', $end]
            ])
            ->when(!empty($codigoTerritorioSelecionado), function($query) use ($tabelaTerritorioSelecionado, $codigoTerritorioSelecionado){
                return $query->whereIn("selecionado.edterritorios_codigo", $codigoTerritorioSelecionado);
            })
            /*->when($types != null, function($query) use ($types){
                return $query->whereIn('geovalores.tipo', $types);
            })
            ->when($typesAccident != null, function($query) use ($typesAccident){
                return $query->whereIn('geovalores.tipo_acidente', $typesAccident);
            })
            ->when($genders != null, function($query) use ($genders){
                return $query->whereIn('geovalores.sexo', $genders);
            })*/
            ->groupBy(DB::Raw("
            ST_X(agrupamento.edterritorios_centroide), 
            ST_Y(agrupamento.edterritorios_centroide),
            agrupamento.edterritorios_sigla,
            agrupamento.edterritorios_nome,
            agrupamento.edterritorios_codigo
            "))
            ->get();

        Log::info($filters);
        Log::info(DB::getQueryLog());

        return ['valores' => $valores];
    }

    public function pontosPorTerritorio(Request $request){


        $start = $request->start;
        $end = $request->end;
        $filters = $request->filters;
        $types = $request->types;
        $typesAccident = $request->typesAccident;
        $genders = $request->genders;
        $paginate = $request->paginate;

        $codigoTerritorioSelecionado = $request->codigoTerritorioSelecionado;
        $tabelaTerritorioSelecionado = $this->territorios[$request->tipoTerritorioSelecionado]['tabela'];


        //Log::info($codigoTerritorioSelecionado);
        //Log::info($tabelaTerritorioSelecionado);
        //Log::info($tabelaTerritorioAgrupamento);

        DB::connection()->enableQueryLog();

        $valores = DB::table("geovalores")
            ->select(DB::raw("
            geovalores.id, 
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
            ->join($tabelaTerritorioSelecionado, DB::Raw("ST_Contains($tabelaTerritorioSelecionado.edterritorios_geometry, geovalores.ponto)"), '=', DB::Raw("true"))
            ->leftJoin('geovalores_valores_filtros', 'geovalores_valores_filtros.geovalor_id', '=', 'geovalores.id')
            ->when(count($filters) > 0, function($query) use ($filters){
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
            })
            ->where([
                ['geovalores.serie_id', 1],
                ['geovalores.data', '>=', $start],
                ['geovalores.data', '<=', $end]
            ])
            ->when(!empty($codigoTerritorioSelecionado), function($query) use ($tabelaTerritorioSelecionado, $codigoTerritorioSelecionado){
                return $query->whereIn($tabelaTerritorioSelecionado.".edterritorios_codigo", $codigoTerritorioSelecionado);
            });
            /*->when($types != null, function($query) use ($types){
                return $query->whereIn('geovalores.tipo', $types);
            })
            ->when($typesAccident != null, function($query) use ($typesAccident){
                return $query->whereIn('geovalores.tipo_acidente', $typesAccident);
            })
            ->when($genders != null, function($query) use ($genders){
                return $query->whereIn('geovalores.sexo', $genders);
            });*/

            $icones = DB::table('geovalores')
                ->select('geovalores.id', 'valores_filtros.imagem', 'valores_filtros.titulo')
                ->join('geovalores_valores_filtros', 'geovalores_valores_filtros.geovalor_id', '=', 'geovalores.id')
                ->join('valores_filtros', 'geovalores_valores_filtros.valor_filtro_id', '=', 'valores_filtros.id')
                ->join('filtros_series', 'valores_filtros.filtro_id', '=', 'filtros_series.id')
                ->get();

            Log::info([$icones]);


        if(!$paginate){
            $valores = $valores->get();
        }

        if($paginate){
            $valores = $valores->paginate(30);
        }

        //Log::info($filters);
        //Log::info(DB::getQueryLog());



        return ['valores' => $valores];
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
            ->join('spat.ed_territorios_paises', DB::Raw("ST_Contains(spat.ed_territorios_paises.edterritorios_geometry, geovalores.ponto)"), '=', DB::Raw("true"))
            ->where([
                ['geovalores.serie_id', 1],
                ['geovalores.data', '>=', $start],
                ['geovalores.data', '<=', $end],
                ['spat.ed_territorios_paises.edterritorios_codigo', $pais]
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
                ['valores_series.serie_id', 1],
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

        $tipo_territorio = $request->tipo_territorio;

        $regions = DB::table('todos_territorios')
            ->select('edterritorios_codigo as id', 'edterritorios_nome as title', 'sigla', 'tipo_territorio')
            ->where('edterritorios_nome', 'ilike', "$request->search%")
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

        $id = 1;

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

        $id = 1;
        $year = $request->year;

        //return $year;

        $months = [];

        $rows = DB::table('geovalores')
            ->select(DB::Raw('extract(month from data) as month'))
            ->where(DB::Raw('extract(year from data)'), $year)
            ->where('serie_id', $id)
            ->distinct()
            ->get();

        $rows = collect($rows)->sortBy('month')->toArray();

        //return $rows;


        foreach($rows as $row){
            array_push($months, $this->months[$row->month]);
        }

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
}
