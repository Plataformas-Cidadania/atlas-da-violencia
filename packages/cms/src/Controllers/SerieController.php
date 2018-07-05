<?php

namespace Cms\Controllers;

use Cms\Models\ImagemCms;
use Illuminate\Http\Request;

use App\Http\Requests;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Intervention\Image\Facades\Image;
use Maatwebsite\Excel\Facades\Excel;

class SerieController extends Controller
{
    
    

    public function __construct()
    {
        $this->serie = new \App\Serie;
        $this->campos = [
            'fonte_id', 'cmsuser_id', 'periodicidade_id', 'unidade', 'indicador'
        ];
        $this->pathImagem = public_path().'/imagens/series';
        $this->sizesImagem = [
            'xs' => ['width' => 140, 'height' => 79],
            'sm' => ['width' => 480, 'height' => 270],
            'md' => ['width' => 580, 'height' => 326],
            'lg' => ['width' => 1170, 'height' => 658]
        ];
        $this->widthOriginal = true;
    }

    function index()
    {

        $fontes = \App\Fonte::lists('titulo', 'id')->all();
        $idiomas = \App\Idioma::lists('titulo', 'sigla')->all();
        $periodicidades = \App\Periodicidade::lists('titulo', 'id')->all();
        $indicadores = \App\Indicador::lists('titulo', 'id')->all();
        $unidades = \App\Unidade::lists('titulo', 'id')->all();

        return view('cms::serie.listar', [
            'fontes' => $fontes,
            'idiomas' => $idiomas,
            'periodicidades' => $periodicidades,
            'indicadores' => $indicadores,
            'unidades' => $unidades,
        ]);
    }

    public function listar(Request $request)
    {
        //Log::info('CAMPOS: '.$request->campos);

        //Log::info($request->dadoPesquisa);

        $campos = explode(", ", $request->campos);

        $series = DB::table('series')
            ->select($campos)
            ->join('textos_series', 'textos_series.serie_id', '=', 'series.id')
            ->where([
                [$request->campoPesquisa, 'ilike', "%$request->dadoPesquisa%"],
            ])
            ->orderBy($request->ordem, $request->sentido)
            ->paginate($request->itensPorPagina);
        return $series;
    }

    public function inserir(Request $request)
    {

        $data = $request->all();

        $data['serie'] += ['cmsuser_id' => auth()->guard('cms')->user()->id];//adiciona id do usuario
        $data['textos'] += ['cmsuser_id' => auth()->guard('cms')->user()->id];//adiciona id do usuario

        /*if(empty($data['serie']['serie_id'])){
            $data['serie']['serie_id'] = 0;
        }*/

        //$data['serie']['tipo_valores'] = 0;

        //verifica se o index do campo existe no array e caso não exista inserir o campo com valor vazio.
        foreach($this->campos as $campo){
            if(!array_key_exists($campo, $data)){
                $data['serie'] += [$campo => ''];
            }
        }

        $file = $request->file('file');

        if($file!=null){
            $filename = rand(1000,9999)."-".clean($file->getClientOriginalName());
            $imagemCms = new ImagemCms();
            $success = $imagemCms->inserir($file, $this->pathImagem, $filename, $this->sizesImagem, $this->widthOriginal);
            
            if($success){
                $data['serie']['imagem'] = $filename;
                return $this->serie->create($data['serie']);
            }else{
                return "erro";
            }
        }

        $serie = $this->serie->create($data['serie']);
        $data['textos'] += ['serie_id' => $serie->id];
        $textos_serie = \App\TextoSerie::create($data['textos']);

    }

    public function detalhar($id)
    {
        $serie = $this->serie
            ->select('textos_series.titulo', 'series.*')
            ->join('textos_series', 'textos_series.serie_id', '=', 'series.id')
            ->where([
            ['series.id', '=', $id],
        ])->firstOrFail();
        $idiomas = \App\Idioma::lists('titulo', 'sigla')->all();
        $periodicidades = \App\Periodicidade::lists('titulo', 'id')->all();

        $fontes = \App\Fonte::lists('titulo', 'id')->all();


        $indicadores = \App\Indicador::lists('titulo', 'id')->all();
        $unidades = \App\Unidade::lists('titulo', 'id')->all();

        return view('cms::serie.detalhar', [
            'serie' => $serie,
            'fontes' => $fontes,
            'idiomas' => $idiomas,
            'periodicidades' => $periodicidades,
            'indicadores' => $indicadores,
            'unidades' => $unidades,
        ]);
    }

