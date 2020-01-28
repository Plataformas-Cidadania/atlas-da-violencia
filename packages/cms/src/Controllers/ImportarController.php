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

class ImportarController extends Controller
{

    public function __construct()
    {
        set_time_limit(600); // 10 minutos
        $this->importar = new \App\Importar;
        $this->campos = [
            'fonte_id', 'cmsuser_id', 'periodicidade_id', 'unidade', 'indicador'
        ];
        $this->pathImagem = public_path().'/imagens/importars';
        $this->sizesImagem = [
            'xs' => ['width' => 140, 'height' => 79],
            'sm' => ['width' => 480, 'height' => 270],
            'md' => ['width' => 580, 'height' => 326],
            'lg' => ['width' => 1170, 'height' => 658]
        ];
        $this->widthOriginal = true;
    }



    public function viewImportar($id){
        $importar = \App\Importar::find($id);
        $textos_importars = DB::table('textos_importars')->where('importar_id', $id)->first();
        return view('cms::importar.import', ['importar' => $importar, 'textos_importars' => $textos_importars]);
    }

    public function viewImportarVarias(){

        $tipos_dados_importars = config("constants.TIPOS_DADOS_IMPORTARS");


        return view('cms::importar.import-varias', ['tipos_dados_importars' => $tipos_dados_importars]);
    }

    private function validarArquivoCsv($arquivo){
        $ext = $arquivo->getClientOriginalExtension();

        return ['result' => $ext=='csv', 'msg' => 'O arquivo deve ser .csv'];

    }


    private function validarArquivo($data, $arquivo){
        $ext = $arquivo->getClientOriginalExtension();

        return ['result' => $ext=='csv', 'msg' => 'O arquivo deve ser .csv'];
    }

    public function importar(Request $request){
        $data = $request->all();

        $arquivo = $request->file('arquivo');

        $filenameArquivo = rand(1000,9999)."-".clean($arquivo->getClientOriginalName());
        $successArquivo = $arquivo->move(public_path()."/import", $filenameArquivo);

        ini_set('max_execution_time', 1800);

        $validation = $this->validarArquivo($data, $arquivo);

        if(!$validation['result']){
            return ['erro' => 1, 'msg' => $validation['msg']];
        }

        $csv = $this->lerCsv($filenameArquivo);

        if($data['tipo_dados'] == 0){
            return $this->importarEstacoes($csv);
        }

        return 0;

    }


