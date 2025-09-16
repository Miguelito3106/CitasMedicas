<?php

namespace App\Http\Controllers;

use App\Models\HorariosMedicos;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class HorariosMedicosController extends Controller
{
    public function index(){
        $horariosMedicos = HorariosMedicos::with('medico')->get();
        return response()->json($horariosMedicos);
    }

    public function store(Request $request){
        $validator = Validator::make($request->all(),[
            'medico_id' => 'required|exists:medicos,id',
            'dia_semana' => 'required|in:Lunes,Martes,Miercoles,Jueves,Viernes,Sabado',
            'hora_inicio' => 'required|date_format:H:i',
            'hora_fin' => 'required|date_format:H:i|after:hora_inicio',
        ]);

        if($validator->fails()){
            return response()->json($validator->errors(), 422);
        }

        $horarioMedico = HorariosMedicos::create($validator->validated());
        return response()->json($horarioMedico, 201);
    }

    public function show(string $id){
        $horarioMedico = HorariosMedicos::with('medico')->find($id);
        if(!$horarioMedico){
            return response()->json(['message' => 'Horario no encontrado'], 404);
        }
        return response()->json($horarioMedico);
    }

    public function update(Request $request, string $id){
        $horarioMedico = HorariosMedicos::find($id);
        if(!$horarioMedico){
            return response()->json(['message' => 'Horario no encontrado'], 404);
        }

        $validator = Validator::make($request->all(),[
            'medico_id' => 'sometimes|exists:medicos,id',
            'dia_semana' => 'sometimes|in:Lunes,Martes,Miercoles,Jueves,Viernes,Sabado',
            'hora_inicio' => 'sometimes|date_format:H:i',
            'hora_fin' => 'sometimes|date_format:H:i|after:hora_inicio',
        ]);

        if($validator->fails()){
            return response()->json($validator->errors(), 422);
        }

        $horarioMedico->update($validator->validated());
        return response()->json($horarioMedico);
    }

    public function destroy(string $id){
        $horarioMedico = HorariosMedicos::find($id);
        if(!$horarioMedico){
            return response()->json(['message' => 'Horario no encontrado'], 404);
        }
        
        $horarioMedico->delete();
        return response()->json(['message' => 'Horario eliminado correctamente']);
    }
}