    public function alterar(Request $request, $id)
    {
        $data = $request->all();
        $data['serie'] += ['cmsuser_id' => auth()->guard('cms')->user()->id];//adiciona id do usuario

        //verifica se o index do campo existe no array e caso não exista inserir o campo com valor vazio.
        foreach($this->campos as $campo){
            if(!array_key_exists($campo, $data)){
                if($campo!='imagem'){
                    $data['serie'] += [$campo => ''];
                }
            }
        }

	$data['serie']['tipo_valores'] = 0;

        $serie = $this->serie->where([
            ['id', '=', $id],
        ])->firstOrFail();

        $file = $request->file('file');

        if($file!=null){
            $filename = rand(1000,9999)."-".clean($file->getClientOriginalName());
            $imagemCms = new ImagemCms();
            $success = $imagemCms->alterar($file, $this->pathImagem, $filename, $this->sizesImagem, $this->widthOriginal, $serie);
            if($success){
                $data['serie']['imagem'] = $filename;
                $serie->update($data['serie']);
                return $serie->imagem;
            }else{
                return "erro";
            }
        }

        //remover imagem
        if($data['removerImagem']){
            $data['serie']['imagem'] = '';
            if(file_exists($this->pathImagem."/".$serie->imagem)) {
                unlink($this->pathImagem . "/" . $serie->imagem);
            }
        }

        $serie->update($data['serie']);
        return "Gravado com sucesso";
    }

    public function excluir($id)
    {
        //Auth::loginUsingId(2);

        $serie = $this->serie->where([
            ['id', '=', $id],
        ])->firstOrFail();

        //remover imagens        
        if(!empty($serie->imagem)){
            //remover imagens
            $imagemCms = new ImagemCms();
            $imagemCms->excluir($this->pathImagem, $this->sizesImagem, $serie);
        }
                

        $serie->delete();

    }

    public function viewImportar($id){
        $serie = \App\Serie::find($id);
        $textos_series = DB::table('textos_series')->where('serie_id', $id)->first();
        return view('cms::serie.import', ['serie' => $serie, 'textos_series' => $textos_series]);
    }

    public function viewImportarVarias(){
        return view('cms::serie.import-varias');
    }

    private function validarArquivoCsv($arquivo){
        $ext = $arquivo->getClientOriginalExtension();

        return ['result' => $ext=='csv', 'msg' => 'O arquivo deve ser .csv'];

    }

    private function validarArquivo2($serie, $arquivo){
        $ext = $arquivo->getClientOriginalExtension();

        //Log::info($ext);

        if($serie->abrangencia==4){
            return ['result' => $ext=='csv', 'msg' => 'O arquivo deve ser .csv'];
        }

        if($serie->abrangencia==1 || $serie->abrangencia==2 || $serie->abrangencia==3){
            return ['result' => $ext=='xls' || $ext=='xlsx', 'msg' => 'O arquivo deve ser .xls ou .xlsx'];
        }

        return false;
    }

    private function validarArquivo($data, $arquivo){
        $ext = $arquivo->getClientOriginalExtension();

        //Log::info($ext);

        /*if($data['serie']['abrangencia']==4){
            return ['result' => $ext=='csv', 'msg' => 'O arquivo deve ser .csv'];
        }

        if($data['serie']['abrangencia']==1 || $data['serie']['abrangencia']==2 || $data['serie']['abrangencia']==3 || $data['serie']['abrangencia']==7){
            return ['result' => $ext=='csv', 'msg' => 'O arquivo deve ser .csv'];
        }*/

        return ['result' => $ext=='csv', 'msg' => 'O arquivo deve ser .csv'];

        //return false;
    }

