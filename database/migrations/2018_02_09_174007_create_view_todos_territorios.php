<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateViewTodosTerritorios extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement(
            "
            create view todos_territorios as
            select edterritorios_codigo, edterritorios_nome, edterritorios_sigla, edterritorios_sigla as sigla, '1' as tipo_territorio  from spat.ed_territorios_paises
            UNION
            select edterritorios_codigo, edterritorios_nome, edterritorios_sigla, edterritorios_sigla as sigla, '2' as tipo_territorio  from spat.ed_territorios_regioes
            UNION
            select edterritorios_codigo, edterritorios_nome, edterritorios_sigla, edterritorios_sigla as sigla, '3' as tipo_territorio  from spat.ed_territorios_uf
            UNION
            select m.edterritorios_codigo, m.edterritorios_nome, m.edterritorios_sigla, u.edterritorios_sigla as sigla, '4' as tipo_territorio  from spat.ed_territorios_municipios as m INNER JOIN spat.ed_territorios_uf as u on m.edterritorios_sigla = u.edterritorios_codigo::text                            
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
        DB::statement("drop view todos_territorios");
    }
}
