<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMaterializedViewMvwSeriesPointsByRegion extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement("DROP MATERIALIZED VIEW IF EXISTS mvw_series_points_by_region;");
        DB::statement(
            "                       
            CREATE MATERIALIZED VIEW mvw_series_points_by_region AS
            SELECT geovalores.id,
                   geovalores.serie_id,
                   paises.edterritorios_codigo as codigo_territorio_nivel_acima,
                   regioes.edterritorios_codigo,
                   regioes.edterritorios_nome,
                   regioes.edterritorios_sigla,
                   regioes.edterritorios_centroide AS centroide_territorio,
                   st_x(geovalores.ponto) AS longitude,
                   st_y(geovalores.ponto) AS latitude,
                   geovalores.data AS data_ponto
            FROM geovalores geovalores
                     JOIN spat.ed_territorios_regioes regioes ON st_contains(regioes.edterritorios_geometry, geovalores.ponto)
                     JOIN spat.ed_territorios_paises paises ON st_contains(paises.edterritorios_geometry, regioes.edterritorios_centroide)
                     LEFT JOIN geovalores_valores_filtros ON geovalores_valores_filtros.geovalor_id = geovalores.id
                WITH DATA;   
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
        DB::statement("DROP MATERIALIZED VIEW IF EXISTS mvw_series_points_by_region;");
    }
}