    public function importar(Request $request){
        $data = $request->all();

        $arquivo = $request->file('arquivo');

        $filenameArquivo = rand(1000,9999)."-".clean($arquivo->getClientOriginalName());
        $successArquivo = $arquivo->move(public_path()."/import", $filenameArquivo);
        if($successArquivo){
            $data['serie']['arquivo'] = $filenameArquivo;
        }

        ini_set('max_execution_time', 360);



        $validation = $this->validarArquivo($data, $arquivo);

        if(!$validation['result']){
            return ['erro' => 1, 'msg' => $validation['msg']];
        }

        $csv = $this->lerCsv($filenameArquivo);

        if(!array_key_exists('id', $data)){
            $data['id'] = 0;
        }

        $periodicidade_id = null;
        if($data['id']!=0){
            $serie = \App\Serie::select('indicador', 'periodicidade_id')->where('id', $data['id'])->first();
            $periodicidade_id = $serie->periodicidade_id;
        }

        if($data['modelo']==1){
            //$this->importarModelo1($csv, $data['id'], $data['serie']['abrangencia'], $serie->periodicidade_id);
            $this->importarModelo1($csv, $data['id'], $periodicidade_id);
            return 1;
        }

        if($data['modelo']==2){
            $this->importarModelo2($csv, $data['id'], $data['serie']['abrangencia'], $data['serie']['periodicidade']);
            return 2;
        }

        return 0;

    }

    private function lerCsv($filenameArquivo){
        $csv = [];

        $file = fopen(public_path()."/import/$filenameArquivo", "r");

        $cont = 0;
        $columns = [];
        while(!feof($file)){
            $linha = fgets($file, 4096);
            $values = explode(';', $linha);
            if($cont==0){
                foreach($values as $key => $value){
                    $values[$key] = somenteLetrasNumeros(clean($value));
                }
                $columns = $values;
                //Log::info($columns);
            }else{
                $row = [];
                foreach($values as $key => $value){
                    $row[$columns[$key]] = $value;
                }
                array_push($csv, $row);
            }

            $cont++;
        }

        return $csv;
    }

    //private function importarModelo1($csv, $serie_id, $abrangencia, $periodicidade){
    private function importarModelo1($csv, $serie_id, $periodicidade){
        $cms_user_id = auth()->guard('cms')->user()->id;
        //$tipo_regiao = $abrangencia;

        if($serie_id != 0){
            $serieId = $serie_id;
        }

        $lastSerieId = 0;

        foreach($csv as $row){

            $primeira_coluna = 'abrangencia';
            if($serie_id == 0){
                $primeira_coluna = 'serie';
            }

            if($row[$primeira_coluna]==''){
                break;
            }

            if($serie_id == 0){
                $serieId = $row['serie'];
                if($lastSerieId!=$serieId){
                    $lastSerieId = $serieId;
                    $serie = \App\Serie::select('indicador', 'periodicidade_id')->where('id', $serieId)->first();
                    $periodicidade = $serie->periodicidade_id;
                }
            }


            $abrangencia = $row['abrangencia'];
            $tipo_regiao = $abrangencia;

            $regiao_id = $row['cod'];
            if($abrangencia==4){
                if(count($row['cod']) == 6){
                    $regiao_id = $row['cod'].$this->calcula_dv_municipio($row['cod']);
                }

            }

            $valor = "";
            $valor = $row['valor'];

            if($abrangencia==1 || $abrangencia==2 || $abrangencia==3){

                //$coluna_edterritorios = 'edterritorios_nome';
                //if($abrangencia==3){//regiao
                    //$coluna_edterritorios = 'edterritorios_sigla';
                //}

                $coluna_edterritorios = 'edterritorios_sigla';

                $tabelas = $this->getTabelas();
                //$abrangencias = $this->getAbrangencias();

                DB::connection()->enableQueryLog();

                $regiao = DB::table($tabelas[$abrangencia])
                    ->select('edterritorios_codigo as regiao_id')
                    ->where($coluna_edterritorios, 'ilike', $row['cod'])//cod em pais, regiao e uf se refere a sigla
                    ->first();

                //Log::info(DB::getQueryLog());

                $regiao_id = $regiao->regiao_id;//no caso de pais, regiao e uf o regiao id é pego das tabelas do spat

            }

            $periodo = trim($row['periodo']);
            if($periodicidade==2 || $periodicidade==3 || $periodicidade==4){
                $periodo = trim($row['periodo'])."-01";
            }
            if($periodicidade==1){//Anual
                $periodo = trim($row['periodo'])."-01-01";
            }


            $reg =[
                'periodo' => $periodo,
                'tipo_regiao' => $tipo_regiao,
                'regiao_id' => $regiao_id,
                'serie_id' => $serieId
            ];

            Log::info($reg);


            if($regiao_id != null && $regiao_id != "" && $valor != null & $valor != ""){
                $registro = \App\ValorSerie::updateOrCreate(
                    $reg,
                    ['valor' => $valor, 'cmsuser_id' => $cms_user_id]
                );

                Log::info([$registro]);
            }

            //Log::info(DB::getQueryLog());
        }
    }

