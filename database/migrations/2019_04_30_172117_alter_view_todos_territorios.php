<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterViewTodosTerritorios extends Migration
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
            CREATE OR REPLACE VIEW public.todos_territorios as
            SELECT t.edterritorios_codigo,            
                t.edterritorios_nome,            
                t.edterritorios_sigla,            
                case            
                       when t.edterritorios_tnivid=5 then t.edterritorios_sigla            
                       else t.edterritorios_sigla            
                end  AS sigla,            
                case            
                       when t.edterritorios_tnivid=98 then 1            
                       when t.edterritorios_tnivid=1 then 2            
                       when t.edterritorios_tnivid=2 then 3            
                       when t.edterritorios_tnivid=5 then 4            
                end  as tipo_territorio            
               FROM spat.ed_territorios t                          
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
        DB::statement(
            "
            create view public.todos_territorios as
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
}
