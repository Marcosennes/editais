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
                    return ['mensagem'  => "Anexo cadastrado", 'validacao' => true];
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

    public function excluir($anexo_id)
    {
        $this->repository->delete($anexo_id);

        return [
            'validacao' => true,
            'mensagem'  => "Anexo excluído",
        ];
    }

    public function anexosExcluidos()
    {
        $anexos_excluidos = EditalFilho::onlyTrashed()
                                       ->join('editals', 'editals.id', '=', 'edital_filhos.pai_id')
                                       ->select('edital_filhos.id', 'edital_filhos.nome', 'ano', 'editals.nome AS nome_pai', 'edital_filhos.deleted_at')
                                       ->orderBy('edital_filhos.pai_id', 'asc')
                                       ->orderBy('edital_filhos.created_at', 'asc')
                                       ->get();

        return $anexos_excluidos;
    }

    public function restaurar($anexo_id)
    {
        // $anexo_a_ser_restaurado = EditalFilho::onlyTrashed()->where('id', $anexo_id)->get();

        $pai_id = EditalFilho::withTrashed()
                             ->where('id', '=', $anexo_id)
                             ->select('pai_id')   
                             ->get();

        $verifica_pai_excluido = Edital::onlyTrashed()
                                       ->where('id', '=', $pai_id[0]['pai_id'])
                                       ->select('*')
                                       ->get();

        if(is_null($verifica_pai_excluido) || $verifica_pai_excluido->count() == 0)
        {
            //O edital não está excluído
            EditalFilho::onlyTrashed()
                       ->where('id', $anexo_id)
                       ->restore();

            return [
                'validacao' => true,
                'mensagem'  => "O anexo foi restaurado",
            ];

        }else{
            //O edital está excluído
            return [
                'validacao' => false,
                'mensagem'  => "O anexo não pode ser restaurado pois o edital dono deste está excluído.",
            ];
        }
        // dd($anexo_id, $pai_id[0]['pai_id'], $verifica_pai_deletado[0]['deleted_at']);

    }

    public function filtrarAnexo($instituicao_id, $ano, $tipo_id)
    {
        $anexosFiltrados = EditalFilho::join('editals', 'editals.id', '=', 'edital_filhos.pai_id')
                                         ->select('edital_filhos.id', 'edital_filhos.nome', 'ano', 'instituicao_id', 'tipo_id') 
                                         ->where('instituicao_id', '=', $instituicao_id)
                                         ->where('ano','=',$ano)
                                         ->where('tipo_id','=',$tipo_id)
                                         ->orderBy('edital_filhos.pai_id', 'asc')
                                         ->orderBy('edital_filhos.created_at', 'asc')
                                         ->select('edital_filhos.id', 'edital_filhos.nome', 'ano', 'editals.nome AS nome_pai')
                                         ->get();
        return $anexosFiltrados;
    }
}