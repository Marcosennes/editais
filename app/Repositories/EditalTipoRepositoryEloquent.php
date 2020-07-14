<?php

namespace App\Repositories;

use Prettus\Repository\Eloquent\BaseRepository;
use Prettus\Repository\Criteria\RequestCriteria;
use App\Repositories\edital_tipoRepository;
use App\Entities\EditalTipo;
use App\Validators\EditalTipoValidator;

/**
 * Class EditalTipoRepositoryEloquent.
 *
 * @package namespace App\Repositories;
 */
class EditalTipoRepositoryEloquent extends BaseRepository implements EditalTipoRepository
{
    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return EditalTipo::class;
    }

    /**
    * Specify Validator class name
    *
    * @return mixed
    */
    public function validator()
    {

        return EditalTipoValidator::class;
    }


    /**
     * Boot up the repository, pushing criteria
     */
    public function boot()
    {
        $this->pushCriteria(app(RequestCriteria::class));
    }
    
}
