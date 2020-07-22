<?php

namespace App\Http\Controllers;

use App\Entities\Edital;
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
        $editais = Edital::where('tipo_id', '=', 1)
                            ->where('ano', '=', 2019)
                            ->select('nome', 'arquivo')
                            ->get();

        $anos   = $this->service->ordenaAno(); 
        $tipos  = $this->service->tipos();

        return view('edital.index',[
            'anos'  => $anos,
            'tipos' => $tipos,
            'tipo_selecionado' => 1,
            'ano_selecionado' => 2019,
            'editais' => $editais,
        ]);
    }

    public function cadastrar()
    {
        $anos   = $this->service->ordenaAno(); 
        $tipos  = $this->service->tipos();
        return view('edital.cadastrar',[
            'anos'  => $anos,
            'tipos' => $tipos,
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

    public function filtrarPorAno($ano)
    {
        $editaisFiltrados = $this->service->filtraPorAno($ano);

        $anos   = $this->service->ordenaAno(); 
        $tipos  = $this->service->tipos();

        return view('edital.index',[
            'anos'  => $anos,
            'tipos' => $tipos,
            'tipo_selecionado' => 1,
            'ano_selecionado' => $ano,
            'editaisFiltrados' => $editaisFiltrados,
        ]);
    }
    
    public function filtrarPorTipo($ano_selecionado, $tipo)
    {
        $editaisFiltrados = $this->service->filtraPorTipo($ano_selecionado, $tipo);

        $anos   = $this->service->ordenaAno(); 
        $tipos  = $this->service->tipos();

        return view('edital.index',[
            'anos'  => $anos,
            'tipos' => $tipos,
            'tipo_selecionado' => $tipo,
            'ano_selecionado' => $ano_selecionado,
            'editaisFiltrados' => $editaisFiltrados,
        ]);
    }

    public function filtrarPorTipoAnexo(Request $request)
    {

        $editaisFiltrados = $this->service->filtraPorTipo($request->get('ano'), $request->get('tipo'));
        $anos   = $this->service->ordenaAno(); 
        $tipos  = $this->service->tipos();

        return view('edital.cadastrar',[
            'anos'  => $anos,
            'tipos' => $tipos,
            'editaisFiltrados' => $editaisFiltrados,
        ]);
    }
}
