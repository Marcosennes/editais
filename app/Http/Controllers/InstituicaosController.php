<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use Prettus\Validator\Contracts\ValidatorInterface;
use Prettus\Validator\Exceptions\ValidatorException;
use App\Http\Requests\InstituicaoCreateRequest;
use App\Http\Requests\InstituicaoUpdateRequest;
use App\Repositories\InstituicaoRepository;
use App\Validators\InstituicaoValidator;

/**
 * Class InstituicaosController.
 *
 * @package namespace App\Http\Controllers;
 */
class InstituicaosController extends Controller
{
    /**
     * @var InstituicaoRepository
     */
    protected $repository;

    /**
     * @var InstituicaoValidator
     */
    protected $validator;

    /**
     * InstituicaosController constructor.
     *
     * @param InstituicaoRepository $repository
     * @param InstituicaoValidator $validator
     */
    public function __construct(InstituicaoRepository $repository, InstituicaoValidator $validator)
    {
        $this->repository = $repository;
        $this->validator  = $validator;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $this->repository->pushCriteria(app('Prettus\Repository\Criteria\RequestCriteria'));
        $instituicaos = $this->repository->all();

        if (request()->wantsJson()) {

            return response()->json([
                'data' => $instituicaos,
            ]);
        }

        return view('instituicaos.index', compact('instituicaos'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  InstituicaoCreateRequest $request
     *
     * @return \Illuminate\Http\Response
     *
     * @throws \Prettus\Validator\Exceptions\ValidatorException
     */
    public function store(InstituicaoCreateRequest $request)
    {
        try {

            $this->validator->with($request->all())->passesOrFail(ValidatorInterface::RULE_CREATE);

            $instituicao = $this->repository->create($request->all());

            $response = [
                'message' => 'Instituicao created.',
                'data'    => $instituicao->toArray(),
            ];

            if ($request->wantsJson()) {

                return response()->json($response);
            }

            return redirect()->back()->with('message', $response['message']);
        } catch (ValidatorException $e) {
            if ($request->wantsJson()) {
                return response()->json([
                    'error'   => true,
                    'message' => $e->getMessageBag()
                ]);
            }

            return redirect()->back()->withErrors($e->getMessageBag())->withInput();
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $instituicao = $this->repository->find($id);

        if (request()->wantsJson()) {

            return response()->json([
                'data' => $instituicao,
            ]);
        }

        return view('instituicaos.show', compact('instituicao'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $instituicao = $this->repository->find($id);

        return view('instituicaos.edit', compact('instituicao'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  InstituicaoUpdateRequest $request
     * @param  string            $id
     *
     * @return Response
     *
     * @throws \Prettus\Validator\Exceptions\ValidatorException
     */
    public function update(InstituicaoUpdateRequest $request, $id)
    {
        try {

            $this->validator->with($request->all())->passesOrFail(ValidatorInterface::RULE_UPDATE);

            $instituicao = $this->repository->update($request->all(), $id);

            $response = [
                'message' => 'Instituicao updated.',
                'data'    => $instituicao->toArray(),
            ];

            if ($request->wantsJson()) {

                return response()->json($response);
            }

            return redirect()->back()->with('message', $response['message']);
        } catch (ValidatorException $e) {

            if ($request->wantsJson()) {

                return response()->json([
                    'error'   => true,
                    'message' => $e->getMessageBag()
                ]);
            }

            return redirect()->back()->withErrors($e->getMessageBag())->withInput();
        }
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $deleted = $this->repository->delete($id);

        if (request()->wantsJson()) {

            return response()->json([
                'message' => 'Instituicao deleted.',
                'deleted' => $deleted,
            ]);
        }

        return redirect()->back()->with('message', 'Instituicao deleted.');
    }
}
