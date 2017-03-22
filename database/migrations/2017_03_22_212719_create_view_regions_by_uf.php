<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateViewRegionsByUf extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement(
            "create view regions_by_uf as
            select 
            ST_AsGeoJSON(ed_territorios_regioes.edterritorios_geometry) as geometry, 
            valores_series.valor, 
            valores_series.periodo,
            valores_series.serie_id,
            ed_territorios_regioes.edterritorios_codigo as codigo, 
            ed_territorios_regioes.edterritorios_sigla as sigla, 
            ed_territorios_regioes.edterritorios_nome as nome, 
            ST_X(ed_territorios_regioes.edterritorios_centroide) as x, 
            ST_Y(ed_territorios_regioes.edterritorios_centroide) as y
            
            from valores_series inner join ed_territorios_uf on valores_series.regiao_id = ed_territorios_uf.edterritorios_codigo 
             
            inner join spat.ed_territorios_uf on spat.ed_territorios_uf.edterritorios_codigo = public.valores_series.regiao_id 
            inner join spat.ed_uf on spat.ed_uf.eduf_cd_uf = spat.ed_territorios_uf.edterritorios_codigo 
            inner join spat.territorio on spat.territorio.terregiao = spat.ed_uf.edre_cd_regiao 
            inner join spat.ed_territorios_regioes on spat.ed_territorios_regioes.edterritorios_terid = spat.territorio.terid 
            
             
            "
        );
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::statement("drop view regions_by_uf");
    }
}
