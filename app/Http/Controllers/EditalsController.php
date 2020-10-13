<?php

namespace App\Http\Controllers;

use App\Entities\Edital;
use App\Entities\EditalFilho;
use App\Entities\EditalTipo;
use App\Http\Requests\editalCreateRequest;
use App\Repositories\EditalRepository;
use App\Services\EditalService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EditalsController extends Controller
{
    protected $repository;
    protected $service;

    public function __construct(EditalRepository $repository, EditalService $service)
    {
        $this->repository   = $repository;
        $this->service      = $service;
    }

    public function index()
    {
        $instituicoes       = $this->service->instituicoes();
        $anos               = $this->service->ordenaAnoPorInstituicao(1); 
        $ano_selecionado    = $this->service->maiorAno($anos);
        $tipos              = $this->service->tiposSelecionados(1, $ano_selecionado);
        $editais            = $this->service->filtrar(1, $ano_selecionado, $tipos[0]->id);
        $anexos             = $this->service->anexos(1,$ano_selecionado,$tipos[0]->id);
        $editais_com_anexo  = $this->service->editaisComAnexo(1, $ano_selecionado, $tipos[0]->id);

        return view('edital.index', [
            'instituicoes'              => $instituicoes,
            'anos'                      => $anos,
            'tipos'                     => $tipos,
            'instituicao_selecionada'   => 1,
            'ano_selecionado'           => $ano_selecionado,
            'tipo_selecionado'          => $tipos[0]->id,   //Seleciona o primeiro tipo disponível
            'editais'                   => $editais,
            'editais_com_anexo'         => json_encode($editais_com_anexo),
            'anexos'                    => $anexos,
        ]);
    }

    public function cadastrar()
    {
        $instituicoes                       = $this->service->instituicoes();
        $anos                               = $this->service->ordenaAno();
        $anos_tipos_instituicoes            = array();
        $tipos                              = $this->service->tipos();

        for($i = 0; $i < sizeof($instituicoes); $i++){
            $anos_tipos_instituicoes[$i]    = $this->service->ordenaAnoTipoPorInstituicao($instituicoes[$i]->id);
        }
        
        return view('edital.cadastrar',[
            'anos'                      => $anos,
            'anos_tipos_instituicoes'   => $anos_tipos_instituicoes,
            'tipos'                     => $tipos,
            'instituicoes'              => $instituicoes,
        ]);
    }

    public function salvar(editalCreateRequest $request)
    {
        $resposta = $this->service->salvar($request);

        /*
        session()->flash('cadastro',[
            'mensagem'  => $resposta['mensagem'],
            'validacao' => $resposta['validacao'],
        ]);
        */
        
        session_start();

        $_SESSION['cadastro']['validacao']  = $resposta['validacao'];
        $_SESSION['cadastro']['mensagem']   = $resposta['mensagem'];
        
        
        return redirect()->route('edital.cadastrar');
    }

    public function excluir(Request $request)
    {
        $resposta = $this->service->excluir($request->get('id'));

        $_SESSION['exclusao_edital']['validacao']  = $resposta['validacao'];
        $_SESSION['exclusao_edital']['mensagem']   = $resposta['mensagem'];

        return redirect()->route('edital.cadastrar');
    }
        //função filtrarPost é similar mas utilizando o método Post. A função filtrar não está sendo utilizada  
    /*
    public function filtrar($instituicao_id, $ano, $tipo_id)
    {
        $editais            = $this->service->filtrar($instituicao_id, $ano, $tipo_id);
        $anexos             = $this->service->anexos($instituicao_id, $ano, $tipo_id);
        $editais_com_anexo  = $this->service->editaisComAnexo($instituicao_id, $ano, $tipo_id);
        $anos               = $this->service->ordenaAnoPorInstituicao($instituicao_id); 
        $tipos              = $this->service->tiposSelecionados($instituicao_id, $ano);
        $instituicoes       = $this->service->instituicoes();

        if(!(isset($editais[0]))){  //não existe nenhum edital com os filtros recebidos (instituicao, ano e tipo)
            if(isset($tipos[0])){   //existe algum tipo no ano recebido (instituicao, ano)
                $tipo_id            = $tipos[0]->id;    //a página exibira o primeiro tipo disponível daquele ano
                $editais            = $this->service->filtrar($instituicao_id, $ano, $tipo_id);
                $anexos             = $this->service->anexos($instituicao_id, $ano, $tipo_id);
                $editais_com_anexo  = $this->service->editaisComAnexo($instituicao_id, $ano, $tipo_id);        
            }
            else if(isset($anos[0])){   //existe pelo menos um ano na instituicao recebida
                $ano                = $anos[(sizeof($anos) -1)]->ano;   //é selecionado o último ano daquela instituicao
                $tipos              = $this->service->tiposSelecionados($instituicao_id, $ano);
                $tipo_id            = $tipos[0]->id;
                $editais            = $this->service->filtrar($instituicao_id, $ano, $tipo_id);
                $anexos             = $this->service->anexos($instituicao_id, $ano, $tipo_id);
                $editais_com_anexo  = $this->service->editaisComAnexo($instituicao_id, $ano, $tipo_id);        
            }
            else{
                return abort(404);  
            }
        }
 
            // $json = json_enconde($produtos);
            // return response()->json($json);
 
        // O próprio Laravel já retorna em json
        return view('edital.index',[
            'instituicoes'              => $instituicoes,
            'anos'                      => $anos,
            'tipos'                     => $tipos,
            'instituicao_selecionada'   => $instituicao_id,
            'ano_selecionado'           => $ano,
            'tipo_selecionado'          => $tipo_id,
            'editais'                   => $editais,
            'editais_com_anexo'         => json_encode($editais_com_anexo),
            'anexos'                    => $anexos,
        ]);
    }
    */

    public function filtrarAnexo(Request $request)
    {

        $editais = $this->service->filtrar($request->get('instituicao_id'),$request->get('ano'), $request->get('tipo'));
        
        echo json_encode($editais);
        return;
    }
    
    //função utilizada no select de instituição da index. A diferença é que esta recebe somente a instituicao
    //Além disso, ela retorna a view pois não é uma requisição ajax
    public function filtrarInstituicao(Request $request)
    {
        $anos               = $this->service->ordenaAnoPorInstituicao($request->get('instituicao_id_filtro')); 
        if(!isset($anos[0])){
            return abort(404);
        }
        $ano_selecionado    = $anos[(sizeof($anos) -1)]->ano;   //é selecionado o último ano daquela instituicao
        $tipos              = $this->service->tiposSelecionados($request->get('instituicao_id_filtro'), $ano_selecionado);
        $tipo_selecionado   = $tipos[0]->id;    //a página exibirá o primeiro tipo disponível daquele ano
        
        $editais            = $this->service->filtrar($request->get('instituicao_id_filtro'), $ano_selecionado, $tipo_selecionado);
        $anexos             = $this->service->anexos($request->get('instituicao_id_filtro'), $ano_selecionado, $tipo_selecionado);
        $editais_com_anexo  = $this->service->editaisComAnexo($request->get('instituicao_id_filtro'), $ano_selecionado, $tipo_selecionado);
        $instituicoes       = $this->service->instituicoes();   
        
        return view('edital.index',[
            'instituicoes'              => $instituicoes,
            'anos'                      => $anos,
            'tipos'                     => $tipos,
            'instituicao_selecionada'   => $request->get('instituicao_id_filtro'),
            'ano_selecionado'           => $ano_selecionado,
            'tipo_selecionado'          => $tipo_selecionado,
            'editais'                   => $editais,
            'editais_com_anexo'         => json_encode($editais_com_anexo),
            'anexos'                    => $anexos,
        ]);
    }
    
    //função utilizada para filtrar por instituicao, ano e tipo
    //retorna um json para a requisição ajax
    public function filtrarPost(Request $request)
    {
        $editais            = $this->service->filtrar($request->get('instituicao_id'), $request->get('ano'), $request->get('tipo_id'));
        $anexos             = $this->service->anexos($request->get('instituicao_id'), $request->get('ano'), $request->get('tipo_id'));
        $editais_com_anexo  = $this->service->editaisComAnexo($request->get('instituicao_id'), $request->get('ano'), $request->get('tipo_id'));
        $anos               = $this->service->ordenaAnoPorInstituicao($request->get('instituicao_id')); 
        $tipos              = $this->service->tiposSelecionados($request->get('instituicao_id'), $request->get('ano'));
        $instituicoes       = $this->service->instituicoes();
        
        if(!(isset($editais[0]))){  //não existe nenhum edital com os filtros recebidos (instituicao, ano e tipo)
            if(isset($tipos[0])){   //existe algum tipo no ano recebido (instituicao, ano)
                $tipo_id            = $tipos[0]->id;    //a página exibirá o primeiro tipo disponível daquele ano
                $tipo_selecionado   = $tipo_id;

                $editais            = $this->service->filtrar($request->get('instituicao_id'), $request->get('ano'), $tipo_id);
                $anexos             = $this->service->anexos($request->get('instituicao_id'), $request->get('ano'), $tipo_id);
                $editais_com_anexo  = $this->service->editaisComAnexo($request->get('instituicao_id'), $request->get('ano'), $tipo_id);        
            }
            else if(isset($anos[0])){   //existe pelo menos um ano na instituicao recebida
                $ano                = $anos[(sizeof($anos) -1)]->ano;   //é selecionado o último ano daquela instituicao
                $ano_selecionado    = $ano;
                $tipos              = $this->service->tiposSelecionados($request->get('instituicao_id'), $ano);
                $tipo_id            = $tipos[0]->id;
                
                $editais            = $this->service->filtrar($request->get('instituicao_id'), $ano, $tipo_id);
                $anexos             = $this->service->anexos($request->get('instituicao_id'), $ano, $tipo_id);
                $editais_com_anexo  = $this->service->editaisComAnexo($request->get('instituicao_id'), $ano, $tipo_id);        
            }
            else{
                //return abort(404);  
            }
        }

        $resposta = [
            'instituicoes'              => $instituicoes,
            'anos'                      => $anos,
            'tipos'                     => $tipos,
            'instituicao_selecionada'   => $request->get('instituicao_id'),
            'ano_selecionado'           => isset($ano_selecionado) ? $ano_selecionado : $request->get('ano'),
            'tipo_selecionado'          => isset($tipo_selecionado)? json_encode($tipo_selecionado) : $request->get('tipo_id'),
            'editais'                   => $editais,
            'editais_com_anexo'         => $editais_com_anexo,
            'anexos'                    => $anexos,
        ];
        
        // echo json_encode($resposta);
        // return;
        return response()->json($resposta);
    }
    
}
