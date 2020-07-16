<?php

namespace App\Entities;

use Illuminate\Database\Eloquent\Model;
use Prettus\Repository\Contracts\Transformable;
use Prettus\Repository\Traits\TransformableTrait;

class EditalFilho extends Model implements Transformable
{
    use TransformableTrait;

    protected $table = 'edital_filhos';

    protected $fillable = [
        'id',
        'nome',
        'endereco',
        'pai_id',
    ];

    protected $hidden = [
        
    ];

    public function edital(){     //No singular

        return $this->belongsTo(Edital::class);   //belong = pertence

    }
}