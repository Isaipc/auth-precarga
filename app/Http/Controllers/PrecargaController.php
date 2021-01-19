<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PrecargaController extends Controller
{

    /**
     * Solicita las materias de la reticula en base a un periodo en especifico
     */
    public function obtenerMaterias(Request $request)
    {
        $request->validate([
            'periodo' => 'required|int'
        ]);


        // $rows = DB::selectRaw('ROW_NUMBER() over (ORDER BY insdretic.id)  AS row_num');
        DB::statement(DB::raw('set @irow:=0'));

        $materias = DB::table('insdretic')
            ->join('insdmater', 'insdmater.matcve', '=', 'insdretic.matcve')
            ->whereRaw('insdretic.placve = BINARY ?', 'd')
            ->where('insdretic.retper', $request->periodo)
            ->select(
                // DB::raw('@irow:=@irow+1 AS row_number'),
                'insdretic.id',
                'insdretic.retper AS periodo',
                'insdretic.matcve AS clave',
                'insdmater.matnom AS nombre',
                'insdmater.matnco AS nombre_corto',
                'insdmater.mathte AS teoricas',
                'insdmater.mathpr AS practicas',
                'insdmater.matcre AS creditos',
            )
            ->orderBy('insdretic.retper')
            // ->orderBy('row_number')
            ->get();


        return response()->json([
            'total_materias' => $materias->count(),
            'materias' => $materias,
        ]);
    }


    /**
     * Solicita las materias a seleccionar para la precarga
     */
    public function solicitar(Request $request)
    {
        DB::statement(DB::raw('set @irow:=0'));

        // dd(auth('api')->user()->login);

        $alumno = DB::table('insdclist')
            ->where('aluctr', $request->user()->login)
            ->first();

        $materias = DB::table('insdretic')
            ->join('insdmater', 'insdmater.matcve', '=', 'insdretic.matcve')
            ->whereRaw('insdretic.placve = BINARY ?', 'd')
            ->where('insdretic.retper', $alumno->nvoper)
            ->select(
                // DB::raw('@irow:=@irow+1 AS rownumber'),
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
            // ->orderBy('row_number')
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
