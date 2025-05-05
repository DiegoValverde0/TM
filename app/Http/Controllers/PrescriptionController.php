<?php

namespace App\Http\Controllers;

use App\Models\Prescription;
use App\Models\MedicalCare;
use Illuminate\Http\Request;

class PrescriptionController extends Controller
{
    public function index()
    {
        $prescriptions = Prescription::all();
        return view('prescriptions.index', compact('prescriptions'));
    }

    public function create($medicalCareId)
    {
        $medicalCare = MedicalCare::findOrFail($medicalCareId);
        return view('prescriptions.create', compact('medicalCare'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'medication' => 'required|string',
            'dosage' => 'required|string',
            'instructions' => 'required|string',
            'medical_care_id' => 'required|exists:medical_cares,id',
        ]);

        Prescription::create([
            'medical_care_id' => $request->medical_care_id,
            'medication' => $request->medication,
            'dosage' => $request->dosage,
            'instructions' => $request->instructions,
        ]);

        return redirect()->route('prescriptions.index')->with('success', 'Receta creada correctamente.');
    }

    public function edit(Prescription $prescription)
    {
        $medicalCares = MedicalCare::all();
        return view('prescriptions.edit', compact('prescription', 'medicalCares'));
    }

    public function update(Request $request, Prescription $prescription)
    {
        $validated = $request->validate([
            'medical_care_id' => 'required|exists:medical_cares,id',
            'medication' => 'required|string|max:255',
            'dosage' => 'required|string|max:255',
            'instructions' => 'required|string|max:255',
        ]);

        $prescription->update($validated);

        return redirect()->route('prescriptions.index')->with('success', 'Receta actualizada correctamente.');
    }
}