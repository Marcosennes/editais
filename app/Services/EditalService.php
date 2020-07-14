<?php

namespace App\Services;

use App\Repositories\EditalRepository;
use App\Validators\EditalValidator;
use Exception;
use Illuminate\Database\QueryException;
use Prettus\Validator\Contracts\ValidatorInterface;

class EditalService{
    
    private $repository;
    private $validator;

    public function __construct(EditalRepository $repository,EditalValidator $validator)
    {
        $this->repository = $repository;
        $this->validator = $validator;
    }

    public function salvar($data)
    {
        $aux_data =  
        [
            'nome'          => $data['nome'],
            'arquivo'      => $data['arquivo'],
            'ano'           => $data['ano'],
            'instituicao'   => $data['instituicao'],
            'tipo'          => $data['tipo'],
        ];


       /*
        * Dessa forma se a busca retornar sem nenhum usuário com o mesmo email, o resultado não é null
        * 
        * $email_verification = DB::table('users')
        *       ->where('email', '=', $data['email'])
        *       ->select('id')
        *       ->get();
        */

        $email_verification = $this->repository->findWhere(['email' => $data['email']])->first();
        $cpf_verification   = $this->repository->findWhere(['cpf'   => $data['cpf']])->first();

        try
        {
            if($email_verification)
            {
                return 
                [
                    'messages'              => "Email já cadastrado no sistema",
                    'confirm_validation'    => false,
                ];
            }
            else if($cpf_verification)
            {
                return 
                [
                    'messages'              => "CPF já cadastrado no sistema",
                    'confirm_validation'    => false,
                ];
            }
            else
            {
                $this->validator->with($data)->passesOrFail(ValidatorInterface::RULE_CREATE);
                $usuario = $this->repository->create($aux_data);
    
                return 
                [
                    'messages'              => "Usuário cadastrado",
                    'confirm_validation'    => true,
                ];
            }
        }
        catch(Exception $e)
        {
            switch(get_class($e))
            {    
                case QueryException::class     : return ['success' => false, 'messages' => $e->getMessage()];
                case Exception::class          : return ['success' => false, 'messages' => $e->getMessage()];
                default                        : return ['success' => false, 'messages' => get_class($e)];
            }
        }
    }

    public function update($data, $id)
    {
        try
        {
            $this->validator->with($data)->passesOrFail(ValidatorInterface::RULE_UPDATE);
            $usuario = $this->repository->update($data, $id);

            return [
                'success'  => 'true',
                'messages' => "Usuário Atualizado",
                'data'=> $usuario,
            ];
        }
        catch(Exception $e){

            switch(get_class($e)){
                
                case QueryException::class     : return ['success' => false, 'messages' => $e->getMessage()];
                case Exception::class          : return ['success' => false, 'messages' => $e->getMessage()];
                default                        : return ['success' => false, 'messages' => get_class($e)];
            }
        }    
    }

    public function destroy($user_id){

        try
        {
            $this->repository->delete($user_id);

            return [
                'success'   => 'true',
                'messages'  => "Usuário removido",
                'data'      => null,
            ];
        }
        catch(Exception $e)
        {
            switch(get_class($e))
            {
                case QueryException::class     : return ['success' => false, 'messages' => $e->getMessage()];
                case Exception::class          : return ['success' => false, 'messages' => $e->getMessage()];
                default                        : return ['success' => false, 'messages' => get_class($e)];
            }
        }
    }
}