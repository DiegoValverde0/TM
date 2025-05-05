<?php

namespace App\Http\Controllers;

use App\Models\Doctor;
use App\Models\Specialty;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class DoctorController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
    
        if (!in_array($user->role_id, [1, 2, 4])) {
            abort(403, 'No autorizado.');
        }
    
        $doctors = Doctor::with('user', 'specialty');
        $specialties = Specialty::all(); // Para el filtro
    
        if ($request->filled('search')) {
            $search = $request->input('search');
            $doctors->whereHas('user', function ($query) use ($search) {
                $query->where('name', 'like', "%$search%")
                      ->orWhere('email', 'like', "%$search%");
            });
        }
    
        if ($request->filled('specialty_id')) {
            $doctors->where('specialty_id', $request->input('specialty_id'));
        }
    
        $doctors = $doctors->get();
    
        return view('doctors.index', compact('doctors', 'specialties'));
    }
    

    public function create()
    {
        if (Auth::user()->role_id !== 1) {
            abort(403, 'No autorizado.');
        }

        $specialties = Specialty::all();
        return view('doctors.create', compact('specialties'));
    }

    public function store(Request $request)
    {
        if (Auth::user()->role_id !== 1) {
            abort(403, 'No autorizado.');
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'specialty_id' => 'required|exists:specialties,id',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make('Aa1234567*'),
            'role_id' => 2, // Rol de doctor
        ]);

        Doctor::create([
            'user_id' => $user->id,
            'specialty_id' => $request->specialty_id,
        ]);

        return redirect()->route('doctors.index')->with('success', 'Médico creado correctamente.');
    }

    public function edit(Doctor $doctor)
    {
        if (Auth::user()->role_id !== 1) {
            abort(403, 'No autorizado.');
        }

        $specialties = Specialty::all();
        return view('doctors.edit', compact('doctor', 'specialties'));
    }

    public function update(Request $request, Doctor $doctor)
    {
        if (Auth::user()->role_id !== 1) {
            abort(403, 'No autorizado.');
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $doctor->user_id,
            'specialty_id' => 'required|exists:specialties,id',
        ]);

        $doctor->user->update([
            'name' => $request->name,
            'email' => $request->email,
        ]);

        $doctor->update([
            'specialty_id' => $request->specialty_id,
        ]);

        return redirect()->route('doctors.index')->with('success', 'Médico actualizado correctamente.');
    }

    public function destroy(Doctor $doctor)
    {
        if (Auth::user()->role_id !== 1) {
            abort(403, 'No autorizado.');
        }

        $doctor->user->delete();
        $doctor->delete();

        return redirect()->route('doctors.index')->with('success', 'Médico eliminado correctamente.');
    }
}
