<?php

namespace App\Http\Controllers;

use App\Models\MedicalCare;
use App\Models\TriageEntry;
use App\Models\User;
use Illuminate\Http\Request;
use App\Models\Specialty;
use App\Models\Doctor;
use App\Models\MedicalHistory;


class MedicalCareController extends Controller
{
    public function index()
    {
        // Obtener el usuario autenticado
        $user = auth()->user();
    
        if ($user->role_id == 1) {
            // Administrador: acceso completo
            $medicalCares = MedicalCare::with(['triageEntry', 'doctor.user'])->get();
        } elseif ($user->role_id == 2) {
            // Doctor: ver solo sus registros
            if ($user->doctor) { // Verifica si el doctor existe
                $medicalCares = MedicalCare::where('doctor_id', $user->doctor->id)
                    ->with(['triageEntry', 'doctor.user'])
                    ->get();
            } else {
                return redirect()->route('dashboard')->with('error', 'No tienes acceso a registros.');
            }
        } elseif ($user->role_id == 4) {
            // Recepcionista: ver todos los registros, solo puede crear y ver detalles
            $medicalCares = MedicalCare::with(['triageEntry', 'doctor.user'])->get();
        } else {
            // Otros roles: acceso restringido
            return redirect()->route('dashboard')->with('error', 'Acceso restringido.');
        }
    
        return view('medical_cares.index', compact('medicalCares'));
    }

    public function create(Request $request)
    {
        $triageEntries = TriageEntry::with('patient')->get();
        $triageEntries = TriageEntry::doesntHave('medicalCares')->get();
        $specialties = Specialty::all();
        $doctors = Doctor::with(['user', 'specialty'])->get(); // Asegúrate de cargar la especialidad
    
        return view('medical_cares.create', compact('triageEntries', 'specialties', 'doctors'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'triage_entry_id' => 'required|exists:triage_entries,id|unique:medical_cares,triage_entry_id',
            'doctor_id' => 'required|exists:doctors,id',
        ]);
    
        MedicalCare::create([
            'triage_entry_id' => $request->triage_entry_id,
            'doctor_id' => $request->doctor_id,
            'diagnosis' => '', // Valor por defecto
            'treatment' => '', // Valor por defecto
            'status' => 'pending', // Estado por defecto
            'date' => now()->format('Y-m-d'), // Fecha actual
            'time' => now()->format('H:i'), // Hora actual
        ]);
    
        return redirect()->route('medical_cares.index')->with('success', 'Atención médica creada exitosamente.');
    }


    public function show(MedicalCare $medicalCare)
    {
        return view('medical_cares.show', compact('medicalCare'));
    }

    public function edit(MedicalCare $medicalCare)
    {
        // Verifica si el usuario tiene el rol de administrador o doctor
        if (auth()->user()->role_id != 1 && auth()->user()->role_id != 2) {
            return redirect()->route('medical_cares.index')->with('error', 'No tienes permiso para editar.');
        }
    
        $triageEntries = TriageEntry::all();
        $specialties = Specialty::all();
        $doctors = Doctor::with('user')->get(); // Cargar doctores con usuarios
    
        return view('medical_cares.edit', compact('medicalCare', 'triageEntries', 'specialties', 'doctors'));
    }

    public function update(Request $request, MedicalCare $medicalCare)
    {
        // Validaciones comunes
        $rules = [
            'diagnosis' => 'required|string',
            'treatment' => 'required|string',
            'status' => 'required|string',
        ];
    
        if (auth()->user()->role_id == 1) { // Administrador puede editar todo
            $rules['triage_entry_id'] = 'required|exists:triage_entries,id';
            $rules['doctor_id'] = 'required|exists:doctors,id';
        } elseif (auth()->user()->role_id == 2) { // Doctor
            if ($medicalCare->doctor_id != auth()->user()->doctor->id) {
                return redirect()->route('medical_cares.index')->with('error', 'Acceso restringido.');
            }
        }
    
        // Validar los datos del request
        $request->validate($rules);
    
        // Preparar los datos para actualizar
        $data = [
            'diagnosis' => $request->diagnosis,
            'treatment' => $request->treatment,
            'status' => $request->status,
        ];
    
        // Si es administrador, agregar triage_entry_id y doctor_id
        if (auth()->user()->role_id == 1) {
            $data['triage_entry_id'] = $request->triage_entry_id;
            $data['doctor_id'] = $request->doctor_id;
        }
    
        // Actualizar la atención médica
        $medicalCare->update($data);
    
        // Actualizar el historial médico si se completa la atención médica
        if ($data['status'] === 'completed') {
            $medicalHistory = MedicalHistory::where('patient_id', $medicalCare->triageEntry->patient_id)
                ->where('condition', $request->diagnosis)
                ->first();
    
            if ($medicalHistory) {
                // Actualiza el registro existente
                $medicalHistory->update([
                    'treatment' => $request->treatment,
                    'date' => now()->format('Y-m-d'), // Actualiza la fecha
                ]);
            } else {
                // Si no existe, crea uno nuevo y agrega medical_care_id
                MedicalHistory::create([
                    'patient_id' => $medicalCare->triageEntry->patient_id,
                    'condition' => $request->diagnosis,
                    'treatment' => $request->treatment,
                    'date' => now()->format('Y-m-d'),
                    'medical_care_id' => $medicalCare->id, // Asegúrate de que esto no sea null
                ]);
            }
        }
    
        return redirect()->route('medical_cares.index')->with('success', 'Atención médica actualizada exitosamente.');
    }

    public function destroy(MedicalCare $medicalCare)
    {
        if (auth()->user()->role_id == 2) {
            abort(403, 'Acceso no autorizado');
        }
    
        $medicalCare->delete();
        return redirect()->route('medical_cares.index')->with('success', 'Atención médica eliminada correctamente.');
    }
}