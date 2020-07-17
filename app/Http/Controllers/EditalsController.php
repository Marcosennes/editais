<?php

namespace App\Http\Controllers;

use App\Entities\Edital;
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
        $anos = array('2015', '2016', '2017', '2018', '2019', '2020');
        return view('edital.index',[
            'anos' => $anos,
        ]);
    }

    public function cadastrar()
    {
        $editais      = $this->repository->all();

        return view('edital.cadastrar');
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
}
