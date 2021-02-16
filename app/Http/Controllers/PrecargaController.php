<?php

namespace App\Http\Controllers;

use App\Models\MateriaPrecarga;
use Illuminate\Http\Request;

class PrecargaController extends Controller
{
    protected $max_creditos = 0;
    protected $max_materias = 0;

    /**
     * Solicita las materias precargadas del alumno
     */
    public function obtenerPrecarga(Request $request)
    {
        $materias = $request->user()->materiasPrecargadas()->get();

        return response()->json([
            'materias' => $materias,
        ]);
    }

    /**
     * Solicita las materias disponibles para la precarga
     */
    public function obtenerMaterias(Request $request)
    {
        // Se extrae el usuario del alumno:
        $user = $request->user();
        // Se extraen las materias del periodo disponibles
        $materias = $user->materiasPeriodo()->get();
        // Se extraen las materias especiales del alumno de la bd:
        $especiales = $user->materiasEspeciales()->get();
        // Se extraen las materias reprobadas del alumno de la bd:
        $repites = $user->materiasRepites()->get();

        // **CONDICIONES**
        // Alumnos irregulares:
        if ($repites->count() > 0)
            $materias = $materias->merge($repites);

        // Alumnos con 1 especial:
        if ($especiales->count() == 1) {
            // Se excluye RESIDENCIAS PROFESIONALES
            // Se incluye el especial
            $materias = $materias
                ->where('clave', '<>', 'RISC1001')
                ->merge($especiales);

            // Alumnos con 2 especiales:
        } else if ($especiales->count() >= 2)

            // Se incluyen solo los especiales
            $materias = $especiales;

        return response()->json([
            'materias' => $materias,
        ]);
    }

    /**
     * Guarda la precarga con las materias seleccionadas
     */
    public function guardar(Request $request)
    {
        // Se valida la existencia de la lista de materias:
        $request->validate([
            'materias' => 'required'
        ]);

        // Se extrae el usuario del alumno:
        $user = $request->user();

        //Se extraen las materias subidas:
        $materias = $request->materias;

        return $this->validarPrecarga($user, $materias);
    }

    protected function validarPrecarga($user, $materias)
    {
        $total_creditos = 0;

        // Se extraen las materias especiales del alumno de la bd:
        $especiales = $user->materiasEspeciales();
        // Se extraen las materias reprobadas del alumno de la bd:
        $repites = $user->materiasRepites();

        if ($repites->get('matcve')->count() > 0) {
            $this->max_creditos = 36;
            $this->max_materias = 7;
        }

        if ($especiales->get()->count() == 1) {
            $this->max_creditos = 22;
            $this->max_materias = 4;
        }

        // Calcula el total de créditos de las materias subidas
        foreach ($materias as $key => $materia)
            $total_creditos += $materia['creditos'];

        if ($total_creditos > $this->max_creditos)
            return response()->json(
                ['message' => 'No puede exceder el límite de créditos: ' .
                    $this->max_creditos],
                422
            );
        if (count($materias) > $this->max_materias)
            return response()->json(
                ['message' => 'No puede exceder el límite de materias: ' .
                    $this->max_materias],
                422
            );

        // Limpiar las precargas del alumno:
        MateriaPrecarga::where('aluctr', $user->login)->delete();

        foreach ($materias as $value) {
            MateriaPrecarga::create([
                'aluctr' =>  $user->login,
                'periodo' =>  $value['periodo'],
                'matcve' =>  $value['clave'],
                'matcre' =>  $value['creditos'],
                'tipo' =>  $value['tipo'],
                // 'grupo' => '',
            ]);
        }

        return response()->json(['message' => 'Precarga finalizada'], 201);
    }
}
