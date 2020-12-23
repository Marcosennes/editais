<?php

namespace App\Presenters;

use App\Transformers\EditalTipoTransformer;
use Prettus\Repository\Presenter\FractalPresenter;

/**
 * Class EditalTipoPresenter.
 *
 * @package namespace App\Presenters;
 */
class EditalTipoPresenter extends FractalPresenter
{
    /**
     * Transformer
     *
     * @return \League\Fractal\TransformerAbstract
     */
    public function getTransformer()
    {
        return new EditalTipoTransformer();
    }
}
