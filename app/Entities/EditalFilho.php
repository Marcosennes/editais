<?php

namespace App\Entities;

use Illuminate\Foundation\Auth\Edital as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Edital extends Authenticatable
{
    use Notifiable;

    protected $table = 'editals';
    protected $fillable = [
        'id',
        'nome',
        'endereco',
        'pai_id',
    ];

    protected $hidden = [
        
    ];

    public function edital(){     //No singular pois o produto pertence a uma instituição

        return $this->belongsTo(Edital::class);   //belong = pertence

    }
}