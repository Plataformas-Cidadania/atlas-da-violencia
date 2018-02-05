<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class TransitoController extends Controller
{

    private $territorios = [
        1 => ['nome' => "País", 'tabela' => "spat.ed_territorios_paises"],
        2 => ['nome' => "Região", 'tabela' => "spat.ed_territorios_regioes"],
        3 => ['nome' => "UF", 'tabela' => "spat.ed_territorios_uf"],
        4 => ['nome' => "Município", 'tabela' => "spat.ed_territorios_municipios"],
    ];

    public function valoresMapa(Request $request){

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

    public function totalPorTerritorio(Request $request){
        $start = $request->start;
        $end = $request->end;
        $type = $request->type;

        $codigoTerritorioSelecionado = $request->codigoTerritorioSelecionado;
        $tabelaTerritorioSelecionado = $this->territorios[$request->tipoTerritorioSelecionado]['tabela'];

        $tipoTerritorioAgrupamento = $request->tipoTerritorioAgrupamento;
        $tabelaTerritorioAgrupamento = $this->territorios[$request->tipoTerritorioAgrupamento]['tabela'];

        //Log::info($codigoTerritorioSelecionado);
        //Log::info($tabelaTerritorioSelecionado);
        //Log::info($tabelaTerritorioAgrupamento);

        $valores = DB::table("geovalores")
            ->select(DB::raw("
            ST_X($tabelaTerritorioAgrupamento.edterritorios_centroide) as lng, 
            ST_Y($tabelaTerritorioAgrupamento.edterritorios_centroide) as lat,
            $tabelaTerritorioAgrupamento.edterritorios_sigla as sigla,
            $tabelaTerritorioAgrupamento.edterritorios_nome as nome,
            $tabelaTerritorioAgrupamento.edterritorios_codigo as codigo,
            '$tipoTerritorioAgrupamento' as tipo_territorio,
            count(geovalores.id) as total
            "))
            ->join('series', 'series.id', '=', 'geovalores.serie_id')
            ->join($tabelaTerritorioAgrupamento, DB::Raw("ST_Contains($tabelaTerritorioAgrupamento.edterritorios_geometry, geovalores.ponto)"), '=', DB::Raw("true"))
            ->join($tabelaTerritorioSelecionado, DB::Raw("ST_Contains($tabelaTerritorioSelecionado.edterritorios_geometry, $tabelaTerritorioAgrupamento.edterritorios_centroide)"), '=', DB::Raw("true"))
            ->where([
                ['geovalores.serie_id', 1],
                ['geovalores.data', '>=', $start],
                ['geovalores.data', '<=', $end]
            ])
            ->when(!empty($codigoTerritorioSelecionado), function($query) use ($tabelaTerritorioSelecionado, $codigoTerritorioSelecionado){
                return $query->where($tabelaTerritorioSelecionado.".edterritorios_codigo", $codigoTerritorioSelecionado);
            })
            ->when($type > 0, function($query) use ($type){
                return $query->where('geovalores.tipo', $type);
            })
            ->groupBy(DB::Raw("
            ST_X($tabelaTerritorioAgrupamento.edterritorios_centroide), 
            ST_Y($tabelaTerritorioAgrupamento.edterritorios_centroide),
            $tabelaTerritorioAgrupamento.edterritorios_sigla,
            $tabelaTerritorioAgrupamento.edterritorios_nome,
            $tabelaTerritorioAgrupamento.edterritorios_codigo
            "))
            ->get();

        return ['valores' => $valores];
    }

    public function pontosPorTerritorio(Request $request){


        $start = $request->start;
        $end = $request->end;
        $type = $request->type;

        $codigoTerritorioSelecionado = $request->codigoTerritorioSelecionado;
        $tabelaTerritorioSelecionado = $this->territorios[$request->tipoTerritorioSelecionado]['tabela'];


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
            ->join($tabelaTerritorioSelecionado, DB::Raw("ST_Contains($tabelaTerritorioSelecionado.edterritorios_geometry, geovalores.ponto)"), '=', DB::Raw("true"))
            ->where([
                ['geovalores.serie_id', 1],
                ['geovalores.data', '>=', $start],
                ['geovalores.data', '<=', $end]
            ])
            ->when(!empty($codigoTerritorioSelecionado), function($query) use ($tabelaTerritorioSelecionado, $codigoTerritorioSelecionado){
                return $query->where($tabelaTerritorioSelecionado.".edterritorios_codigo", $codigoTerritorioSelecionado);
            })
            ->when($type > 0, function($query) use ($type){
                return $query->where('geovalores.tipo', $type);
            })
            ->get();

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
}