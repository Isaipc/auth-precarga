<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\DB;
use Laravel\Passport\HasApiTokens;

define('RETICULA', 'd');
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

    /* Consulta las materias del periodo que debe cursar el alumno */
    public function materiasPeriodo()
    {
        // Se extraen las materias del periodo que tiene que cursar el alumno
        return $this->materias('N')
            ->where('insdretic.retper', $this->alumno->nvoper);
    }

    /* Consulta las materias precargadas del alumno*/
    public function materiasPrecargadas()
    {
        return DB::table('insprecarga')
            ->join('insdmater', 'insdmater.matcve', '=', 'insprecarga.matcve')
            ->where('insprecarga.aluctr', $this->login)
            ->select(
                'insprecarga.id',
                'insprecarga.grupo AS grupo',
                'insprecarga.periodo AS periodo',
                'insprecarga.matcve AS clave',
                'insdmater.matnom AS nombre',
                'insdmater.matnco AS nombre_corto',
                'insdmater.mathte AS teoricas',
                'insdmater.mathpr AS practicas',
                'insdmater.matcre AS creditos',
                'insprecarga.tipo AS tipo',
            )
            ->orderBy('insprecarga.periodo');
    }

    /* Consulta las materias especiales del alumno */
    public function materiasEspeciales()
    {
        return $this->materias('E')
            ->whereIn('insdretic.matcve', function ($query) {
                $query->select('matcve')
                    ->from('insespeciales')
                    ->where('aluctr', $this->login);
            });
    }

    /* Consulta las materias reprobadas del alumno */
    public function materiasRepites()
    {
        // Se puede solucionar con una tabla 🤔
        return $this->materias('R')
            // ->where('insdretic.retper', $this->alumno->nvoper)
            ->whereIn('insdretic.matcve', function ($query) {
                $query->select('matcve')
                    ->from('insdlista')
                    ->where('aluctr', $this->login)
                    ->where('liscal', '<', 70);
            })
            ->whereNotIn('insdretic.matcve', function ($query) {
                $query->select('matcve')
                    ->from('insdlista')
                    ->where('aluctr', $this->login)
                    ->where('liscal', '>=', 70);
            })
            ->whereNotIn('insdretic.matcve', function ($query) {
                $query->select('matcve')
                    ->from('insespeciales')
                    ->where('aluctr', $this->login);
            });
    }

    /* Consulta que devuelve los detalles del alumno según sus datos académicos */
    public function detallesDelAlumno()
    {
        $especiales = $this->materiasEspeciales()->get('matcve');
        $repites = $this->materiasRepites()->get('matcve');

        return DB::table('insdclist')
            ->join('inscarreras', 'inscarreras.id', '=', 'insdclist.carcve')
            ->join('inscaptur', 'inscaptur.login', '=', 'insdclist.aluctr')
            ->where('insdclist.aluctr', $this->login)
            ->select(
                'inscaptur.id',
                'inscaptur.nombre',
                'insdclist.aluctr AS control',
                'inscarreras.nombre AS nombre_carrera',
                'inscarreras.nomcort AS nomcorto_carrera',
                'insdclist.clinpe AS periodo',
                'insdclist.nvoper AS nuevo_periodo',
                'insdclist.promedio',
                'insdclist.creditos',
                'insdclist.registro',
                'insdclist.inscrito',
                DB::raw($repites->count() . ' AS repites'),
                DB::raw($especiales->count() . ' AS especiales')
            )
            ->first();
    }

    public function alumno()
    {
        return $this->hasOne(Alumno::class, 'aluctr', 'login');
    }


    public function materias($tipo)
    {
        return DB::table('insdretic')
            ->join('insdmater', 'insdmater.matcve', '=', 'insdretic.matcve')
            ->select(
                'insdretic.id',
                'insdretic.retper AS periodo',
                'insdretic.matcve AS clave',
                'insdmater.matnom AS nombre',
                'insdmater.matnco AS nombre_corto',
                'insdmater.mathte AS teoricas',
                'insdmater.mathpr AS practicas',
                'insdmater.matcre AS creditos',
                DB::raw("'" . $tipo . "' AS tipo"),
            )
            ->whereRaw('placve = BINARY ?', RETICULA);
    }
}
