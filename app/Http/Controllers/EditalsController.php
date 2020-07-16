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

        return view('edital.index');
    }

    public function salvar(editalCreateRequest $request)
    {
        $resposta = $this->service->salvar($request);

        session()->flash('cadastro',[
            'mensagem'  => $resposta['mensagem'],
            'validacao' => $resposta['validacao'],
        ]);

        return redirect()->route('edital.index');
    }
}
