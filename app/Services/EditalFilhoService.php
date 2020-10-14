<?php

namespace App\Services;

use App\Entities\Edital;
use App\Entities\EditalFilho;
use App\Entities\EditalTipo;
use App\Repositories\EditalFilhoRepository;
use App\Validators\EditalFilhoValidator;
use Exception;
use Illuminate\Database\QueryException;
use Prettus\Validator\Contracts\ValidatorInterface;
use Prettus\Validator\Exceptions\ValidatorException;
use App\Scripts\EditalClass;

//Métodos comuns a editais e editais filhos estão implementados na classe abstrata extendida a baixo
class EditalFilhoService extends EditalClass{
    
    private $repository;
    private $validator;

    public function __construct(EditalFilhoRepository $repository,EditalFilhoValidator $validator)
    {
        $this->repository = $repository;
        $this->validator = $validator;
    }

    public function salvar($data)
    {
        if(isset($data['pai_id'])){
        
            $ano = Edital::where('id', '=', $data['pai_id'])
                         ->select('ano')
                         ->get();
    
            $arquivo_nome = $this->formata($data['nome']) . '_' . 'anexo_' . $ano[0]->ano .'.pdf';
            
            $verificacao_arquivo = $this->verificaArquivo($data->file('arquivo'), $arquivo_nome);
    
            if($verificacao_arquivo['validacao'] != true)
            {
                return['mensagem'  => $verificacao_arquivo['mensagem'], 'validacao' => $verificacao_arquivo['validacao']];                
            }
    
            try
            {
            $arquivo_nome_verification = $this->repository->findWhere(['arquivo' => $arquivo_nome])->first();
                
            if($arquivo_nome_verification)                                  //Existe algum arquivo com o mesmo nome
            {                                    
                return['mensagem'  => "Já existe arquivo com esse nome. Mude o nome do edital.", 'validacao' => false];                
            }else                                                       //Não existe um arquivo com esse nome no banco
            {
                $data->file('arquivo')->storeAs('editais', $arquivo_nome);  //Salvo o arquivo
    
                if(empty($data['nome'])){
                    return ['mensagem'  => "Nome não pode ser vazio", 'validacao' => false];
                }
                if(empty($data['pai_id'])){
                    return ['mensagem'  => "Selecione um Edital para receber o anexo", 'validacao' => false];
                }
                $aux_data =  
                [
                    'nome'              => $data['nome'],
                    'arquivo'           => $arquivo_nome,
                    'pai_id'            => $data['pai_id'],
                ];
    
                $this->validator->with($aux_data)->passesOrFail(ValidatorInterface::RULE_CREATE);
                $this->repository->create($aux_data);
                    return ['mensagem'  => "Edital cadastrado", 'validacao' => true];
                        }
            }
            catch(Exception $e)
            {
                switch(get_class($e))
                {    
                    case QueryException::class     : return     ['validacao' => false,     'mensagem' => $e->getMessage()];
                    case ValidatorException::class : return     ['validacao' => false,     'mensagem' => $e->getMessage()];
                    case Exception::class          : return     ['validacao' => false,     'mensagem' => $e->getMessage()];
                    default                        : return     ['validacao' => false,     'mensagem' => get_class($e)];
                }
            }
        }
        else{
            return['mensagem'  => "O anexo deve pertencer a um edital.", 'validacao' => false];                
        }
    }
    public function filtrarAnexo($instituicao_id, $ano, $tipo_id)
    {
        $anexosFiltrados = Edital::where('instituicao_id', '=', $instituicao_id)
                                  ->where('ano','=',$ano)
                                  ->where('tipo_id','=',$tipo_id)
                                  ->join('edital_filhos', 'editals.id', '=', 'edital_filhos.pai_id')
                                  ->select('edital_filhos.id', 'edital_filhos.nome', 'ano')
                                  ->get();

        return $anexosFiltrados;
    }
}