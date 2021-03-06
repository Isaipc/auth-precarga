<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Alumno extends Model
{
    use HasFactory;

    protected $table = 'insdclist';
    public $timestamps = false;

    public function detalles()
    {
        return $this->hasOne(DetallesAlumno::class, 'aluctr', 'aluctr');
    }
}
