<?php

namespace App\Transformers;

use League\Fractal\TransformerAbstract;
use App\Entities\EditalFilho;

/**
 * Class EditalFilhoTransformer.
 *
 * @package namespace App\Transformers;
 */
class EditalFilhoTransformer extends TransformerAbstract
{
    /**
     * Transform the EditalFilho entity.
     *
     * @param \App\Entities\EditalFilho $model
     *
     * @return array
     */
    public function transform(EditalFilho $model)
    {
        return [
            'id'         => (int) $model->id,

            /* place your other model properties here */

            'created_at' => $model->created_at,
            'updated_at' => $model->updated_at
        ];
    }
}
