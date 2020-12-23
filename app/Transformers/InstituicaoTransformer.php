<?php

namespace App\Transformers;

use League\Fractal\TransformerAbstract;
use App\Entities\Instituicao;

/**
 * Class InstituicaoTransformer.
 *
 * @package namespace App\Transformers;
 */
class InstituicaoTransformer extends TransformerAbstract
{
    /**
     * Transform the Instituicao entity.
     *
     * @param \App\Entities\Instituicao $model
     *
     * @return array
     */
    public function transform(Instituicao $model)
    {
        return [
            'id'         => (int) $model->id,

            /* place your other model properties here */

            'created_at' => $model->created_at,
            'updated_at' => $model->updated_at
        ];
    }
}
