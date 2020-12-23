<?php

namespace App\Repositories;

use Prettus\Repository\Eloquent\BaseRepository;
use Prettus\Repository\Criteria\RequestCriteria;
use App\Repositories\edital_filhoRepository;
use App\Entities\EditalFilho;
use App\Validators\EditalFilhoValidator;

/**
 * Class EditalFilhoRepositoryEloquent.
 *
 * @package namespace App\Repositories;
 */
class EditalFilhoRepositoryEloquent extends BaseRepository implements EditalFilhoRepository
{
    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return EditalFilho::class;
    }

    /**
    * Specify Validator class name
    *
    * @return mixed
    */
    public function validator()
    {

        return EditalFilhoValidator::class;
    }


    /**
     * Boot up the repository, pushing criteria
     */
    public function boot()
    {
        $this->pushCriteria(app(RequestCriteria::class));
    }
    
}
