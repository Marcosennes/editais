<?php

namespace App\Presenters;

use App\Transformers\InstituicaoTransformer;
use Prettus\Repository\Presenter\FractalPresenter;

/**
 * Class InstituicaoPresenter.
 *
 * @package namespace App\Presenters;
 */
class InstituicaoPresenter extends FractalPresenter
{
    /**
     * Transformer
     *
     * @return \League\Fractal\TransformerAbstract
     */
    public function getTransformer()
    {
        return new InstituicaoTransformer();
    }
}
