<?php

namespace App\Entities;

use Illuminate\Foundation\Auth\Edital as Authenticatable;
use Illuminate\Foundation\Auth\EditalFilho;
use Illuminate\Notifications\Notifiable;

class Edital extends Authenticatable
{
    use Notifiable;

    protected $table = 'editals';
    protected $fillable = [
        'id',
        'nome',
        'arquivo',
        'ano',
        'tipo_id',
        'instituicao',
    ];

    protected $hidden = [
        
    ];

    public function editalTipo(){     //No singular pois o produto pertence a uma instituição

        return $this->belongsTo(EditalTipo::class);   //belong = pertence

    }


    public function editalFilhos(){

        return $this->hasMany(EditalFilho::class);

    }
}
