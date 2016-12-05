<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use Illuminate\Support\Facades\DB;

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

    public function listarSeries(){
        $series = [
            [
                'id' => 1,
                'serie' => 'Dívida externa - registrada',
                'frequencia' => 'Anual de 1889 até 2008',
                'fonte' => 'Banco Central do Brasil, Boletim, Seção Balanço de Pagamentos (Bacen / Boletim / BP)',
                'unidade' => 'US$ (milhões)',
                'comentario' => 'Para 1889-1945: Abreu, Marcelo de Paiva (Org.). A ordem do progresso - cem anos de política econômica republicana. Rio de Janeiro: Campus, 1992. Obs.: A partir de mar. 2001, exclui empréstimos intercompanhias (retroativo a 2000) e contempla revisão na posição de endividamento. Compreende o setor público financeiro e não financeiro e o setor privado.',
                'atualizado' => '26/06/2009',
            ],

            [
                'id' => 2,
                'serie' => 'Dívida externa - registrada - bônus',
                'frequencia' => 'Anual de 1889 até 2008',
                'fonte' => 'Banco Central do Brasil, Boletim, Seção Balanço de Pagamentos (Bacen / Boletim / BP)',
                'unidade' => 'US$ (milhões)',
                'comentario' => 'Para 1889-1945: Abreu, Marcelo de Paiva (Org.). A ordem do progresso - cem anos de política econômica republicana. Rio de Janeiro: Campus, 1992. Obs.: A partir de mar. 2001, exclui empréstimos intercompanhias (retroativo a 2000) e contempla revisão na posição de endividamento. Compreende o setor público financeiro e não financeiro e o setor privado.',
                'atualizado' => '26/06/2009',
            ],

            [
                'id' => 3,
                'serie' => 'Dívida externa - pública - registrada',
                'frequencia' => 'Anual de 1889 até 2008',
                'fonte' => 'Banco Central do Brasil, Boletim, Seção Balanço de Pagamentos (Bacen / Boletim / BP)',
                'unidade' => 'US$ (milhões)',
                'comentario' => 'Para 1889-1945: Abreu, Marcelo de Paiva (Org.). A ordem do progresso - cem anos de política econômica republicana. Rio de Janeiro: Campus, 1992. Obs.: A partir de mar. 2001, exclui empréstimos intercompanhias (retroativo a 2000) e contempla revisão na posição de endividamento. Compreende o setor público financeiro e não financeiro e o setor privado.',
                'atualizado' => '26/06/2009',
            ],

        ];

        $series_valores = [
            ['id_serie' => 1, 'data' => '1980', 'valor' => 3.1423],
            ['id_serie' => 1, 'data' => '1981', 'valor' => 3.1213],
            ['id_serie' => 1, 'data' => '1982', 'valor' => 3.1187],
            ['id_serie' => 1, 'data' => '1983', 'valor' => 3.1305],
            ['id_serie' => 1, 'data' => '1984', 'valor' => 3.1587],
            ['id_serie' => 1, 'data' => '1985', 'valor' => 3.2119],
            ['id_serie' => 1, 'data' => '1986', 'valor' => 3.1794],
            ['id_serie' => 1, 'data' => '1987', 'valor' => 3.1868],
            ['id_serie' => 1, 'data' => '1988', 'valor' => 3.1957],
            ['id_serie' => 1, 'data' => '1989', 'valor' => 3.1858],
            ['id_serie' => 1, 'data' => '1990', 'valor' => 3.2081],
            ['id_serie' => 1, 'data' => '1991', 'valor' => 3.2130],
            ['id_serie' => 1, 'data' => '1992', 'valor' => 3.2119],
            ['id_serie' => 1, 'data' => '1993', 'valor' => 3.2128],
            ['id_serie' => 1, 'data' => '1994', 'valor' => 3.2304],
            ['id_serie' => 1, 'data' => '1995', 'valor' => 3.2353],
            ['id_serie' => 1, 'data' => '1996', 'valor' => 3.2197],
            ['id_serie' => 1, 'data' => '1997', 'valor' => 3.2332],
            ['id_serie' => 1, 'data' => '1998', 'valor' => 3.2456],
            ['id_serie' => 1, 'data' => '1999', 'valor' => 3.2229],
            ['id_serie' => 1, 'data' => '2000', 'valor' => 3.2470],

            ['id_serie' => 2, 'data' => '1980', 'valor' => 3.2352],
            ['id_serie' => 2, 'data' => '1981', 'valor' => 3.2394],
            ['id_serie' => 2, 'data' => '1982', 'valor' => 3.2236],
            ['id_serie' => 2, 'data' => '1983', 'valor' => 3.2009],
            ['id_serie' => 2, 'data' => '1984', 'valor' => 3.2402],
            ['id_serie' => 2, 'data' => '1985', 'valor' => 3.2534],
            ['id_serie' => 2, 'data' => '1986', 'valor' => 3.2630],
            ['id_serie' => 2, 'data' => '1987', 'valor' => 3.2998],
            ['id_serie' => 2, 'data' => '1988', 'valor' => 3.3320],
            ['id_serie' => 2, 'data' => '1989', 'valor' => 3.3256],
            ['id_serie' => 2, 'data' => '1990', 'valor' => 3.2966],
            ['id_serie' => 2, 'data' => '1991', 'valor' => 3.2848],
            ['id_serie' => 2, 'data' => '1992', 'valor' => 3.2534],
            ['id_serie' => 2, 'data' => '1993', 'valor' => 3.1928],
            ['id_serie' => 2, 'data' => '1994', 'valor' => 3.2446],
            ['id_serie' => 2, 'data' => '1995', 'valor' => 3.2715],
            ['id_serie' => 2, 'data' => '1996', 'valor' => 3.2425],
            ['id_serie' => 2, 'data' => '1997', 'valor' => 3.2466],
            ['id_serie' => 2, 'data' => '1998', 'valor' => 3.2397],
            ['id_serie' => 2, 'data' => '1999', 'valor' => 3.2519],
            ['id_serie' => 2, 'data' => '2000', 'valor' => 3.2607],

            ['id_serie' => 3, 'data' => '1980', 'valor' => 3.2147],
            ['id_serie' => 3, 'data' => '1981', 'valor' => 3.2313],
            ['id_serie' => 3, 'data' => '1982', 'valor' => 3.2366],
            ['id_serie' => 3, 'data' => '1983', 'valor' => 3.2047],
            ['id_serie' => 3, 'data' => '1984', 'valor' => 3.2128],
            ['id_serie' => 3, 'data' => '1985', 'valor' => 3.2261],
            ['id_serie' => 3, 'data' => '1986', 'valor' => 3.2209],
            ['id_serie' => 3, 'data' => '1987', 'valor' => 3.2242],
            ['id_serie' => 3, 'data' => '1988', 'valor' => 3.1743],
            ['id_serie' => 3, 'data' => '1989', 'valor' => 3.1666],
            ['id_serie' => 3, 'data' => '1990', 'valor' => 3.1596],
            ['id_serie' => 3, 'data' => '1991', 'valor' => 3.2130],
            ['id_serie' => 3, 'data' => '1992', 'valor' => 3.2119],
            ['id_serie' => 3, 'data' => '1993', 'valor' => 3.2128],
            ['id_serie' => 3, 'data' => '1994', 'valor' => 3.2304],
            ['id_serie' => 3, 'data' => '1996', 'valor' => 3.2197],
            ['id_serie' => 3, 'data' => '1997', 'valor' => 3.2332],
            ['id_serie' => 3, 'data' => '1999', 'valor' => 3.2229],
            ['id_serie' => 3, 'data' => '2000', 'valor' => 3.2470],
        ];


        for($i=0;$i<count($series_valores);$i++){
            $series_valores[$i]['id'] = $i+1;
        }

        $return = ['series' => $series, 'valores' => $series_valores];

        return $return;
    }
}
