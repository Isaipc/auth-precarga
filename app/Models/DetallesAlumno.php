<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetallesAlumno extends Model
{
    use HasFactory;

    protected $table = 'insdalumnos';
    public $timestamps = false;
}
