<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TriageEntry;
use App\Models\Patient;
use Illuminate\Support\Facades\Auth;

class TriageEntryController extends Controller
{
    public function index()
    {
        $user = auth()->user();
    
        // Si es paciente, solo mostrar sus registros
        if ($user->role_id == 5) {
            $triageEntries = TriageEntry::with('patient', 'nurse')
                ->where('patient_id', $user->patient->id)
                ->get();
        } else {
            // Otros roles pueden ver todos
            $triageEntries = TriageEntry::with('patient', 'nurse')->get();
        }
    
        return view('triage_entries.index', compact('triageEntries'));
    }
    


    public function show(TriageEntry $triageEntry)
    {
        $user = auth()->user();
    
        if ($user->role_id == 5 && $triageEntry->patient_id !== $user->patient->id) {
            abort(403, 'No autorizado a ver este registro.');
        }
    
        return view('triage_entries.show', compact('triageEntry'));
    }
    


    public function create()
    {
        $user = Auth::user();
        if (!in_array($user->role_id, [1, 4])) {
            abort(403, 'No autorizado.');
        }

        return view('triage_entries.create');
    }

    public function findPatient(Request $request)
    {
        $user = Auth::user();
        if (!in_array($user->role_id, [1, 4])) {
            abort(403, 'No autorizado.');
        }

        $request->validate([
            'identification_number' => 'required|string',
        ]);

        $patient = Patient::where('identification_number', $request->identification_number)->first();

        if (!$patient) {
            return redirect()->route('triage_entries.create')->with('error', 'Paciente no encontrado.');
        }

        return view('triage_entries.create', compact('patient'));
    }

    public function store(Request $request)
    {
        $user = Auth::user();
        if (!in_array($user->role_id, [1, 4])) {
            abort(403, 'No autorizado.');
        }

        $request->validate([
            'patient_id' => 'required|exists:patients,id',
            'heart_rate' => 'nullable|integer',
            'blood_pressure' => 'nullable|string|max:10',
            'temperature' => 'nullable|numeric|between:30,45',
            'oxygen_saturation' => 'nullable|numeric|between:0,100',
            'respiratory_rate' => 'nullable|integer',
            'symptoms' => 'required|string',
            'priority' => 'required|in:red,yellow,green,blue,black',
            'notes' => 'nullable|string',
        ]);
    
        TriageEntry::create([
            'patient_id' => $request->patient_id,
            'nurse_id' => auth()->id(),
            'heart_rate' => $request->heart_rate,
            'blood_pressure' => $request->blood_pressure,
            'temperature' => $request->temperature,
            'oxygen_saturation' => $request->oxygen_saturation,
            'respiratory_rate' => $request->respiratory_rate,
            'symptoms' => $request->symptoms,
            'priority' => $request->priority,
            'notes' => $request->notes,
        ]);

        return redirect()->route('triage_entries.index')->with('success', 'Triaje creado correctamente.');
    }
    
    public function edit(TriageEntry $triageEntry)
    {
        $user = Auth::user();
        if (!in_array($user->role_id, [1, 4])) {
            abort(403, 'No autorizado.');
        }

        return view('triage_entries.edit', compact('triageEntry'));
    }

    public function update(Request $request, TriageEntry $triageEntry)
    {
        $user = Auth::user();
        if (!in_array($user->role_id, [1, 4])) {
            abort(403, 'No autorizado.');
        }

        $request->validate([
            'heart_rate' => 'nullable|integer',
            'blood_pressure' => 'nullable|string|max:10',
            'temperature' => 'nullable|numeric|between:30,45',
            'oxygen_saturation' => 'nullable|numeric|between:0,100',
            'respiratory_rate' => 'nullable|integer',
            'symptoms' => 'required|string',
            'priority' => 'required|in:red,yellow,green,blue,black',
            'notes' => 'nullable|string',
        ]);
    
        $triageEntry->update($request->all());
    
        return redirect()->route('triage_entries.index')->with('success', 'Entrada de triaje actualizada exitosamente.');
    }

    public function destroy(TriageEntry $triageEntry)
    {
        $user = Auth::user();
        if ($user->role_id !== 1) {
            abort(403, 'No autorizado.');
        }

        $triageEntry->delete();
        return redirect()->route('triage_entries.index')->with('success', 'Entrada de triaje eliminada exitosamente.');
    }
    
    public function searchForm()
    {
        $user = Auth::user();
        if (!in_array($user->role_id, [1, 4])) {
            abort(403, 'No autorizado.');
        }

        return view('triage_entries.search');
    }
}
