<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use Prettus\Validator\Contracts\ValidatorInterface;
use Prettus\Validator\Exceptions\ValidatorException;
use App\Http\Requests\EditalTipoCreateRequest;
use App\Http\Requests\EditalTipoUpdateRequest;
use App\Repositories\EditalTipoRepository;
use App\Validators\EditalTipoValidator;

/**
 * Class EditalTiposController.
 *
 * @package namespace App\Http\Controllers;
 */
class EditalTiposController extends Controller
{
    /**
     * @var EditalTipoRepository
     */
    protected $repository;

    /**
     * @var EditalTipoValidator
     */
    protected $validator;

    /**
     * EditalTiposController constructor.
     *
     * @param EditalTipoRepository $repository
     * @param EditalTipoValidator $validator
     */
    public function __construct(EditalTipoRepository $repository, EditalTipoValidator $validator)
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
        $editalTipos = $this->repository->all();

        if (request()->wantsJson()) {

            return response()->json([
                'data' => $editalTipos,
            ]);
        }

        return view('editalTipos.index', compact('editalTipos'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  EditalTipoCreateRequest $request
     *
     * @return \Illuminate\Http\Response
     *
     * @throws \Prettus\Validator\Exceptions\ValidatorException
     */
    public function store(EditalTipoCreateRequest $request)
    {
        try {

            $this->validator->with($request->all())->passesOrFail(ValidatorInterface::RULE_CREATE);

            $editalTipo = $this->repository->create($request->all());

            $response = [
                'message' => 'EditalTipo created.',
                'data'    => $editalTipo->toArray(),
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
        $editalTipo = $this->repository->find($id);

        if (request()->wantsJson()) {

            return response()->json([
                'data' => $editalTipo,
            ]);
        }

        return view('editalTipos.show', compact('editalTipo'));
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
        $editalTipo = $this->repository->find($id);

        return view('editalTipos.edit', compact('editalTipo'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  EditalTipoUpdateRequest $request
     * @param  string            $id
     *
     * @return Response
     *
     * @throws \Prettus\Validator\Exceptions\ValidatorException
     */
    public function update(EditalTipoUpdateRequest $request, $id)
    {
        try {

            $this->validator->with($request->all())->passesOrFail(ValidatorInterface::RULE_UPDATE);

            $editalTipo = $this->repository->update($request->all(), $id);

            $response = [
                'message' => 'EditalTipo updated.',
                'data'    => $editalTipo->toArray(),
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
                'message' => 'EditalTipo deleted.',
                'deleted' => $deleted,
            ]);
        }

        return redirect()->back()->with('message', 'EditalTipo deleted.');
    }
}
