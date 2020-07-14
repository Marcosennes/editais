<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use Prettus\Validator\Contracts\ValidatorInterface;
use Prettus\Validator\Exceptions\ValidatorException;
use App\Http\Requests\EditalCreateRequest;
use App\Http\Requests\EditalUpdateRequest;
use App\Repositories\EditalRepository;
use App\Services\EditalService;


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


    public function salvar(EditalCreateRequest $request)
    {
        dd($request);

        $request = $this->service->salvar($request->all());

        return redirect()->route('user.index');
    }
}
