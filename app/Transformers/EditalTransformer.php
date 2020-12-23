<?php

namespace App\Transformers;

use League\Fractal\TransformerAbstract;
use App\Entities\Edital;

/**
 * Class EditalTransformer.
 *
 * @package namespace App\Transformers;
 */
class EditalTransformer extends TransformerAbstract
{
    /**
     * Transform the Edital entity.
     *
     * @param \App\Entities\Edital $model
     *
     * @return array
     */
    public function transform(Edital $model)
    {
        return [
            'id'         => (int) $model->id,

            /* place your other model properties here */

            'created_at' => $model->created_at,
            'updated_at' => $model->updated_at
        ];
    }
}
