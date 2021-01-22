<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use stdClass;

define('RETICULA', 'd');
class PrecargaController extends Controller
{

    /**
     * Solicita las materias precargadas del alumno
     */
    public function obtenerPrecarga(Request $request)
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

        // se obtienen las materias cursadas por el alumno
        $materias_cursadas = DB::table('insdlista')
            ->select('matcve AS clave')
            ->where('aluctr', $request->user()->login)
            ->where('liscal', '>', 0)
            ->get();


        $arr = array();
        foreach ($materias_cursadas as $key => $value) {
            array_push($arr, $value->clave);
        }

        // Se extraen los periodos del reticula
        $reticula = DB::table('insdretic')
            ->select('retper AS periodo')
            ->whereRaw('placve = BINARY ?', RETICULA)
            ->orderBy('periodo')
            ->distinct()
            ->get();



        // Se recorren los periodos
        foreach ($reticula as $key => $value) {

            // Se extraen las materias de un periodo especifico
            $materias = DB::table('insdretic')
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
                    DB::raw("'N' AS tipo"),

                )
                ->whereRaw('placve = BINARY ?', RETICULA)
                ->where('insdretic.retper', $value->periodo)
                ->whereNotIn('insdretic.matcve', $arr)
                ->get();

            $value->total_materias = $materias->count();

            $value->materias = $materias;
        }


        return response()->json([
            'num_periodos' => $reticula->count(),
            'reticula' => $reticula,
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

        $num_control = $request->user()->login;

        DB::table('insprecarga')
            ->where('insprecarga.aluctr', $num_control)
            ->delete();

        $materias = array();

        foreach ($request->materias as $key => $value) {
            
            $_materia = [
                'aluctr' =>  $num_control,
                'periodo' =>  $value['periodo'],
                'matcve' =>  $value['clave'],
                'matcre' =>  $value['creditos'],
                // 'grupo' => '',
            ];

            array_push($materias, $_materia);
        }    
        
        DB::table('insprecarga')->insert($materias);

        return response()->json(['message' => 'Precarga finalizada'], 201);
    }
}
