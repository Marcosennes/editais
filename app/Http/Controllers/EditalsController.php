<?php

namespace App\Http\Controllers;

use App\Entities\Edital;
use App\Entities\EditalFilho;
use App\Entities\EditalTipo;
use App\Http\Requests\editalCreateRequest;
use App\Repositories\EditalRepository;
use App\Services\EditalService;
use Illuminate\Http\Request;


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
            'tipo_selecionado'          => $tipos[0]->id,
            'editais'                   => $editais,
            'editais_com_anexo'         => json_encode($editais_com_anexo),
            'anexos'                    => $anexos,
        ]);
    }

    public function cadastrar()
    {
        $anos           = $this->service->ordenaAno();
        $tipos          = $this->service->tipos();
        $instituicoes   = $this->service->instituicoes();
        
        return view('edital.cadastrar',[
            'anos'          => $anos,
            'tipos'         => $tipos,
            'instituicoes'  => $instituicoes,
        ]);
    }

    public function salvar(editalCreateRequest $request)
    {
        $resposta = $this->service->salvar($request);

        session()->flash('cadastro',[
            'mensagem'  => $resposta['mensagem'],
            'validacao' => $resposta['validacao'],
        ]);
        
        /*
        $_SESSION['cadastro']['validacao']  = $resposta['validacao'];
        $_SESSION['cadastro']['mensagem']   = $resposta['mensagem'];
        */
        
        return redirect()->route('edital.cadastrar', [

            'resposta' => $resposta,
        ]);
    }

    public function filtrar($instituicao_id, $ano, $tipo_id)
    {
        $editais = $this->service->filtrar($instituicao_id, $ano, $tipo_id);
        $anexos = $this->service->anexos($instituicao_id, $ano, $tipo_id);
        $editais_com_anexo = $this->service->editaisComAnexo($instituicao_id, $ano, $tipo_id);
        $anos   = $this->service->ordenaAnoPorInstituicao($instituicao_id); 
        $tipos  = $this->service->tiposSelecionados($instituicao_id, $ano);
        $instituicoes  = $this->service->instituicoes();

        if(!(isset($editais[0]))){
            if(isset($tipos[0])){
                $tipo_id            = $tipos[0]->id;

                $editais            = $this->service->filtrar($instituicao_id, $ano, $tipo_id);
                $anexos             = $this->service->anexos($instituicao_id, $ano, $tipo_id);
                $editais_com_anexo  = $this->service->editaisComAnexo($instituicao_id, $ano, $tipo_id);        
            }
            else if(isset($anos[0])){

                $ano                = $anos[(sizeof($anos) -1)]->ano;
                $tipos              = $this->service->tiposSelecionados($instituicao_id, $ano);
                $tipo_id            = $tipos[0]->id;

                $editais            = $this->service->filtrar($instituicao_id, $ano, $tipo_id);
                $anexos             = $this->service->anexos($instituicao_id, $ano, $tipo_id);
                $editais_com_anexo  = $this->service->editaisComAnexo($instituicao_id, $ano, $tipo_id);        
            }
            else{
                $instituicao_id     = 1;
                $ano                = 2019;
                $tipo_id            = 1;
                
                $anos               = $this->service->ordenaAnoPorInstituicao($instituicao_id); 
                $tipos              = $this->service->tiposSelecionados($instituicao_id, $ano);
                $editais            = $this->service->filtrar($instituicao_id, $ano, $tipo_id);
                $anexos             = $this->service->anexos($instituicao_id, $ano, $tipo_id);
                $editais_com_anexo  = $this->service->editaisComAnexo($instituicao_id, $ano, $tipo_id);        
            }
        }

        /*
            $json = json_enconde($produtos);
            return response()->json($json);
        */

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

    public function filtrarPost(Request $request)
    {
        $editais = $this->service->filtrar($request->get('instituicao_id'), $request->get('ano'), $request->get('tipo_id'));
        $anexos = $this->service->anexos($request->get('instituicao_id'), $request->get('ano'), $request->get('tipo_id'));
        $editais_com_anexo = $this->service->editaisComAnexo($request->get('instituicao_id'), $request->get('ano'), $request->get('tipo_id'));
        $anos   = $this->service->ordenaAnoPorInstituicao($request->get('instituicao_id')); 
        $tipos  = $this->service->tiposSelecionados($request->get('instituicao_id'), $request->get('ano'));
        $instituicoes  = $this->service->instituicoes();

        $resposta = [
            'anos'                      => $anos,
            'tipos'                     => $tipos,
            'instituicoes'              => $instituicoes,
            'instituicao_selecionada'   => $request->get('instituicao_id'),
            'ano_selecionado'           => $request->get('ano'),
            'tipo_selecionado'          => $request->get('tipo_id'),
            'editais'                   => $editais,
            'editais_com_anexo'         => json_encode($editais_com_anexo),
            'anexos'                    => $anexos,
        ];

        json_encode($resposta);
        return response()->json($resposta);
    }

    public function filtrarAnexo(Request $request)
    {

        $editais = $this->service->filtrar($request->get('instituicao_id'),$request->get('ano'), $request->get('tipo'));
        
        echo json_encode($editais);
        return;
    }
}
