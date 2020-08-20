<?php

namespace App\Http\Controllers;

use App\Entities\EditalFilho;
use App\Entities\EditalTipo;
use App\Http\Requests\edital_filhoCreateRequest;
use App\Repositories\EditalFilhoRepository;
use App\Services\EditalFilhoService;
use Illuminate\Http\Request;

class EditalFilhosController extends Controller
{

    protected $repository;
    protected $service;

    public function __construct(EditalFilhoRepository $repository, EditalFilhoService $service)
    {
        $this->repository   = $repository;
        $this->service      = $service;
    }

    public function salvar(edital_filhoCreateRequest $request)
    {
        $resposta = $this->service->salvar($request);

        session_start();
        $_SESSION['cadastro_anexo']['validacao']  = $resposta['validacao'];
        $_SESSION['cadastro_anexo']['mensagem']   = $resposta['mensagem'];
        
        return redirect()->route('edital.cadastrar');
    }
}
