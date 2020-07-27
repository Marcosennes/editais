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


class EditalService{
    
    private $repository;
    private $validator;

    public function __construct(EditalRepository $repository,EditalValidator $validator)
    {
        $this->repository = $repository;
        $this->validator = $validator;
    }

    public function formata($palavra){

        $str = $palavra;
        $str = strtolower(utf8_decode($str)); $i=1;
        $str = strtr($str, utf8_decode('àáâãäåæçèéêëìíîïñòóôõöøùúûýýÿ'), 'aaaaaaaceeeeiiiinoooooouuuyyy');
        $str = preg_replace("/([^a-z0-9])/",'-',utf8_encode($str));
        while($i>0) $str = str_replace('--','-',$str,$i);
        if (substr($str, -1) == '-') $str = substr($str, 0, -1);

        return $str;
    }

    public function salvar($data)
    {
        $fileName = $this->formata($data['nome']) . '_' . $data['ano'] .'.pdf';
        
        try
        {
            if($data->file('arquivo') != null)
            {
                if($data->file('arquivo')->extension() == 'pdf')
                {
                    $fileName_verification = $this->repository->findWhere(['arquivo' => $fileName])->first();
                    
                    if($fileName_verification){                                //Existe algum arquivo com o mesmo nome
                        
                        return
                        [
                            'mensagem'  => "Já existe arquivo com esse nome. Mude o nome do edital.",
                            'validacao' => false,
                        ];                
                    }else                                                       //Não existe um arquivo com esse nome no banco
                    {
                        
                        $data->file('arquivo')->storeAs('editais', $fileName);  //Salvo o arquivo
                        
                        if(empty($data['nome'])){
                            return [
                                'mensagem'  => "Nome não pode ser vazio",
                                'validacao' => false];
                        }
                        if(empty($data['ano']) || $data['ano'] < 1990 || $data['ano'] > 2060){
                            return [
                                'mensagem'  => "Ano não pode ser vazio e deve ser um valor aceito",
                                'validacao' => false];
                        }
                        if(empty($data['tipo_id'])){
                            return [
                                'mensagem'  => "Selecione um Tipo",
                                'validacao' => false];
                        }
                        if(empty($data['instituicao_id'])){
                            return [
                                'mensagem'  => "Selecione a Instituição",
                                'validacao' => false];
                        }

                        $aux_data =  
                        [
                            'nome'              => $data['nome'],
                            'arquivo'           => $fileName,
                            'ano'               => $data['ano'],
                            'tipo_id'           => $data['tipo_id'],
                            'instituicao_id'    => $data['instituicao_id'],
                        ];
                        
                        $this->validator->with($aux_data)->passesOrFail(ValidatorInterface::RULE_CREATE);
                        $this->repository->create($aux_data);
            
                        return 
                        [
                            'mensagem'  => "Edital cadastrado",
                            'validacao' => true,
                        ];
                    }
                }
                else
                {
                    return
                    [
                        'mensagem'  => "Só é aceito arquivo no formato PDF",
                        'validacao' => false,
                    ];
                }
                
            }else
            {
                return
                [
                    'mensagem'  => "Campo do arquivo não pode ser vazio",
                    'validacao' => false,
                ];
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
        $instituicoes = Instituicao::select('id','nome')
        ->get();
        
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

    public function tipos()
    {
        $tipos = EditalTipo::select('id','nome')
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
}