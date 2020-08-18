<?php

namespace App\Services;

use App\Entities\Edital;
use App\Entities\EditalFilho;
use App\Entities\EditalTipo;
use App\Entities\Instituicao;
use App\Repositories\EditalRepository;
use App\Validators\EditalValidator;
use Exception;
use Illuminate\Database\QueryException;
use Prettus\Validator\Contracts\ValidatorInterface;
use Prettus\Validator\Exceptions\ValidatorException;
use Illuminate\Support\Facades\Storage;
use App\Scripts\EditalClass;

//Métodos comuns a editais e editais filhos estão implementados na classe abstrata extendida a baixo
class EditalService extends EditalClass{
    
    private $repository;
    private $validator;

    public function __construct(EditalRepository $repository,EditalValidator $validator)
    {
        $this->repository = $repository;
        $this->validator = $validator;
    }

    public function salvar($data)
    {   
        $arquivo_nome = $this->formata($data['nome']) . '_' . $data['ano'] .'.pdf';

        $verificacao_arquivo = $this->verificaArquivo($data->file('arquivo'), $arquivo_nome);

        if($verificacao_arquivo['validacao'] != true)
        {
            return['mensagem'  => $verificacao_arquivo['mensagem'], 'validacao' => $verificacao_arquivo['validacao']];                
        }

        try
        {
            $arquivo_nome_verification = $this->repository->findWhere(['arquivo' => $arquivo_nome])->first();
            
            if($arquivo_nome_verification)                                   //Existe algum arquivo com o mesmo nome
            {                                    
                return['mensagem'  => "Já existe arquivo com esse nome. Mude o nome do edital.", 'validacao' => false];                
            }else                                                       //Não existe um arquivo com esse nome no banco
            {
                $data->file('arquivo')->storeAs('editais', $arquivo_nome);  //Salvo o arquivo
                
                if(empty($data['nome'])){
                    return ['mensagem'  => "Nome não pode ser vazio", 'validacao' => false];
                }
                if(empty($data['ano']) || $data['ano'] < 1990 || $data['ano'] > 2060)
                {
                    return ['mensagem'  => "Ano não pode ser vazio e deve ser um valor aceito", 'validacao' => false];
                }
                if(empty($data['tipo_id'])){
                    return ['mensagem'  => "Selecione um Tipo", 'validacao' => false];
                }
                if(empty($data['instituicao_id'])){
                    return ['mensagem'  => "Selecione a Instituição", 'validacao' => false];
                }
                $aux_data =  
                [
                    'nome'              => $data['nome'],
                    'arquivo'           => $arquivo_nome,
                    'ano'               => $data['ano'],
                    'tipo_id'           => $data['tipo_id'],
                    'instituicao_id'    => $data['instituicao_id'],
                ];
                
                $this->validator->with($aux_data)->passesOrFail(ValidatorInterface::RULE_CREATE);
                $this->repository->create($aux_data);
    
                return ['mensagem'  => "Edital cadastrado",'validacao' => true];
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

    public function anexos($instituicao_id, $ano, $tipo_id)
    {
        $anexos = EditalFilho::join('editals','edital_filhos.pai_id', '=', 'editals.id')
                             ->select('pai_id', 'instituicao_id', 'ano', 'tipo_id')
                             ->where('instituicao_id','=',$instituicao_id)
                             ->where('ano','=',$ano)
                             ->where('tipo_id','=',$tipo_id)
                             ->select('edital_filhos.id', 'edital_filhos.nome', 'pai_id')
                             ->get();

        return $anexos;
    }

    public function editaisComAnexo($instituicao_id, $ano, $tipo_id)
    {
        $editais_com_anexo = EditalFilho::join('editals','edital_filhos.pai_id', '=', 'editals.id')
                                        ->select('pai_id', 'instituicao_id', 'ano', 'tipo_id')
                                        ->where('instituicao_id','=',$instituicao_id)
                                        ->where('ano','=',$ano)
                                        ->where('tipo_id','=',$tipo_id)
                                        ->select('pai_id')
                                        ->groupBy('pai_id')
                                        ->orderBy('pai_id', 'asc')
                                        ->get();

        $editais_com_anexo_array = [];

        for($i = 0; $i < sizeof($editais_com_anexo); $i++)
        {
            $editais_com_anexo_array[$i] = $editais_com_anexo[$i]->pai_id; 
        }

        return $editais_com_anexo_array;
    }

    public function instituicoes()
    {
        $instituicoes = Instituicao::select('id','nome')->get();
        
        return $instituicoes;
    }

    public function ordenaAno()
    {
        $anos = Edital::select('ano')
                      ->groupBy('ano')
                      ->orderBy('ano', 'asc')
                      ->get();

        return $anos;
    }

    public function ordenaAnoPorInstituicao($instituicao_id)
    {
        $anos = Edital::where('instituicao_id', '=', $instituicao_id)
                      ->select('ano')
                      ->groupBy('ano')
                      ->orderBy('ano', 'asc')
                      ->get();

        return $anos;
    }

    public function tipos(){
        $tipos = EditalTipo::select('id','nome')->get();

        return $tipos;
    }

    public function tiposSelecionados($instituicao_id, $ano)
    {
        $tipos = EditalTipo::join('editals', 'edital_tipos.id', '=', 'editals.tipo_id')
                            ->select('edital_tipos.id','editals.instituicao_id', 'editals.ano', 'edital_tipos.nome')
                            ->where('instituicao_id', '=', $instituicao_id)
                            ->where('ano', '=', $ano)
                            ->select('edital_tipos.id', 'edital_tipos.nome')
                            ->groupBy('edital_tipos.id', 'edital_tipos.nome')
                            ->get();

        return $tipos;
    }

    public function filtrar($instituicao_id, $ano, $tipo_id)
    {
        $editaisFiltrados = Edital::where('instituicao_id', '=', $instituicao_id)
                                  ->where('ano','=',$ano)
                                  ->where('tipo_id','=',$tipo_id)
                                  ->select('*')
                                  ->get();

        return $editaisFiltrados;
    }

    public function maiorAno($anos){

        $ano_selecionado = $anos[0]->ano;
        
        for($i = 1; $i < sizeof($anos); $i++){
            if($anos[$i]->ano > $ano_selecionado){
                $ano_selecionado = $anos[$i]->ano;
            }
        }

        return $ano_selecionado;
    }
}