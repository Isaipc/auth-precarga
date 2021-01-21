<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PrecargaController extends Controller
{

    /**
     * Solicita las materias precargadas del alumno
     */
    public function solicitar(Request $request)
    {
        $materias = DB::table('insprecarga')
            ->join('insdmater', 'insdmater.matcve', '=', 'insprecarga.matcve')
            ->where('insprecarga.aluctr', $request->user()->login)
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
            )
            ->orderBy('insprecarga.periodo')
            ->get();


        return response()->json([
            'total_materias' => $materias->count(),
            'materias' => $materias,
        ]);
    }


    /**
     * Solicita las materias disponibles para la precarga
     */
    public function obtenerMaterias(Request $request)
    {
        $alumno = DB::table('insdclist')
            ->where('aluctr', $request->user()->login)
            ->first();

        $materias = DB::table('insdretic')
            ->join('insdmater', 'insdmater.matcve', '=', 'insdretic.matcve')
            ->whereRaw('insdretic.placve = BINARY ?', 'd')
            ->where('insdretic.retper', $alumno->nvoper)
            ->select(
                'insdretic.id',
                'insdretic.retper AS periodo',
                'insdretic.matcve AS clave',
                'insdmater.matnom AS nombre',
                'insdmater.matnco AS nombre_corto',
                'insdmater.mathte AS teoricas',
                'insdmater.mathpr AS practicas',
                'insdmater.matcre AS creditos',
                DB::raw("'N' AS tipo"),

            )
            ->orderBy('insdretic.retper')
            ->get();


        return response()->json([
            'total_materias' => $materias->count(),
            'total_creditos' => $materias->sum('creditos'),
            'materias' => $materias,
        ]);
    }

    /**
     * Guarda la precarga con las materias seleccionadas
     */
    public function guardar(Request $request)
    {

        $request->validate([
            'materias' => 'required'
        ]);


        $request->materias->dd();

        // DB::table('insprecarga')->insertOrIgnore([]);

        return response()->json(['message' => 'Precarga finalizada'], 201);
    }
}
