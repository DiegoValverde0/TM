<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Patient;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class PatientController extends Controller
{
    public function index(Request $request)
    {
        $user = auth()->user();

        // Verifica el rol del usuario y ajusta el comportamiento
        if ($user->role_id == 5) {
            // Si es paciente, solo muestra su propio registro
            $patients = Patient::where('user_id', $user->id)->get();
        } else {
            // Si el rol es admin, doctor o recepcionista, muestra todos los pacientes
            $patients = Patient::query();

            // Búsqueda por nombre o número de documento de identidad
            if ($request->has('search')) {
                $search = $request->input('search');
                $patients->where(function($query) use ($search) {
                    $query->where('name', 'like', "%$search%")
                          ->orWhere('identification_number', 'like', "%$search%");
                });
            }

            // Obtener todos los pacientes con los filtros de búsqueda aplicados
            $patients = $patients->get();
        }

        return view('patients.index', compact('patients'));
    }

    public function show(Patient $patient)
    {
        $user = auth()->user();

        // Si el usuario es un paciente, solo puede ver su propio registro
        if ($user->role_id == 5 && $patient->user_id !== $user->id) {
            abort(403, 'No autorizado a ver este registro.');
        }

        return view('patients.show', compact('patient'));
    }

    public function create()
    {
        $user = Auth::user();

        // Solo administradores y recepcionistas pueden crear pacientes
        if (!in_array($user->role_id, [1, 4])) {
            abort(403, 'No autorizado.');
        }

        return view('patients.create');
    }

    public function store(Request $request)
    {
        $user = Auth::user();

        // Solo administradores y recepcionistas pueden almacenar pacientes
        if (!in_array($user->role_id, [1, 4])) {
            abort(403, 'No autorizado.');
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'gender' => 'required|string',
            'age' => 'required|integer|min:1',
            'identification_number' => 'required|string|max:20',
            'phone' => 'nullable|string|max:15',
            'address' => 'nullable|string',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make('Aa1234567*'),
            'role_id' => 5, // Rol de paciente
        ]);

        Patient::create([
            'user_id' => $user->id,
            'name' => $request->name,
            'gender' => $request->gender,
            'age' => $request->age,
            'identification_number' => $request->identification_number,
            'phone' => $request->phone,
            'address' => $request->address,
        ]);

        return redirect()->route('patients.index')->with('success', 'Paciente creado correctamente.');
    }

    public function edit(Patient $patient)
    {
        $user = Auth::user();

        // Solo administradores y recepcionistas pueden editar pacientes
        if (!in_array($user->role_id, [1, 4])) {
            abort(403, 'No autorizado.');
        }

        return view('patients.edit', compact('patient'));
    }

    public function update(Request $request, Patient $patient)
    {
        $user = Auth::user();

        // Solo administradores y recepcionistas pueden actualizar pacientes
        if (!in_array($user->role_id, [1, 4])) {
            abort(403, 'No autorizado.');
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $patient->user_id,
            'age' => 'required|integer|min:1',
        ]);

        $patient->user->update([
            'name' => $request->name,
            'email' => $request->email,
        ]);

        $patient->update(['age' => $request->age]);

        return redirect()->route('patients.index')->with('success', 'Paciente actualizado correctamente.');
    }

    public function destroy(Patient $patient)
    {
        $user = Auth::user();

        // Solo administradores pueden eliminar pacientes
        if ($user->role_id !== 1) {
            abort(403, 'No autorizado.');
        }

        $patient->user->delete();
        $patient->delete();
        return redirect()->route('patients.index')->with('success', 'Paciente eliminado correctamente.');
    }
}
