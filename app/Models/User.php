<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\DB;
use Laravel\Passport\HasApiTokens;

class User extends Authenticatable
{
    use HasFactory, Notifiable, HasApiTokens;


    protected $table = 'inscaptur';
    public $timestamps = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'nombre',
        'login',
        'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        // 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        // 'email_verified_at' => 'datetime',
    ];

    public function details()
    {
        return DB::table('insdclist')
            ->join('inscarreras', 'inscarreras.id', '=', 'insdclist.carcve')
            ->where('insdclist.aluctr', $this->login)
            ->select(
                'inscarreras.nombre AS nombre_carrera',
                'inscarreras.nomcort AS nomcorto_carrera',
                'insdclist.clinpe AS periodo',
                'insdclist.nvoper AS nuevo_periodo',
                'insdclist.promedio',
                'insdclist.creditos',
                'insdclist.registro',
                'insdclist.inscrito'
            )
            ->get();
    }
}
