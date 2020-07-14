<?php

namespace App\Repositories;

use Prettus\Repository\Eloquent\BaseRepository;
use Prettus\Repository\Criteria\RequestCriteria;
use App\Repositories\editalRepository;
use App\Entities\Edital;
use App\Validators\EditalValidator;

/**
 * Class EditalRepositoryEloquent.
 *
 * @package namespace App\Repositories;
 */
class EditalRepositoryEloquent extends BaseRepository implements EditalRepository
{
    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return Edital::class;
    }

    /**
    * Specify Validator class name
    *
    * @return mixed
    */
    public function validator()
    {

        return EditalValidator::class;
    }


    /**
     * Boot up the repository, pushing criteria
     */
    public function boot()
    {
        $this->pushCriteria(app(RequestCriteria::class));
    }
    
}
