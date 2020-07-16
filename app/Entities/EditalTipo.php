<?php

namespace App\Entities;

use Illuminate\Database\Eloquent\Model;
use Prettus\Repository\Contracts\Transformable;
use Prettus\Repository\Traits\TransformableTrait;
    
class EditalTipo extends Model implements Transformable
{
    use TransformableTrait;

    protected $table = 'edital_tipos';
    protected $fillable = [
        'id',
        'nome',
    ];

    protected $hidden = [
        
    ];

    public function editals(){

        return $this->hasMany(Edital::class);

    }
}
