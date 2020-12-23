<?php

namespace App\Entities;

use Illuminate\Database\Eloquent\Model;
use Prettus\Repository\Contracts\Transformable;
use Prettus\Repository\Traits\TransformableTrait;
use Illuminate\Database\Eloquent\SoftDeletes;

class EditalFilho extends Model implements Transformable
{
    use TransformableTrait;
    use SoftDeletes;

    protected $table = 'edital_filhos';

    protected $fillable = [
        'id',
        'nome',
        'arquivo',
        'pai_id',
    ];
    
    protected $dates = ['deleted_at'];

    protected $hidden = [
        
    ];

    public function edital(){     //No singular

        return $this->belongsTo(Edital::class);   //belong = pertence

    }
}