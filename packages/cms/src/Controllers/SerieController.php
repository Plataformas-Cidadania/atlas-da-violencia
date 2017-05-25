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
            'imagem', 'titulo', 'descricao', 'serie_id', 'tema_id', 'fonte_id', 'cmsuser_id', 'idioma_sigla', 'periodicidade_id', 'unidade', 'indicador', 'abrangencia', 'tipo_valores'
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

        $series = \App\Serie::all();
        //$temas = \App\Tema::lists('titulo', 'id')->all();
        $fontes = \App\Fonte::lists('titulo', 'id')->all();
        $series_relacionado = \App\Serie::lists('titulo', 'id')->all();
        $idiomas = \App\Idioma::lists('titulo', 'sigla')->all();
        $periodicidades = \App\Periodicidade::lists('titulo', 'id')->all();



        return view('cms::serie.listar', [
            'series' => $series,
            'series_relacionado' => $series_relacionado,
            'fontes' => $fontes,
            'idiomas' => $idiomas,
            'periodicidades' => $periodicidades
        ]);
    }

    public function listar(Request $request)
    {
        //Log::info('CAMPOS: '.$request->campos);

        Log::info($request->dadoPesquisa);

        $campos = explode(", ", $request->campos);

        $series = DB::table('series')
            ->select($campos)
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


        if(empty($data['serie']['serie_id'])){
            $data['serie']['serie_id'] = 0;
        }

        $data['serie']['tipo_valores'] = 0;

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

        return $this->serie->create($data['serie']);

    }

    public function detalhar($id)
    {
        $serie = $this->serie->where([
            ['id', '=', $id],
        ])->firstOrFail();
        $idiomas = \App\Idioma::lists('titulo', 'sigla')->all();
        $periodicidades = \App\Periodicidade::lists('titulo', 'id')->all();

        $fontes = \App\Fonte::lists('titulo', 'id')->all();
        $series_relacionado = \App\Serie::lists('titulo', 'id')->all();

        return view('cms::serie.detalhar', [
            'serie' => $serie,
            'series_relacionado' => $series_relacionado,
            'fontes' => $fontes,
            'idiomas' => $idiomas,
            'periodicidades' => $periodicidades
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
        return view('cms::serie.import', ['serie' => $serie]);
    }

    private function validarArquivo($serie, $arquivo){
        $ext = $arquivo->getClientOriginalExtension();

        Log::info($ext);

        if($serie->abrangencia==4){
            return ['result' => $ext=='csv', 'msg' => 'O arquivo deve ser .csv'];
        }

        if($serie->abrangencia==1 || $serie->abrangencia==2 || $serie->abrangencia==3){
            return ['result' => $ext=='xls' || $ext=='xlsx', 'msg' => 'O arquivo deve ser .xls ou .xlsx'];
        }

        return false;
    }

    public function importar(Request $request){

        $data = $request->all();

        //return $data;

        $arquivo = $request->file('arquivo');

        $filenameArquivo = rand(1000,9999)."-".clean($arquivo->getClientOriginalName());
        $successArquivo = $arquivo->move(public_path()."/import", $filenameArquivo);
        if($successArquivo){
            $data['serie']['arquivo'] = $filenameArquivo;
        }

        ini_set('max_execution_time', 360);

        $serie = \App\Serie::select('abrangencia', 'indicador')->where('id', $data['id'])->first();


        $validation = $this->validarArquivo($serie, $arquivo);

        if(!$validation['result']){
            return ['erro' => 1, 'msg' => $validation['msg']];
        }


        if($serie->abrangencia==1 || $serie->abrangencia==2 || $serie->abrangencia==3){
            $excel = Excel::load(public_path()."/import/$filenameArquivo", function($reader) {})->get();
            $this->importarPaisUfRegiao($excel, $data['id'], $serie->abrangencia);
            return;
        }

        /*if($data['serie']['abrangencia']==1 || $data['serie']['abrangencia']==2 || $data['serie']['abrangencia']==3){
            $excel = Excel::load(public_path()."/import/$filenameArquivo", function($reader) {})->get();
            $this->importarPaisUfRegiao($excel, $data['id'], $serie->abrangencia);
            return;
        }*/

        if($serie->abrangencia==4){

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
                    Log::info($columns);
                }else{
                    $row = [];
                    foreach($values as $key => $value){
                        $row[$columns[$key]] = $value;
                    }
                    array_push($csv, $row);
                }

                $cont++;
            }

            //Log::info($csv);

            //return $csv;

            $this->importarMunicipios($csv, $data['id'], $serie->abrangencia, $serie->indicador, $data['serie']['periodo']);
            return;
        }

    }

    private function importarMunicipios($csv, $serie_id, $abrangencia, $indicador, $periodo){
        //Log::info('abrangencia: '.$abrangencia);
        $registros = [];
        $uf = '';
        $municipio = '';
        $bairro = '';
        $cms_user_id = auth()->guard('cms')->user()->id;
        $tipo_regiao = $abrangencia;

        $tabelas = $this->getTabelas();

        $abrangencias = [
            1 => 'pais',
            2 => 'regiao',
            3 => 'uf',
            4 => 'micro-regiao',
        ];

        $indicadores = [
            '1' => 'Quantidade',
            '2' => 'Taxa por 100 mil Habitantes',
            '3' => 'Proporção',
            '4' => 'Taxa Bayesiana'
        ];

        $coluna_edterritorios = 'edterritorios_nome';

        $cont = 0;
        foreach($csv as $row){
            if($row['codmun']==''){
                break;
            }
            //Log::info($row);
            //Log::info('indicador: '.$indicador);
            //Log::info($row['codmun'].": ".$this->calcula_dv_municipio($row['codmun']));

            $cod = $row['codmun'].$this->calcula_dv_municipio($row['codmun']);

            $valor = 0;
            if($indicador==1){
                $valor = $row['homicidios'];
            }
            if($indicador==2){
                $valor = $row['taxa'];//txhomicidio
            }
            /*if($indicador==2){
                $valor = $row['txeb'];
            }*/

            /*$reg =[
                'valor' => $valor,
                'periodo' => $periodo,
                'uf' => $uf,
                'tipo_regiao' => $tipo_regiao,
                'regiao_id' => $cod,
                'municipio' => $municipio,
                'bairro' => $bairro,
                'serie_id' => $serie_id,
                'cmsuser_id' => $cms_user_id
            ];

            Log::info($reg['periodo'].' / '.$reg['valor']);

            $registro = \App\ValorSerie::create($reg);*/

            $reg =[
                'periodo' => $periodo,
                'uf' => $uf,
                'tipo_regiao' => $tipo_regiao,
                'regiao_id' => $cod,
                'municipio' => $municipio,
                'bairro' => $bairro,
                'serie_id' => $serie_id,
            ];


            //Log::info('valor antes: '.$valor.'----');
            //Log::info('tipo: '.gettype($valor));

           /* if(preg_match('/\\d/', $valor) > 0){
                Log::info('contém numero');
            }else{
                Log::info('não contém numero');
            }*/

            //$valor = str_replace('<br/>', '', $valor);

            if($valor==null || empty($valor) || !preg_match('/\\d/', $valor) > 0){
                $valor = 0;
            }

            Log::info('valor: '.$valor);

            if($cod != null && $periodo != null){
                $registro = \App\ValorSerie::updateOrCreate(
                    $reg,
                    ['valor' => $valor, 'cmsuser_id' => $cms_user_id]
                );
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

    private function importarPaisUfRegiao($excel, $serie_id, $abrangencia){
        Log::info('abrangencia: '.$abrangencia);
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
                    Log::info('titulo abrangência: '.$abrangencias[$abrangencia]);
                    Log::info('index: '.$index);
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

                        //Log::info($regiao->regiao_id);

                        /*$reg =[
                            'valor' => $valor,
                            'periodo' => $periodo,
                            'uf' => $uf,
                            'tipo_regiao' => $tipo_regiao,
                            'regiao_id' => $regiao_id,
                            'municipio' => $municipio,
                            'bairro' => $bairro,
                            'serie_id' => $serie_id,
                            'cmsuser_id' => $cms_user_id
                        ];

                        $registro = \App\ValorSerie::create($reg);*/

                        $reg =[
                            'periodo' => $periodo,
                            'uf' => $uf,
                            'tipo_regiao' => $tipo_regiao,
                            'regiao_id' => $regiao_id,
                            'municipio' => $municipio,
                            'bairro' => $bairro,
                            'serie_id' => $serie_id
                        ];

                        Log::info($valor);
			
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
    }

    private function getTabelas(){
        $tabelas = [
            1 => 'spat.ed_territorios_paises',
            2 => 'spat.ed_territorios_regioes',
            3 => 'spat.ed_territorios_uf',
            4 => 'spat.ed_territorios_municipios',
            5 => 'spat.ed_territorios_microrregioes',
            6 => 'spat.ed_territorios_mesoregioes'
        ];

        return $tabelas;
    }

    private function getAbrangencias(){
        $abrangencias = [
            1 => 'pais',
            2 => 'regiao',
            3 => 'uf',
            4 => 'micro-regiao',
        ];

        return $abrangencias;
    }

    public function testeExcel($serie_id, $arquivo){
        //$data = Excel::load(public_path().'/excel/taxa.homicidio.ANO.REGIAO.xlsx', function($reader) {
        Log::info($arquivo);
        $data = Excel::load(public_path()."/excel/$arquivo", function($reader) {

        })->get();

        //return $data;

        //dd($data);

        $registros = [];
        $valor = 0;
        $periodo = '';
        $uf = '';
        $municipio = '';
        $bairro = '';
        //$serie_id = 14;
        $cms_user_id = 1;


        foreach($data as $row){
            foreach($row as $index => $cel){
                if(!empty($index)){
                    if($index=='uf'){
                        $uf = $cel;
                    }else{
                        array_push($registros, ['uf' => $uf, 'ano' => $index, 'value' => $cel]);
                        $valor = $cel;
                        $periodo = $index;

                        $tipo_regiao = 2;
                        $regiao = DB::table('ed_territorios_uf')
                            ->select('edterritorios_codigo as regiao_id')
                            ->where('edterritorios_sigla', $uf)
                            ->first();

                        $regiao_id = $regiao->regiao_id;

                        Log::info($regiao->regiao_id);

                        $reg =[
                            'valor' => $valor,
                            'periodo' => $periodo,
                            'uf' => $uf,
                            'tipo_regiao' => $tipo_regiao,
                            'regiao_id' => $regiao_id,
                            'municipio' => $municipio,
                            'bairro' => $bairro,
                            'serie_id' => $serie_id,
                            'cmsuser_id' => $cms_user_id
                        ];

                        $registro = \App\ValorSerie::create($reg);


                    }
                }
            }
        }

        /*foreach($data['RowCollection']->items as $row){
            foreach($row['RowCollection']->items as $index => $cel){
                if($index != ''){
                    if($index=='uf'){
                        $uf = $cel;
                    }else{
                        array_push($registros, ['uf' => $uf, 'ano' => $index, 'value' => $cel]);
                    }
                }
            }
        }*/

        dd($registros);
    }

    


}
