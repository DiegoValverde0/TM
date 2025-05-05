@extends('layouts.app')

@section('content')
<div class="container">
    <h1 class="my-4">Detalles del Paciente</h1>

    <!-- Mostrar los detalles del paciente -->
    <div class="card">
        <div class="card-header">
            <h3>{{ $patient->name }}</h3>
        </div>
        <div class="card-body">
            <p><strong>Género:</strong> {{ ucfirst($patient->gender) }}</p>
            <p><strong>Edad:</strong> {{ $patient->age }}</p>
            <p><strong>Número de Identificación:</strong> {{ $patient->identification_number }}</p>
            <p><strong>Teléfono:</strong> {{ $patient->phone ?? 'No disponible' }}</p>
            <p><strong>Dirección:</strong> {{ $patient->address ?? 'No disponible' }}</p>
        </div>
    </div>

    <!-- Botones de acción basados en el rol del usuario -->
    <div class="mt-3">
        @if(in_array(Auth::user()->role_id, [1, 3])) 
            <a href="{{ route('patients.edit', $patient->id) }}" class="btn btn-warning">Editar</a>

            <form action="{{ route('patients.destroy', $patient->id) }}" method="POST" style="display:inline-block;">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-danger" onclick="return confirm('¿Seguro que deseas eliminar este paciente?')">Eliminar</button>
            </form>
        @endif
        <a href="{{ route('medical_histories.index', ['patient_id' => $patient->id]) }}" class="btn btn-info">
        Ver Historial Médico
        </a>
        <a href="{{ route('patients.index') }}" class="btn btn-secondary">Volver al listado</a>
    </div>
</div>
@endsection
