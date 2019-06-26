<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMaterializedViewMvwSeriesPointsByUf extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement("DROP MATERIALIZED VIEW IF EXISTS mvw_series_points_by_uf;");
        DB::statement(
            "  
            CREATE MATERIALIZED VIEW mvw_series_points_by_uf AS
            SELECT geovalores.id,
                   geovalores.serie_id,
                   regioes.edterritorios_codigo as codigo_territorio_nivel_acima,
                   ufs.edterritorios_codigo,
                   ufs.edterritorios_nome,
                   ufs.edterritorios_sigla,
                   ufs.edterritorios_centroide AS centroide_territorio,
            
                   st_x(geovalores.ponto) AS longitude,
                   st_y(geovalores.ponto) AS latitude,
                   geovalores.data AS data_ponto
            FROM geovalores geovalores
                     JOIN spat.ed_territorios_uf ufs ON st_contains(ufs.edterritorios_geometry, geovalores.ponto)
                     JOIN spat.ed_territorios_regioes regioes ON st_contains(regioes.edterritorios_geometry, ufs.edterritorios_centroide)
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
        DB::statement("DROP MATERIALIZED VIEW IF EXISTS mvw_series_points_by_uf;");
    }
}
