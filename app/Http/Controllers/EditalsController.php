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
        $editais = $this->service->filtrar(1, 2019, 1);
        $anexos = $this->service->anexos(1,2019,1);
        $editais_com_anexo = $this->service->editaisComAnexo(1, 2019, 1);;
        $anos   = $this->service->ordenaAno(); 
        $tipos  = $this->service->tipos();
        $instituicoes  = $this->service->instituicoes();

        return view('edital.index',[
            'anos'                      => $anos,
            'tipos'                     => $tipos,
            'instituicoes'              => $instituicoes,
            'instituicao_selecionada'   => 1,
            'ano_selecionado'           => 2019,
            'tipo_selecionado'          => 1,
            'editais'                   => $editais,
            'editais_com_anexos'        => $editais_com_anexo,
            'anexos'                    => $anexos,
        ]);
    }

    public function cadastrar()
    {
        $anos   = $this->service->ordenaAno(); 
        $tipos  = $this->service->tipos();
        $instituicoes  = $this->service->instituicoes();
        
        return view('edital.cadastrar',[
            'anos'  => $anos,
            'tipos' => $tipos,
            'instituicoes' => $instituicoes,
        ]);
    }

    public function salvar(editalCreateRequest $request)
    {
        $resposta = $this->service->salvar($request);

        session()->flash('cadastro',[
            'mensagem'  => $resposta['mensagem'],
            'validacao' => $resposta['validacao'],
        ]);

        return redirect()->route('edital.cadastrar');
    }

    public function filtrar($instituition_id, $ano, $tipo_id)
    {
        $editais = $this->service->filtrar($instituition_id, $ano, $tipo_id);
        $anexos = $this->service->anexos($instituition_id, $ano, $tipo_id);
        $editais_com_anexo = $this->service->editaisComAnexo($instituition_id, $ano, $tipo_id);
        $anos   = $this->service->ordenaAno(); 
        $tipos  = $this->service->tipos();
        $instituicoes  = $this->service->instituicoes();
        
        
        return view('edital.index',[
            'anos'                      => $anos,
            'tipos'                     => $tipos,
            'instituicoes'              => $instituicoes,
            'instituicao_selecionada'   => $instituition_id,
            'ano_selecionado'           => $ano,
            'tipo_selecionado'          => $tipo_id,
            'editais'                   => $editais,
            'editais_com_anexos'        => $editais_com_anexo,
            'anexos'                    => $anexos,
        ]);
    }

    public function filtrarAnexo(Request $request)
    {

        $editais = $this->service->filtrar($request->get('instituicao_id'),$request->get('ano'), $request->get('tipo'));
        
        echo json_encode($editais);
        return;
    }
}
