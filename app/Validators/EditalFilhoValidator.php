<?php

namespace App\Validators;

use \Prettus\Validator\Contracts\ValidatorInterface;
use \Prettus\Validator\LaravelValidator;

/**
 * Class EditalFilhoValidator.
 *
 * @package namespace App\Validators;
 */
class EditalFilhoValidator extends LaravelValidator
{
    /**
     * Validation Rules
     *
     * @var array
     */
    protected $rules = [
        ValidatorInterface::RULE_CREATE => 
        [
            'nome'      => 'required',
            'arquivo'   => 'required',
            'pai_id'    => 'required',
        ],
        ValidatorInterface::RULE_UPDATE => [],
    ];
}