    private function importarModelo2($csv, $serie_id, $abrangencia, $periodicidade){
        $cms_user_id = auth()->guard('cms')->user()->id;
        //$tipo_regiao = $abrangencia;

        if($serie_id != 0){
            $serieId = $serie_id;
        }

        foreach($csv as $index => $row){
            if($row['cod']==''){
                break;
            }

            $abrangencia = $row['abrangencia'];
            $tipo_regiao = $abrangencia;

            $regiao_id = $row['cod'];
            if($abrangencia==4){
                $regiao_id = $row['cod'].$this->calcula_dv_municipio($row['cod']);
            }

            $cont = 0;
            foreach($row as $col => $value){
                //Log::info($col);
                if($cont >= 2){//os periodos estão a partir da posição 2
                    $periodo = $col;

                    $valor = "";
                    $valor = $row[$periodo];

                    if($abrangencia==1 || $abrangencia==2 || $abrangencia==3){

                        $coluna_edterritorios = 'edterritorios_nome';
                        if($abrangencia==3){//regiao
                            $coluna_edterritorios = 'edterritorios_sigla';
                        }

                        $tabelas = $this->getTabelas();
                        //$abrangencias = $this->getAbrangencias();

                        DB::connection()->enableQueryLog();

                        $regiao = DB::table($tabelas[$abrangencia])
                            ->select('edterritorios_codigo as regiao_id')
                            ->where($coluna_edterritorios, 'ilike', $row['cod'])//cod em pais, regiao e uf se refere a sigla
                            ->first();

                        //Log::info(DB::getQueryLog());

                        $regiao_id = $regiao->regiao_id;//no caso de pais, regiao e uf o regiao id é pego das tabelas do spat

                        //Log::info($regiao_id);

                    }

                    $reg =[
                        'periodo' => $periodo,
                        'tipo_regiao' => $tipo_regiao,
                        'regiao_id' => $regiao_id,
                        'serie_id' => $serieId
                    ];

                    if($regiao_id != null && $regiao_id != "" && $valor != null & $valor != ""){
                        $registro = \App\ValorSerie::updateOrCreate(
                            $reg,
                            ['valor' => $valor, 'cmsuser_id' => $cms_user_id]
                        );
                    }

                }
                $cont++;
            }
        }
    }

    private function calcula_dv_municipio($codigo_municipio){
        $peso = "1212120";
        //echo substr($peso,0,1);
        $soma = 0;
        for($i = 0; $i < 7; $i++){ $valor = substr($codigo_municipio,$i,1) * substr($peso,$i,1); if($valor>9)
            $soma = $soma + substr($valor,0,1) + substr($valor,1,1);
        else
            $soma = $soma + $valor;
        }
        $dv = (10 - ($soma % 10));
        if(($soma % 10)==0)
            $dv = 0;
        return $dv;
    }

    private function getTabelas(){
        $tabelas = [
            1 => 'spat.ed_territorios_paises',
            2 => 'spat.ed_territorios_regioes',
            3 => 'spat.ed_territorios_uf',
            4 => 'spat.ed_territorios_municipios',
            5 => 'spat.ed_territorios_microrregioes',
            6 => 'spat.ed_territorios_mesoregioes',
            7 => 'spat.ed_territorios_piaui_tds'
        ];

        return $tabelas;
    }

