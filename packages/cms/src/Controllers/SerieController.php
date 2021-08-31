<?php

namespace Cms\Controllers;

use Cms\Models\ImagemCms;
use Illuminate\Http\Request;

use App\Http\Requests;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Intervention\Image\Facades\Image;
use Maatwebsite\Excel\Facades\Excel;
use Phaza\LaravelPostgis\Geometries\Point;

class SerieController extends Controller
{
    
    

    public function __construct()
    {
        set_time_limit(600); // 10 minutos
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
        $this->pathArquivo = public_path().'/arquivos/series';
    }

    function index()
    {
        $lang =  App::getLocale();

        $fontes = \App\Fonte::lists('titulo', 'id')->all();
        $idiomas = \App\Idioma::lists('titulo', 'sigla')->all();
        //$periodicidades = \App\Periodicidade::lists('titulo', 'id')->all();
        //$indicadores = \App\Indicador::lists('titulo', 'id')->all();
        //$unidades = \App\Unidade::lists('titulo', 'id')->all();
        
        $periodicidades = \App\Periodicidade::
            select('idiomas_periodicidades.titulo', 'periodicidades.id')
            ->join('idiomas_periodicidades', 'idiomas_periodicidades.periodicidade_id', '=', 'periodicidades.id')
            ->where('idiomas_periodicidades.idioma_sigla', $lang)
            ->lists('idiomas_periodicidades.titulo', 'periodicidades.id');

        $indicadores = \App\Indicador::
            select('idiomas_indicadores.titulo', 'indicadores.id')
            ->join('idiomas_indicadores', 'idiomas_indicadores.indicador_id', '=', 'indicadores.id')
            ->where('idiomas_indicadores.idioma_sigla', $lang)
            ->lists('idiomas_indicadores.titulo', 'indicadores.id');
        
        $unidades = \App\Unidade::
            select('idiomas_unidades.titulo', 'unidades.id')
            ->join('idiomas_unidades', 'idiomas_unidades.unidade_id', '=', 'unidades.id')
            ->where('idiomas_unidades.idioma_sigla', $lang)
            ->lists('idiomas_unidades.titulo', 'unidades.id');

        $tipos_dados_series = config("constants.TIPOS_DADOS_SERIES");



        return view('cms::serie.listar', [
            'fontes' => $fontes,
            'idiomas' => $idiomas,
            'periodicidades' => $periodicidades,
            'indicadores' => $indicadores,
            'unidades' => $unidades,
            'tipos_dados_series' => $tipos_dados_series,
            'tipo_territorios' => 0,
            'tipo_pontos' => 0,
            'tipo_arquivo' => 0,
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
		['textos_series.idioma_sigla', 'pt_BR'],
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
        $arquivo = $request->file('arquivo');

        $successFile = true;
        if($file!=null){
            $filename = rand(1000,9999)."-".clean($file->getClientOriginalName());
            $imagemCms = new ImagemCms();
            $successFile = $imagemCms->inserir($file, $this->pathImagem, $filename, $this->sizesImagem, $this->widthOriginal);
            if($successFile){
                $data['serie']['imagem'] = $filename;
            }
        }

        $successArquivo = true;
        if($arquivo!=null){
            $filenameArquivo = rand(1000,9999)."-".clean($arquivo->getClientOriginalName());
            $successArquivo = $arquivo->move($this->pathArquivo, $filenameArquivo);
            if($successArquivo){
                $data['serie']['arquivo'] = $filenameArquivo;
            }
        }

        if($successFile && $successArquivo){
            $inserir = $this->serie->create($data['serie']);
            $data['textos']['serie_id'] = $inserir->id;
            $inserir2 = \App\TextoSerie::create($data['textos']);
            return $inserir;
        }else{
            return "erro";
        }

        /*$file = $request->file('file');

        if($file!=null){
            $filename = rand(1000,9999)."-".clean($file->getClientOriginalName());
            $imagemCms = new ImagemCms();
            $success = $imagemCms->inserir($file, $this->pathImagem, $filename, $this->sizesImagem, $this->widthOriginal);
            
            if($success){
                $inserir = $data['serie']['imagem'] = $filename;
                $data['textos'] += ['serie_id' => $inserir->id];
                $textos_serie = \App\TextoSerie::create($data['textos']);
                return $inserir;
            }else{
                return "erro";
            }
        }

        $inserir = $this->serie->create($data['serie']);
        $data['textos'] += ['serie_id' => $inserir->id];
        $textos_serie = \App\TextoSerie::create($data['textos']);
        return $inserir;*/

    }

    public function detalhar($id)
    {

        $lang =  App::getLocale();

        $serie = $this->serie
            ->select('textos_series.titulo', 'series.*')
            ->join('textos_series', 'textos_series.serie_id', '=', 'series.id')
            ->where([
            ['series.id', '=', $id],
        ])->firstOrFail();
        $idiomas = \App\Idioma::lists('titulo', 'sigla')->all();
        //$periodicidades = \App\Periodicidade::lists('titulo', 'id')->all();

        $fontes = \App\Fonte::lists('titulo', 'id')->all();


        //$indicadores = \App\Indicador::lists('titulo', 'id')->all();
        //$unidades = \App\Unidade::lists('titulo', 'id')->all();

        $periodicidades = \App\Periodicidade::
        select('idiomas_periodicidades.titulo', 'periodicidades.id')
            ->join('idiomas_periodicidades', 'idiomas_periodicidades.periodicidade_id', '=', 'periodicidades.id')
            ->where('idiomas_periodicidades.idioma_sigla', $lang)
            ->lists('idiomas_periodicidades.titulo', 'periodicidades.id');

        $indicadores = \App\Indicador::
        select('idiomas_indicadores.titulo', 'indicadores.id')
            ->join('idiomas_indicadores', 'idiomas_indicadores.indicador_id', '=', 'indicadores.id')
            ->where('idiomas_indicadores.idioma_sigla', $lang)
            ->lists('idiomas_indicadores.titulo', 'indicadores.id');

        $unidades = \App\Unidade::
        select('idiomas_unidades.titulo', 'unidades.id')
            ->join('idiomas_unidades', 'idiomas_unidades.unidade_id', '=', 'unidades.id')
            ->where('idiomas_unidades.idioma_sigla', $lang)
            ->lists('idiomas_unidades.titulo', 'unidades.id');

        $tipos_dados_series = config("constants.TIPOS_DADOS_SERIES");

        $tiposDados = $this->tipoDados($serie->tipo_dados);
        $tipo_territorios = $tiposDados['tipo_territorios'];
        $tipo_pontos = $tiposDados['tipo_pontos'];
        $tipo_arquivo = $tiposDados['tipo_arquivo'];

        return view('cms::serie.detalhar', [
            'serie' => $serie,
            'fontes' => $fontes,
            'idiomas' => $idiomas,
            'periodicidades' => $periodicidades,
            'indicadores' => $indicadores,
            'unidades' => $unidades,
            'tipos_dados_series' => $tipos_dados_series,
            'tipo_territorios' => $tipo_territorios,
            'tipo_pontos' => $tipo_pontos,
            'tipo_arquivo' => $tipo_arquivo,
        ]);
    }

    private function tipoDados($tipo_dados){
        switch ($tipo_dados){
            case 0:
                $tipo_territorios = 1;
                $tipo_pontos = 0;
                $tipo_arquivo = 0;
                break;
            case 1:
                $tipo_territorios = 0;
                $tipo_pontos = 1;
                $tipo_arquivo = 0;
                break;
            case 2:
                $tipo_territorios = 1;
                $tipo_pontos = 1;
                $tipo_arquivo = 0;
                break;
            case 3:
                $tipo_territorios = 0;
                $tipo_pontos = 0;
                $tipo_arquivo = 1;
                break;
            case 4:
                $tipo_territorios = 1;
                $tipo_pontos = 0;
                $tipo_arquivo = 1;
                break;
            case 5:
                $tipo_territorios = 0;
                $tipo_pontos = 1;
                $tipo_arquivo = 1;
                break;
            case 6:
                $tipo_territorios = 1;
                $tipo_pontos = 1;
                $tipo_arquivo = 1;
        }

        return ['tipo_territorios' => $tipo_territorios, 'tipo_pontos' => $tipo_pontos, 'tipo_arquivo' => $tipo_arquivo];
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

        $file = $request->file('file');
        $arquivo = $request->file('arquivo');

        //remover imagem
        if($data['removerImagem']){
            $data['serie']['imagem'] = '';
            if(file_exists($this->pathImagem."/".$serie->imagem)) {
                unlink($this->pathImagem . "/" . $serie->imagem);
            }
        }

        if($data['removerArquivo']){
            $data['serie']['arquivo'] = '';
            if(file_exists($this->pathArquivo."/".$serie->arquivo)) {
                unlink($this->pathArquivo . "/" . $serie->arquivo);
            }
        }

        $successFile = true;
        if($file!=null){
            $filename = rand(1000,9999)."-".clean($file->getClientOriginalName());
            $imagemCms = new ImagemCms();
            $successFile = $imagemCms->alterar($file, $this->pathImagem, $filename, $this->sizesImagem, $this->widthOriginal, $serie);
            if($successFile){
                $data['serie']['imagem'] = $filename;
            }
        }
        $successArquivo = true;
        if($arquivo!=null){
            $filenameArquivo = rand(1000,9999)."-".clean($arquivo->getClientOriginalName());
            $successArquivo = $arquivo->move($this->pathArquivo, $filenameArquivo);
            if($successArquivo){
                $data['serie']['arquivo'] = $filenameArquivo;
            }
        }

        if($successFile && $successArquivo){

            $serie->update($data['serie']);
            return $serie->imagem;
        }else{
            return "erro";
        }

        /*if($file!=null){
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
        return "Gravado com sucesso";*/
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

        $tipos_dados_series = config("constants.TIPOS_DADOS_SERIES");


        return view('cms::serie.import-varias', ['tipos_dados_series' => $tipos_dados_series]);
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

        ini_set('max_execution_time', 1800);



        $validation = $this->validarArquivo($data, $arquivo);

        if(!$validation['result']){
            return ['erro' => 1, 'msg' => $validation['msg']];
        }

        $csv = $this->lerCsv($filenameArquivo);

        if($data['tipo_dados'] == 1){
            return $this->importarPontos($csv);
        }

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
                    $values[$key] = somenteLetrasNumeros(clean($value, "_"), "_");
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
                if(strlen($row['cod']) == 6){
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
                $periodo = trim($row['periodo'])."-15";
            }
            if($periodicidade==1){//Anual
                $periodo = trim($row['periodo'])."-01-15";
            }


            $reg =[
                'periodo' => $periodo,
                'tipo_regiao' => $tipo_regiao,
                'regiao_id' => $regiao_id,
                'serie_id' => $serieId
            ];

            //Log::info($reg);


            if($regiao_id != null && $regiao_id != "" && $valor != null & $valor != ""){
                $registro = \App\ValorSerie::updateOrCreate(
                    $reg,
                    ['valor' => $valor, 'cmsuser_id' => $cms_user_id]
                );

                //Log::info([$registro]);
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

    private function importarPontos($csv){
        $cms_user_id = auth()->guard('cms')->user()->id;


        foreach ($csv as $item) {
            //Log::info($item);


            //break;

            //testa se chegou no fim do csv
            if(empty($item['serie'])) {
                break;
            }

            $ponto = new Point($item['lat'], $item['lon']);

            $geovalor = [
                'serie_id' => $item['serie'],
                'ponto' => $ponto,
                'endereco' => $item['endereco'],
                'titulo' => $item['titulo'],
                'data' => $item['data'],
                'hora' => $item['hora'],
                'cmsuser_id' => $cms_user_id
            ];

            //Log::info($geovalor);

            $insertValorGeo = \App\GeoValor::create($geovalor);

            /*$insertValorGeo = new \App\GeoValor();
            $insertValorGeo->serie_id = $item['serie'];
            $insertValorGeo->ponto = new Point($item['lat'], $item['lon']);
            $insertValorGeo->endereco = $item['endereco'];
            $insertValorGeo->data = $item['data'];
            $insertValorGeo->hora = $item['hora'];
            $insertValorGeo->cmsuser_id = $cms_user_id;
            $insertValorGeo->save();*/

            //Log::info('geovalor_id: '.$insertValorGeo->id);

            foreach ($item as $col => $valor) {
                //Log::info($col.': '.$valor);
                $filtro = \App\Filtro::select('id')->where('slug', $col)->first();

                if(!empty($filtro)){

                    $valorFiltroGeo = [
                        'valor_filtro_id' => $valor,
                        'geovalor_id' => $insertValorGeo->id,
                        'cmsuser_id' => $cms_user_id,
                    ];

                    //Log::info($valorFiltroGeo);

                    $insertValorFiltroGeo = \App\GeoValorFiltro::create($valorFiltroGeo);
                }
            }
        }

        return 0;
    }

    private function calcula_dv_municipio($codigo_municipio){
        $peso = "1212120";
        //echo substr($peso,0,1);
        $soma = 0;
        for($i = 0; $i < 7; $i++){

            $valor = (int)substr($codigo_municipio,$i,1) * (int)substr($peso,$i,1); if($valor>9)
            $soma = (int)$soma + (int)substr($valor,0,1) + (int)substr($valor,1,1);
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


    public function valoresFiltrosSerie($id){
        $serie = \App\TextoSerie::select('titulo')
            ->where('serie_id', $id)
            ->where('idioma_sigla', 'pt_BR')
            ->first();

        $valores = \App\ValorFiltro::select('filtros.titulo as filtro', 'filtros.slug', 'valores_filtros.titulo as valor', 'valores_filtros.id as valor_id')
            ->join('filtros', 'filtros.id', '=', 'valores_filtros.filtro_id')
            ->join('filtros_series', 'filtros_series.filtro_id', '=', 'filtros.id')
            ->where('filtros_series.serie_id', $id)
            ->orderBy('filtros.titulo')
            ->orderBy('valores_filtros.id')
            ->get();

        return view('cms::serie.valores-filtros-serie', ['serie' => $serie, 'valores' => $valores]);
    }

    public function atualizarViewsMaterializadasPontos(){
        set_time_limit(1800); // 30 minutos

        DB::statement("REFRESH MATERIALIZED VIEW mvw_series_points_by_region;");
        DB::statement("REFRESH MATERIALIZED VIEW mvw_series_points_by_uf;");        
        

        return view('cms::serie.views-materializadas-pontos');

    }

    public function status($id)
    {
        $tipo_atual = DB::table('series')->where('id', $id)->first();
        $status = $tipo_atual->status == 0 ? 1 : 0;
        DB::table('series')->where('id', $id)->update(['status' => $status]);
    }



}
