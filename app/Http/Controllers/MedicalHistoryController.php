<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\MedicalHistory;

class MedicalHistoryController extends Controller
{
    public function index(Request $request)
    {
        $user = auth()->user();
        $query = MedicalHistory::query();
    
        if ($user->role_id == 5) {
            $query->where('patient_id', $user->patient->id);
        } elseif ($request->filled('patient_id')) {
            $query->where('patient_id', $request->input('patient_id'));
        }
    
        $medicalHistories = $query->get();
    
        return view('medical_histories.index', compact('medicalHistories'));
    }
    

    public function show(MedicalHistory $medicalHistory)
    {
        // Cargar las relaciones necesarias
        $medicalHistory->load('medicalCare.doctor.specialty');
    
        // Verifica si las relaciones se han cargado correctamente
        \Log::info('Medical History:', [
            'medicalHistory' => $medicalHistory,
            'doctor' => $medicalHistory->medicalCare?->doctor,
            'specialty' => $medicalHistory->medicalCare?->doctor?->specialty,
        ]);
    
        return view('medical_histories.show', compact('medicalHistory'));
    }


    public function create(Request $request)
    {
        $triageEntries = TriageEntry::doesntHave('medicalCares')->get();
        $specialties = Specialty::all();
    
        // Inicializar variables
        $doctors = [];
        $selectedSpecialty = null;
    
        // Verifica si se ha enviado una especialidad
        if ($request->has('specialty_id')) {
            $selectedSpecialty = $request->input('specialty_id');
            $doctors = Doctor::where('specialty_id', $selectedSpecialty)->get();
        }
    
        return view('medical_cares.create', compact('triageEntries', 'specialties', 'doctors', 'selectedSpecialty'));
    }

    public function edit(MedicalHistory $medicalHistory)
    {
        return view('medical_histories.edit', compact('medicalHistory'));
    }

    public function update(Request $request, MedicalHistory $medicalHistory)
    {
        $request->validate([
            'patient_id' => 'required|exists:patients,id',
            'condition' => 'required|string',
            'treatment' => 'nullable|string',
            'date' => 'required|date',
        ]);

        $medicalHistory->update($request->all());

        return redirect()->route('medical_histories.index')->with('success', 'Historial médico actualizado exitosamente.');
    }

    public function destroy(MedicalHistory $medicalHistory)
    {
        $medicalHistory->delete();

        return redirect()->route('medical_histories.index')->with('success', 'Historial médico eliminado exitosamente.');
    }
}