    /*private function importarMunicipios($csv, $serie_id, $abrangencia){
        $cms_user_id = auth()->guard('cms')->user()->id;
        $tipo_regiao = $abrangencia;

        foreach($csv as $row){
            if($row['cod']==''){
                break;
            }

            $cod = $row['cod'];
            $cod = $row['cod'].$this->calcula_dv_municipio($row['cod']);

            $valor = "";
            $valor = $row['valor'];

            $reg =[
                'periodo' => $row['periodo'],
                'tipo_regiao' => $tipo_regiao,
                'regiao_id' => $cod,
                'serie_id' => $serie_id,
                'valor' => $valor
            ];

            if($cod != null && $cod != "" && $valor != null & $valor != ""){
                $registro = \App\ValorSerie::updateOrCreate(
                    $reg,
                    ['valor' => $valor, 'cmsuser_id' => $cms_user_id]
                );
            }
        }
    }*/

    /*private function importarPaisUfRegiao($excel, $serie_id, $abrangencia){
        //Log::info('abrangencia: '.$abrangencia);
        $registros = [];
        $uf = '';
        $municipio = '';
        $bairro = '';
        $cms_user_id = auth()->guard('cms')->user()->id;

        $tabelas = $this->getTabelas();
        $abrangencias = $this->getAbrangencias();

        $coluna_edterritorios = 'edterritorios_nome';
        if($abrangencia==3){
            $coluna_edterritorios = 'edterritorios_sigla';
        }

        $territorio = '';
        foreach($excel as $row){
            foreach($row as $index => $cel){
                if(!empty($index)){
                    //Log::info('titulo abrangência: '.$abrangencias[$abrangencia]);
                    //Log::info('index: '.$index);
                    if($index==$abrangencias[$abrangencia]){
                        $territorio = str_replace('-', ' ', $cel);
                    }else{
                        array_push($registros, ['ano' => $index, 'value' => $cel]);
                        $valor = $cel;
                        $periodo = $index;

                        //Log::info('territorio: '.$territorio);

                        DB::connection()->enableQueryLog();

                        $tipo_regiao = $abrangencia;
                        $regiao = DB::table($tabelas[$abrangencia])
                            ->select('edterritorios_codigo as regiao_id')
                            ->where($coluna_edterritorios, 'ilike', $territorio)
                            ->first();

                        //Log::info(DB::getQueryLog());

                        $regiao_id = $regiao->regiao_id;

                        $reg =[
                            'periodo' => $periodo,
                            'uf' => $uf,
                            'tipo_regiao' => $tipo_regiao,
                            'regiao_id' => $regiao_id,
                            'municipio' => $municipio,
                            'bairro' => $bairro,
                            'serie_id' => $serie_id
                        ];

                        //Log::info($valor);

                        if($valor==null){
                            $valor = 0;
                        }


                        if($regiao_id != null && $periodo != null){
                            $registro = \App\ValorSerie::updateOrCreate(
                                $reg,
                                ['valor' => $valor, 'cmsuser_id' => $cms_user_id]
                            );
                        }

                    }
                }
            }
        }
    }*/



























//    public function importar2(Request $request){
//
//        $data = $request->all();
//
//        //return $data;
//
//        $arquivo = $request->file('arquivo');
//
//        $filenameArquivo = rand(1000,9999)."-".clean($arquivo->getClientOriginalName());
//        $successArquivo = $arquivo->move(public_path()."/import", $filenameArquivo);
//        if($successArquivo){
//            $data['serie']['arquivo'] = $filenameArquivo;
//        }
//
//        ini_set('max_execution_time', 360);
//
//        $serie = \App\Serie::select('abrangencia', 'indicador')->where('id', $data['id'])->first();
//
//
//        $validation = $this->validarArquivo($serie, $arquivo);
//
//        if(!$validation['result']){
//            return ['erro' => 1, 'msg' => $validation['msg']];
//        }
//
//
//        if($serie->abrangencia==1 || $serie->abrangencia==2 || $serie->abrangencia==3){
//            $excel = Excel::load(public_path()."/import/$filenameArquivo", function($reader) {})->get();
//            $this->importarPaisUfRegiao2($excel, $data['id'], $serie->abrangencia);
//            return;
//        }
//
//        /*if($data['serie']['abrangencia']==1 || $data['serie']['abrangencia']==2 || $data['serie']['abrangencia']==3){
//            $excel = Excel::load(public_path()."/import/$filenameArquivo", function($reader) {})->get();
//            $this->importarPaisUfRegiao($excel, $data['id'], $serie->abrangencia);
//            return;
//        }*/
//
//        if($serie->abrangencia==4){
//
//            $csv = [];
//
//            $file = fopen(public_path()."/import/$filenameArquivo", "r");
//
//            $cont = 0;
//            $columns = [];
//            while(!feof($file)){
//                $linha = fgets($file, 4096);
//                $values = explode(';', $linha);
//                if($cont==0){
//                    foreach($values as $key => $value){
//                        $values[$key] = somenteLetrasNumeros(clean($value));
//                    }
//                    $columns = $values;
//                    //Log::info($columns);
//                }else{
//                    $row = [];
//                    foreach($values as $key => $value){
//                        $row[$columns[$key]] = $value;
//                    }
//                    array_push($csv, $row);
//                }
//
//                $cont++;
//            }
//
//            //Log::info($csv);
//
//            //return $csv;
//
//            $this->importarMunicipios2($csv, $data['id'], $serie->abrangencia, $serie->indicador, $data['serie']['periodo']);
//            return;
//        }
//
//    }
//
//    private function importarMunicipios2($csv, $serie_id, $abrangencia, $indicador, $periodo){
//        //Log::info('abrangencia: '.$abrangencia);
//        $registros = [];
//        $uf = '';
//        $municipio = '';
//        $bairro = '';
//        $cms_user_id = auth()->guard('cms')->user()->id;
//        $tipo_regiao = $abrangencia;
//
//        //Log::info($csv);
//
//        $tabelas = $this->getTabelas();
//
//        /*$abrangencias = [
//            1 => 'pais',
//            2 => 'regiao',
//            3 => 'uf',
//            4 => 'micro-regiao',
//        ];*/
//
//
//
//        $indicadores = [
//            '1' => 'Quantidade',
//            '2' => 'Taxa por 100 mil Habitantes',
//            '3' => 'Proporção',
//            '4' => 'Taxa Bayesiana'
//        ];
//
//        $coluna_edterritorios = 'edterritorios_nome';
//
//        $cont = 0;
//        foreach($csv as $row){
//            /*if($row['codmun']==''){
//                break;
//            }*/
//            if($row['nome']==''){
//                break;
//            }
//            //Log::info($row);
//            //Log::info('indicador: '.$indicador);
//            //Log::info($row['codmun'].": ".$this->calcula_dv_municipio($row['codmun']));
//
//            $cod = $row['codmun'].$this->calcula_dv_municipio($row['codmun']);
//
//            $valor = 0;
//            if($indicador==1){
//                $valor = $row['homicidios'];
//            }
//            if($indicador==2){
//                $valor = $row['taxa'];//txhomicidio
//            }
//            /*if($indicador==2){
//                $valor = $row['txeb'];
//            }*/
//
//            /*$reg =[
//                'valor' => $valor,
//                'periodo' => $periodo,
//                'uf' => $uf,
//                'tipo_regiao' => $tipo_regiao,
//                'regiao_id' => $cod,
//                'municipio' => $municipio,
//                'bairro' => $bairro,
//                'serie_id' => $serie_id,
//                'cmsuser_id' => $cms_user_id
//            ];
//
//            Log::info($reg['periodo'].' / '.$reg['valor']);
//
//            $registro = \App\ValorSerie::create($reg);*/
//
//            $reg =[
//                'periodo' => $periodo,
//                'uf' => $uf,
//                'tipo_regiao' => $tipo_regiao,
//                'regiao_id' => $cod,
//                'municipio' => $municipio,
//                'bairro' => $bairro,
//                'serie_id' => $serie_id,
//            ];
//
//
//            //Log::info('valor antes: '.$valor.'----');
//            //Log::info('tipo: '.gettype($valor));
//
//           /* if(preg_match('/\\d/', $valor) > 0){
//                Log::info('contém numero');
//            }else{
//                Log::info('não contém numero');
//            }*/
//
//            //$valor = str_replace('<br/>', '', $valor);
//
//            if($valor==null || empty($valor) || !preg_match('/\\d/', $valor) > 0){
//                $valor = 0;
//            }
//
//            //Log::info('cod '.$cod.' valor: '.$valor);
//
//            if($cod != null && $periodo != null){
//                $registro = \App\ValorSerie::updateOrCreate(
//                    $reg,
//                    ['valor' => $valor, 'cmsuser_id' => $cms_user_id]
//                );
//            }
//
//        }
//
//    }
//
//    private function importarPaisUfRegiao2($excel, $serie_id, $abrangencia){
//        //Log::info('abrangencia: '.$abrangencia);
//        $registros = [];
//        $uf = '';
//        $municipio = '';
//        $bairro = '';
//        $cms_user_id = auth()->guard('cms')->user()->id;
//
//        $tabelas = $this->getTabelas();
//        $abrangencias = $this->getAbrangencias();
//
//        $coluna_edterritorios = 'edterritorios_nome';
//        if($abrangencia==3){
//            $coluna_edterritorios = 'edterritorios_sigla';
//        }
//
//        $territorio = '';
//        foreach($excel as $row){
//            foreach($row as $index => $cel){
//                if(!empty($index)){
//                    //Log::info('titulo abrangência: '.$abrangencias[$abrangencia]);
//                    //Log::info('index: '.$index);
//                    if($index==$abrangencias[$abrangencia]){
//                        $territorio = str_replace('-', ' ', $cel);
//                    }else{
//                        array_push($registros, ['ano' => $index, 'value' => $cel]);
//                        $valor = $cel;
//                        $periodo = $index;
//
//                        //Log::info('territorio: '.$territorio);
//
//                        DB::connection()->enableQueryLog();
//
//                        $tipo_regiao = $abrangencia;
//                        $regiao = DB::table($tabelas[$abrangencia])
//                            ->select('edterritorios_codigo as regiao_id')
//                            ->where($coluna_edterritorios, 'ilike', $territorio)
//                            ->first();
//
//                        //Log::info(DB::getQueryLog());
//
//                        $regiao_id = $regiao->regiao_id;
//
//                        //Log::info($regiao->regiao_id);
//
//                        /*$reg =[
//                            'valor' => $valor,
//                            'periodo' => $periodo,
//                            'uf' => $uf,
//                            'tipo_regiao' => $tipo_regiao,
//                            'regiao_id' => $regiao_id,
//                            'municipio' => $municipio,
//                            'bairro' => $bairro,
//                            'serie_id' => $serie_id,
//                            'cmsuser_id' => $cms_user_id
//                        ];
//
//                        $registro = \App\ValorSerie::create($reg);*/
//
//                        $reg =[
//                            'periodo' => $periodo,
//                            'uf' => $uf,
//                            'tipo_regiao' => $tipo_regiao,
//                            'regiao_id' => $regiao_id,
//                            'municipio' => $municipio,
//                            'bairro' => $bairro,
//                            'serie_id' => $serie_id
//                        ];
//
//                        //Log::info($valor);
//
//			if($valor==null){
//				$valor = 0;
//			}
//
//
//                        if($regiao_id != null && $periodo != null){
//                            $registro = \App\ValorSerie::updateOrCreate(
//                                $reg,
//                                ['valor' => $valor, 'cmsuser_id' => $cms_user_id]
//                            );
//                        }
//
//
//
//
//                    }
//                }
//            }
//        }
//    }



    /*private function getAbrangencias(){
        $abrangencias = [
            1 => 'pais',
            2 => 'regiao',
            3 => 'uf',
            //4 => 'micro-regiao',
            4 => 'municipio',
            5 => 'micro-regiao',
            6 => 'meso-regiao',
        ];

        return $abrangencias;
    }*/




}