    private function importarEstacoes($csv){
        $cms_user_id = auth()->guard('cms')->user()->id;

        foreach ($csv as $item) {

            if(empty($item['transporte_id'])) {
                break;
            }

            $estacao = [
                'transporte_id' => $item['transporte_id'],
                'titulo' => str_replace('"', '', $item['titulo']),
                'subtitulo' => $item['subtitulo'],
                'resumida' => $item['resumida'],
                'descricao' => $item['descricao'],
                'data_inicio' => $item['data_inicio'] == '' ? null : $item['data_inicio'],
                'data_termino' => $item['data_termino'] == '' ? null : $item['data_termino'],
                'endereco' => $item['endereco'],
                'telefone' => $item['telefone'],
                'ativo' => $item['ativo'],
                'status' => $item['status'],
                'latitude' => $item['latitude'],
                'longitude' => $item['longitude'],
                'uf' => $item['uf'],
                'cod_municipio' => $item['cod_municipio'],
                'cmsuser_id' => $cms_user_id,
            ];



            $insertEstacao = \App\Estacao::create($estacao);

            $integracoes = explode(',', $item['integracoes']);
            foreach ($integracoes as $integracao) {

                $arrayIntegracao = explode(':', $integracao);


                if(!empty($arrayIntegracao[1])){
                    $slugIntegracao = $arrayIntegracao[0];
                    $existeIntegracao = (int)$arrayIntegracao[1];
                    if($existeIntegracao){
                        $insertIntegracao = \App\Integracao::create([
                            'transporte_id' => \App\Transporte::where('slug', $slugIntegracao)->first()->id,
                            'estacao_id' => $insertEstacao->id
                        ]);
                    }
                }


            }

            $linhasEstacoes = explode(',', $item['linhas_estacoes']);
            foreach ($linhasEstacoes as $linhaEstacao) {
                $arraylinhaEstacao = explode(':', $linhaEstacao);


                if(!empty($arraylinhaEstacao[1])) {

                    $sluglinhaEstacao = $arraylinhaEstacao[0];
                    $existelinhaEstacao = (int)$arraylinhaEstacao[1];
                    if ($existelinhaEstacao) {
                        $insertlinhaEstacao = \App\LinhasEstacoes::create([
                            'linha_id' => \App\Linha::where('slug', $sluglinhaEstacao)->first()->id,
                            'estacao_id' => $insertEstacao->id
                        ]);
                    }
                }
            }
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


    ///////////////////////////////////////////////
    public function csvMetro(){        
        $api = json_decode(file_get_contents('https://opendata.arcgis.com/datasets/7a0b22723c5a458faaae79f046163504_19.geojson'), true);

        $data = [];

        foreach ($api['features'] as $feature) {
            $prop = $feature['properties'];
            $geo = $feature['geometry'];
            $row = [];
            $row['titulo'] = $prop['Nome'];
            $row['subtitulo'] = null;
            $row['resumida'] = null;
            $row['descricao'] = null;
            $row['data_inicio'] = null;
            $row['data_termino'] = null;
            $row['endereco'] = null;
            $row['telefone'] = null;
            $row['ativo'] = $prop['Flg_Ativa'] == 'Não' ? 0 : 1;
            $row['status'] = $prop['Status'] == 'Não' ? 0 : 1;
            $row['latitude'] = $geo['coordinates'][1];
            $row['longitude'] = $geo['coordinates'][0];
            $row['uf'] = 'RJ';
            $row['cod_municipio'] = 33;
            $row['transporte_id'] = 1;

            $row['integracoes'] = '';
            $row['integracoes'] .= 'bicicletario:'.($prop['Flg_Bicicletario'] == 'Sim' ? 1 : 0).',';
            $row['integracoes'] .= 'trem:'.($prop['Integra_Trem'] == 'Sim' ? 1 : 0).',';
            $row['integracoes'] .= 'onibus:'.($prop['Integra_Onibus'] == 'Sim' ? 1 : 0).',';
            $row['integracoes'] .= 'brt:'.($prop['Integra_BRT'] == 'Sim' ? 1 : 0).',';
            $row['integracoes'] .= 'vlt:'.($prop['Integra_VLT'] == 'Sim' ? 1 : 0);

            $row['linhas_estacoes'] = '';
            $row['linhas_estacoes'] .= 'linha1:'.($prop['Flg_Linha1'] == 'Sim' ? 1 : 0).',';
            $row['linhas_estacoes'] .= 'linha2:'.($prop['Flg_Linha2'] == 'Sim' ? 1 : 0).',';
            $row['linhas_estacoes'] .= 'linha4:'.($prop['Flg_Linha4'] == 'Sim' ? 1 : 0);


            array_push($data, $row);
        }

        return $this->gerarCsv($data, 'metro.csv');
    }

    public function csvBrt(){
        $api = json_decode(file_get_contents('https://opendata.arcgis.com/datasets/f753941f44a749d4987f1111aa6486b3_22.geojson'), true);

        $data = [];

        foreach ($api['features'] as $feature) {
            $prop = $feature['properties'];
            $geo = $feature['geometry'];
            $row = [];
            $row['titulo'] = $prop['Nome'];
            $row['subtitulo'] = null;
            $row['resumida'] = null;
            $row['descricao'] = null;
            $row['data_inicio'] = null;
            $row['data_termino'] = null;
            $row['endereco'] = null;
            $row['telefone'] = null;
            $row['ativo'] = $prop['Flg_Ativo'] == 'Não' ? 0 : 1;
            $row['status'] = $prop['Status'] == 'Implantada' ? 1 : 0;
            $row['latitude'] = $geo['coordinates'][1];
            $row['longitude'] = $geo['coordinates'][0];
            $row['uf'] = 'RJ';
            $row['cod_municipio'] = 33;
            $row['transporte_id'] = 2;

            $row['integracoes'] = '';
            $row['integracoes'] .= 'aeroporto:'.($prop['Integra_Aeroporto'] == 'Sim' ? 1 : 0).',';
            $row['integracoes'] .= 'trem:'.($prop['Integra_Trem'] == 'Sim' ? 1 : 0).',';
            $row['integracoes'] .= 'metro:'.($prop['Integra_Metro'] == 'Sim' ? 1 : 0);

            $row['linhas_estacoes'] = '';
            $row['linhas_estacoes'] .= 'carioca:'.($prop['Flg_TransCarioca'] == 'Sim' ? 1 : 0).',';
            $row['linhas_estacoes'] .= 'brasil:'.($prop['Flg_TransBrasil'] == 'Sim' ? 1 : 0).',';
            $row['linhas_estacoes'] .= 'oeste:'.($prop['Flg_TransOeste'] == 'Sim' ? 1 : 0).',';
            $row['linhas_estacoes'] .= 'olimpica:'.($prop['Flg_TransOlimpica'] == 'Sim' ? 1 : 0);

            array_push($data, $row);
        }

        return $this->gerarCsv($data, 'brt.csv');
    }

    public function csvVlt(){
        $api = json_decode(file_get_contents('https://opendata.arcgis.com/datasets/29cc383d2d344e8387dda153ec0d545d_10.geojson'), true);

        $data = [];

        foreach ($api['features'] as $feature) {
            $prop = $feature['properties'];
            $geo = $feature['geometry'];
            $row = [];
            $row['titulo'] = $prop['Nome'];
            $row['subtitulo'] = null;
            $row['resumida'] = null;
            $row['descricao'] = null;
            $row['data_inicio'] = null;
            $row['data_termino'] = null;
            $row['endereco'] = null;
            $row['telefone'] = null;
            $row['ativo'] = $prop['Em_operação'] == 'Não' ? 0 : 1;
            $row['status'] = 0;
            $row['latitude'] = $geo['coordinates'][1];
            $row['longitude'] = $geo['coordinates'][0];
            $row['uf'] = 'RJ';
            $row['cod_municipio'] = 33;
            $row['transporte_id'] = 3;

            $row['integracoes'] = '';
            $row['integracoes'] .= 'metro:'.($prop['Integra_Metro'] == 'Sim' ? 1 : 0).',';
            $row['integracoes'] .= 'barca:'.($prop['Integra_Barcas'] == 'Sim' ? 1 : 0).',';
            $row['integracoes'] .= 'onibus:'.($prop['Integra_Onibus'] == 'Sim' ? 1 : 0).',';
            $row['integracoes'] .= 'trem:'.($prop['Integra_Trem'] == 'Sim' ? 1 : 0).',';
            $row['integracoes'] .= 'teleferico:'.($prop['Integra_Teleferico'] == 'Sim' ? 1 : 0).',';
            $row['integracoes'] .= 'aeroporto:'.($prop['Integra_Aeroporto'] == 'Sim' ? 1 : 0);

            $row['linhas_estacoes'] = '';
            $row['linhas_estacoes'] .= 'linha1:'.($prop['Linha_1'] == 'Sim' ? 1 : 0).',';
            $row['linhas_estacoes'] .= 'linha2:'.($prop['Linha_2'] == 'Sim' ? 1 : 0).',';
            $row['linhas_estacoes'] .= 'linha3:'.($prop['Linha_3'] == 'Sim' ? 1 : 0);

            array_push($data, $row);
        }

        return $this->gerarCsv($data, 'vlt.csv');
    }

    public function csvTrem(){
        $api = json_decode(file_get_contents('https://opendata.arcgis.com/datasets/8c9f264be1e946b1b49cf4c198bd5e46_16.geojson'), true);

        $data = [];

        foreach ($api['features'] as $feature) {
            $prop = $feature['properties'];
            $geo = $feature['geometry'];
            $row = [];
            $row['titulo'] = $prop['Nome'];
            $row['subtitulo'] = null;
            $row['resumida'] = null;
            $row['descricao'] = null;
            $row['data_inicio'] = null;
            $row['data_termino'] = null;
            $row['endereco'] = null;
            $row['telefone'] = null;
            $row['ativo'] = $prop['Flg_Ativa'] == 'Não' ? 0 : 1;
            $row['status'] = $prop['Status'] == 'Implantada' ? 1 : 0;
            $row['latitude'] = $geo['coordinates'][1];
            $row['longitude'] = $geo['coordinates'][0];
            $row['uf'] = 'RJ';
            $row['cod_municipio'] = 33;
            $row['transporte_id'] = 4;

            $row['integracoes'] = '';
            $row['integracoes'] .= 'metro:'.($prop['Flg_IntegraMetro'] == 'Sim' ? 1 : 0).',';
            $row['integracoes'] .= 'onibus:'.($prop['Flg_IntegraOnibus'] == 'Sim' ? 1 : 0).',';
            $row['integracoes'] .= 'teleferico:'.($prop['Flg_IntegraTele'] == 'Sim' ? 1 : 0).',';
            $row['integracoes'] .= 'brt:'.($prop['Integra_BRT'] == 'Sim' ? 1 : 0);

            $row['linhas_estacoes'] = '';
            $row['linhas_estacoes'] .= 'santa_cruz:'.($prop['Flg_SantaCruz'] == 'Sim' ? 1 : 0).',';
            $row['linhas_estacoes'] .= 'paracambi:'.($prop['Flg_Paracambi'] == 'Sim' ? 1 : 0).',';
            $row['linhas_estacoes'] .= 'vila_inhomirim:'.($prop['Flg_VilaInhomirim'] == 'Sim' ? 1 : 0).',';
            $row['linhas_estacoes'] .= 'guapimirim:'.($prop['Flg_Guapimirim'] == 'Sim' ? 1 : 0).',';
            $row['linhas_estacoes'] .= 'saracuruna:'.($prop['Flg_Saracuruna'] == 'Sim' ? 1 : 0).',';
            $row['linhas_estacoes'] .= 'belford_roxo:'.($prop['Flg_BelfordRoxo'] == 'Sim' ? 1 : 0).',';
            $row['linhas_estacoes'] .= 'deodoro:'.($prop['Flg_Deodoro'] == 'Sim' ? 1 : 0).',';
            $row['linhas_estacoes'] .= 'japeri:'.($prop['Flg_Japeri'] == 'Sim' ? 1 : 0);


            array_push($data, $row);
        }

        return $this->gerarCsv($data, 'trem.csv');
    }

    public function csvBarca(){
        $api = json_decode(file_get_contents('https://opendata.arcgis.com/datasets/a1ca34c5510342cf9b056c6aaeb341c8_1.geojson'), true);

        $data = [];

        foreach ($api['features'] as $feature) {
            $prop = $feature['properties'];
            $geo = $feature['geometry'];
            $row = [];
            $row['titulo'] = $prop['Nome'];
            $row['subtitulo'] = null;
            $row['resumida'] = null;
            $row['descricao'] = null;
            $row['data_inicio'] = null;
            $row['data_termino'] = null;
            $row['endereco'] = $prop['Endereco'];
            $row['telefone'] = $prop['Telefone'];
            $row['ativo'] = $prop['Flg_Ativa'] == 'Não' ? 0 : 1;
            $row['status'] = $prop['Status'] == 'Implantada' ? 1 : 0;
            $row['latitude'] = $geo['coordinates'][1];
            $row['longitude'] = $geo['coordinates'][0];
            $row['uf'] = 'RJ';
            $row['cod_municipio'] = 33;
            $row['transporte_id'] = 5;

            $row['integracoes'] = null;
            $row['linhas_estacoes'] = null;



            array_push($data, $row);
        }

        return $this->gerarCsv($data, 'barca.csv');
    }

    public function csvAeroporto(){
        $api = json_decode(file_get_contents('https://opendata.arcgis.com/datasets/7876913025c445048c44099ac4c11df7_3.geojson'), true);

        $data = [];

        foreach ($api['features'] as $feature) {
            $prop = $feature['properties'];
            $geo = $feature['geometry'];
            $row = [];
            $row['titulo'] = $prop['Nome'];
            $row['subtitulo'] = null;
            $row['resumida'] = $prop['Categoria'];
            $row['descricao'] = null;
            $row['data_inicio'] = null;
            $row['data_termino'] = null;
            $row['endereco'] = $prop['Endereco'];
            $row['telefone'] = $prop['Telefone'];
            $row['ativo'] = $prop['Flg_Ativa'] == 'Não' ? 0 : 1;
            $row['status'] = $prop['Status'] == 'Implantada' ? 1 : 0;
            $row['latitude'] = $geo['coordinates'][1];
            $row['longitude'] = $geo['coordinates'][0];
            $row['uf'] = 'RJ';
            $row['cod_municipio'] = 33;
            $row['transporte_id'] = 6;

            $row['integracoes'] = null;
            $row['linhas_estacoes'] = null;



            array_push($data, $row);
        }

        return $this->gerarCsv($data, 'aeroporto.csv');
    }
     

    public function csvBonde(){
        $api = json_decode(file_get_contents('https://opendata.arcgis.com/datasets/b424e9356bd246e89d0e12deedf88536_12.geojson'), true);

        $data = [];

        foreach ($api['features'] as $feature) {
            $prop = $feature['properties'];
            $geo = $feature['geometry'];
            $row = [];
            $row['titulo'] = $prop['Nome'];
            $row['subtitulo'] = $prop['Trecho'];
            $row['resumida'] = null;
            $row['descricao'] = null;
            $row['data_inicio'] = null;
            $row['data_termino'] = null;
            $row['endereco'] = null;
            $row['telefone'] = null;
            $row['ativo'] = $prop['Flg_Ativa'] == 'Não' ? 0 : 1;
            $row['status'] = $prop['Status'] == 'Implantada' ? 1 : 0;
            $row['latitude'] = $geo['coordinates'][1];
            $row['longitude'] = $geo['coordinates'][0];
            $row['uf'] = 'RJ';
            $row['cod_municipio'] = 33;
            $row['transporte_id'] = 7;

            $row['integracoes'] = null;
            $row['linhas_estacoes'] = null;



            array_push($data, $row);
        }

        return $this->gerarCsv($data, 'bonde.csv');
    }


    public function csvTeleferico(){
        $api = json_decode(file_get_contents('https://opendata.arcgis.com/datasets/000eaa25775b4227a4cd93716d27429a_6.geojson'), true);

        $data = [];

        foreach ($api['features'] as $feature) {
            $prop = $feature['properties'];
            $geo = $feature['geometry'];
            $row = [];
            $row['titulo'] = $prop['Nome'];
            $row['subtitulo'] = null;
            $row['resumida'] = null;
            $row['descricao'] = null;
            $row['data_inicio'] = $prop['Data_Inc'];
            $row['data_termino'] = null;
            $row['endereco'] = null;
            $row['telefone'] = null;
            $row['ativo'] = $prop['Flg_Ativa'] == 'Não' ? 0 : 1;
            $row['status'] = $prop['Status'] == 'Implantada' ? 1 : 0;
            $row['latitude'] = $geo['coordinates'][1];
            $row['longitude'] = $geo['coordinates'][0];
            $row['uf'] = 'RJ';
            $row['cod_municipio'] = 33;
            $row['transporte_id'] = 8;

           $row['integracoes'] = '';
           $row['integracoes'] .= 'metro:'.($prop['Integra_Metro'] == 'Sim' ? 1 : 0).',';
            $row['integracoes'] .= 'trem:'.($prop['Integra_Trem'] == 'Sim' ? 1 : 0).',';
            $row['integracoes'] .= 'vlt:'.($prop['Integra_VLT'] == 'Sim' ? 1 : 0);

           $row['linhas_estacoes'] = '';
           $row['linhas_estacoes'] .= 'alemao:'.($prop['Flg_Alemao'] == 'Sim' ? 1 : 0).',';
           $row['linhas_estacoes'] .= 'providencia:'.($prop['Flg_Providencia'] == 'Sim' ? 1 : 0);


            array_push($data, $row);
        }

        return $this->gerarCsv($data, 'teleferico.csv');
    }


    public function csvBicicletario(){
        $api = json_decode(file_get_contents('https://opendata.arcgis.com/datasets/55d5fde287144084ba9d0c17c3d99dad_0.geojson'), true);

        $data = [];

        foreach ($api['features'] as $feature) {
            $prop = $feature['properties'];
            $geo = $feature['geometry'];
            $row = [];
            $row['titulo'] = $prop['Nome'];
            $row['subtitulo'] = null;
            $row['resumida'] = null;
            $row['descricao'] = null;
            $row['data_inicio'] = null;
            $row['data_termino'] = null;
            $row['endereco'] = null;
            $row['telefone'] = null;
            $row['ativo'] = 1;
            $row['status'] = 1;
            $row['latitude'] = $geo['coordinates'][1];
            $row['longitude'] = $geo['coordinates'][0];
            $row['uf'] = 'RJ';
            $row['cod_municipio'] = 33;
            $row['transporte_id'] = 9;

            $row['integracoes'] = null;
            $row['linhas_estacoes'] = null;



            array_push($data, $row);
        }

        return $this->gerarCsv($data, 'bicicletario.csv');
    }


    private function gerarCsv($data, $name){
        // create a file pointer connected to the output stream
        $file = fopen(storage_path().'/app/csv/estacoes/'.$name, 'w');

        // send the column headers
        fputcsv($file, array('titulo', 'subtitulo', 'resumida', 'descricao', 'data_inicio', 'data_termino', 'endereco', 'telefone', 'ativo', 'status', 'latitude', 'longitude', 'uf', 'cod_municipio', 'transporte_id', 'integracoes', 'linhas_estacoes'), ';');

        // output each row of the data
        foreach ($data as $row){
            fputcsv($file, $row, ';');
        }

        return $data;
    }
    ///////////////////////////////////////////////



}
