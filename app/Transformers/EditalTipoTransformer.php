<?php

namespace App\Transformers;

use League\Fractal\TransformerAbstract;
use App\Entities\EditalTipo;

/**
 * Class EditalTipoTransformer.
 *
 * @package namespace App\Transformers;
 */
class EditalTipoTransformer extends TransformerAbstract
{
    /**
     * Transform the EditalTipo entity.
     *
     * @param \App\Entities\EditalTipo $model
     *
     * @return array
     */
    public function transform(EditalTipo $model)
    {
        return [
            'id'         => (int) $model->id,

            /* place your other model properties here */

            'created_at' => $model->created_at,
            'updated_at' => $model->updated_at
        ];
    }
}
