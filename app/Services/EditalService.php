<?php

namespace App\Services;

use App\Entities\Edital;
use App\Entities\EditalTipo;
use App\Entities\Instituicao;
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
            if($data->file('arquivo')->isValid())
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
                    'mensagem'  => "Problema ao enviar arquivo",
                    'validacao' => false,
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

    public function instituicoes()
    {
        $instituicoes = Instituicao::select('id','nome')
        ->get();
        
        return $instituicoes;
    }

    public function filtraPorAno($ano)
    {
        $editaisFiltrados = Edital::where('ano','=',$ano)
                                    ->where('tipo_id','=',1)
                                    ->select('*')
                                    ->get();

        return $editaisFiltrados;
    }

    public function filtraPorTipo($ano_selecionado, $tipo)
    {
        $editaisFiltrados = Edital::where('tipo_id','=',$tipo)
                                    ->where('ano','=',$ano_selecionado)
                                    ->select('id', 'nome', 'ano')
                                    ->get();

        return $editaisFiltrados;
    }

    public function filtraPorTipoEInstituicao($instituicao_id,$ano_selecionado, $tipo)
    {
        $editaisFiltrados = Edital::where('tipo_id','=',$tipo)
                                    ->where('ano','=',$ano_selecionado)
                                    ->where('instituicao_id','=',$instituicao_id)
                                    ->select('id', 'nome', 'ano')
                                    ->get();

        return $editaisFiltrados;
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