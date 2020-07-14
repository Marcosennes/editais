<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use Prettus\Validator\Contracts\ValidatorInterface;
use Prettus\Validator\Exceptions\ValidatorException;
use App\Http\Requests\EditalFilhoCreateRequest;
use App\Http\Requests\EditalFilhoUpdateRequest;
use App\Repositories\EditalFilhoRepository;
use App\Validators\EditalFilhoValidator;

/**
 * Class EditalFilhosController.
 *
 * @package namespace App\Http\Controllers;
 */
class EditalFilhosController extends Controller
{
    /**
     * @var EditalFilhoRepository
     */
    protected $repository;

    /**
     * @var EditalFilhoValidator
     */
    protected $validator;

    /**
     * EditalFilhosController constructor.
     *
     * @param EditalFilhoRepository $repository
     * @param EditalFilhoValidator $validator
     */
    public function __construct(EditalFilhoRepository $repository, EditalFilhoValidator $validator)
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
        $editalFilhos = $this->repository->all();

        if (request()->wantsJson()) {

            return response()->json([
                'data' => $editalFilhos,
            ]);
        }

        return view('editalFilhos.index', compact('editalFilhos'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  EditalFilhoCreateRequest $request
     *
     * @return \Illuminate\Http\Response
     *
     * @throws \Prettus\Validator\Exceptions\ValidatorException
     */
    public function store(EditalFilhoCreateRequest $request)
    {
        try {

            $this->validator->with($request->all())->passesOrFail(ValidatorInterface::RULE_CREATE);

            $editalFilho = $this->repository->create($request->all());

            $response = [
                'message' => 'EditalFilho created.',
                'data'    => $editalFilho->toArray(),
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
        $editalFilho = $this->repository->find($id);

        if (request()->wantsJson()) {

            return response()->json([
                'data' => $editalFilho,
            ]);
        }

        return view('editalFilhos.show', compact('editalFilho'));
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
        $editalFilho = $this->repository->find($id);

        return view('editalFilhos.edit', compact('editalFilho'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  EditalFilhoUpdateRequest $request
     * @param  string            $id
     *
     * @return Response
     *
     * @throws \Prettus\Validator\Exceptions\ValidatorException
     */
    public function update(EditalFilhoUpdateRequest $request, $id)
    {
        try {

            $this->validator->with($request->all())->passesOrFail(ValidatorInterface::RULE_UPDATE);

            $editalFilho = $this->repository->update($request->all(), $id);

            $response = [
                'message' => 'EditalFilho updated.',
                'data'    => $editalFilho->toArray(),
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
                'message' => 'EditalFilho deleted.',
                'deleted' => $deleted,
            ]);
        }

        return redirect()->back()->with('message', 'EditalFilho deleted.');
    }
}
