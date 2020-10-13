<?php

namespace App\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\EditalFilho;
use Prettus\Repository\Contracts\Transformable;
use Prettus\Repository\Traits\TransformableTrait;
use Illuminate\Database\Eloquent\SoftDeletes;

class Edital extends Model implements Transformable
{
    use TransformableTrait;
    use SoftDeletes;

    protected $table = 'editals';
    protected $fillable = [
        'id',
        'nome',
        'arquivo',
        'ano',
        'tipo_id',
        'instituicao_id',
    ];

    protected $dates = ['deleted_at'];

    protected $hidden = [
        
    ];

    public function editalTipo(){     //No singular pois o produto pertence a uma instituição

        return $this->belongsTo(EditalTipo::class);   //belong = pertence

    }

    public function instituicao(){     //No singular pois o produto pertence a uma instituição

        return $this->belongsTo(Instituicao::class);   //belong = pertence

    }
    

    public function editalFilhos(){

        return $this->hasMany(EditalFilho::class);

    }
}
