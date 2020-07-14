<?php

namespace App\Presenters;

use App\Transformers\EditalFilhoTransformer;
use Prettus\Repository\Presenter\FractalPresenter;

/**
 * Class EditalFilhoPresenter.
 *
 * @package namespace App\Presenters;
 */
class EditalFilhoPresenter extends FractalPresenter
{
    /**
     * Transformer
     *
     * @return \League\Fractal\TransformerAbstract
     */
    public function getTransformer()
    {
        return new EditalFilhoTransformer();
    }
}
