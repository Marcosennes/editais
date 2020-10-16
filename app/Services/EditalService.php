<?php

namespace App\Services;

use App\Entities\Edital;
use App\Entities\EditalFilho;
use App\Entities\EditalTipo;
use App\Entities\Instituicao;
use App\Repositories\EditalRepository;
use App\Validators\EditalValidator;
use App\Repositories\EditalFilhoRepository;
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

    public function __construct(EditalRepository $repository,EditalValidator $validator, EditalFilhoRepository $anexo_repository)
    {
        $this->repository = $repository;
        $this->validator = $validator;
        $this->anexo_repository = $anexo_repository;
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
            
            if($arquivo_nome_verification)  //Existe algum arquivo com o mesmo nome
            {                                    
                return['mensagem'  => "Já existe arquivo com esse nome. Mude o nome do edital.", 'validacao' => false];                
            }else   //Não existe um arquivo com esse nome no banco
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

    public function excluir($edital_id)
    {
        $possui_anexo = EditalFilho::where('pai_id', '=', $edital_id)
                                    ->select('id')
                                    ->get();
        
        if(is_null($possui_anexo) || $possui_anexo->count() == 0)
        {
            $this->repository->delete($edital_id);

            return [
                'validacao' => 'true',
                'mensagem'  => "Edital excluído",
            ];
        }    
        else
        {
            foreach($possui_anexo as $anexo)
            {
                $this->anexo_repository->delete($anexo["id"]);
            }

            $this->repository->delete($edital_id);

            return [
                'validacao' => 'true',
                'mensagem'  => "Edital e anexos excluídos",
            ];
        }

        return [
            'validacao' => 'false',
            'mensagem'  => "O Edital não foi excluído",
        ];
    }
    //retorna os anexos de um edital
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

    //retorna um vetor com o id de todos os editais com anexo
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

    public function ordenaAnoTipoPorInstituicao($instituicao_id)    //retorna os anos de uma instituicao com os tipos que cada ano possui
    {
        $anos = Edital::where('instituicao_id', '=', $instituicao_id)
                      ->select('ano')
                      ->groupBy('ano')
                      ->orderBy('ano', 'asc')
                      ->get();

        for($i=0; $i<sizeof($anos); $i++){  // $anos recebe os ids dos tipos que cada ano possui
            $anos[$i]->tipos = Edital::where('instituicao_id', '=', $instituicao_id)
                                     ->where('ano', '=', $anos[$i]->ano)
                                     ->select('tipo_id')
                                     ->groupBy('tipo_id')
                                     ->orderBy('tipo_id', 'asc')
                                     ->get(); 

            for($k=0; $k<sizeof($anos[$i]->tipos); $k++){   //é necessário para encontrar o nome de cada tipo
                $anos[$i]->tipos[$k]->nome = EditalTipo::where('id', '=', $anos[$i]->tipos[$k]->tipo_id)
                                                       ->select('nome')
                                                       ->get();

            $anos[$i]->tipos[$k]->nome = $anos[$i]->tipos[$k]->nome[0]->nome;
            }
        }
        //dd($anos[0]->ano, $anos[0]->tipos[1]->tipo_id);
        
        return $anos;
    }

    public function tipos(){
        $tipos = EditalTipo::select('id','nome')->get();

        return $tipos;
    }
    //retorna os tipos de uma instituição em um ano específico
    public function tiposSelecionados($instituicao_id, $ano)
    {
        $tipos = EditalTipo::join('editals', 'edital_tipos.id', '=', 'editals.tipo_id')
                            ->where('instituicao_id', '=', $instituicao_id)
                            ->where('ano', '=', $ano)
                            ->whereNull('deleted_at')
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

    //retorna o maior ano de um vetor de anos recebidos
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