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

class EditalFilhoService{
    
    private $repository;
    private $validator;

    public function __construct(EditalFilhoRepository $repository,EditalFilhoValidator $validator)
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
        $ano = Edital::where('id', '=', $data['pai_id'])
                        ->select('ano')
                        ->get();

        $fileName = $this->formata($data['nome']) . '_' . 'anexo_' . $ano[0]->ano .'.pdf';
        
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
                            'pai_id'            => $data['pai_id'],
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
}