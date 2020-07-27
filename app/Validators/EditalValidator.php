<?php

namespace App\Validators;

use \Prettus\Validator\Contracts\ValidatorInterface;
use \Prettus\Validator\LaravelValidator;

/**
 * Class EditalValidator.
 *
 * @package namespace App\Validators;
 */
class EditalValidator extends LaravelValidator
{
    /**
     * Validation Rules
     *
     * @var array
     */
    protected $rules = [
        ValidatorInterface::RULE_CREATE => 
        [
            'nome'              => 'required',
            'arquivo'           => 'required',
            'ano'               => 'required',
            'tipo_id'           => 'required',
            'instituicao_id'    => 'required',
        ],
        ValidatorInterface::RULE_UPDATE => [],
    ];
}
