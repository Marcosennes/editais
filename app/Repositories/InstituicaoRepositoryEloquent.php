<?php

namespace App\Repositories;

use Prettus\Repository\Eloquent\BaseRepository;
use Prettus\Repository\Criteria\RequestCriteria;
use App\Repositories\InstituicaoRepository;
use App\Entities\Instituicao;
use App\Validators\InstituicaoValidator;

/**
 * Class InstituicaoRepositoryEloquent.
 *
 * @package namespace App\Repositories;
 */
class InstituicaoRepositoryEloquent extends BaseRepository implements InstituicaoRepository
{
    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return Instituicao::class;
    }

    /**
    * Specify Validator class name
    *
    * @return mixed
    */
    public function validator()
    {

        return InstituicaoValidator::class;
    }


    /**
     * Boot up the repository, pushing criteria
     */
    public function boot()
    {
        $this->pushCriteria(app(RequestCriteria::class));
    }
    
}
