<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MateriaPrecarga extends Model
{
    use HasFactory;

    protected $table = 'insprecarga';
    public $timestamps = false;

    protected $fillable = [
        'aluctr',
        'periodo',
        'matcve',
        'matcre',
        'tipo',
    ];

    public function materia()
    {
        return $this->hasOne(Materia::class, 'matcve', 'matcve');
    }
}
