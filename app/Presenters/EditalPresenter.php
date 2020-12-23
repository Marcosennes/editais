<?php

namespace App\Presenters;

use App\Transformers\EditalTransformer;
use Prettus\Repository\Presenter\FractalPresenter;

/**
 * Class EditalPresenter.
 *
 * @package namespace App\Presenters;
 */
class EditalPresenter extends FractalPresenter
{
    /**
     * Transformer
     *
     * @return \League\Fractal\TransformerAbstract
     */
    public function getTransformer()
    {
        return new EditalTransformer();
    }
}
