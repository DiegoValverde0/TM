@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>Editar Historial Médico</h1>

        <form action="{{ route('medical_histories.update', $medicalHistory->id) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="mb-3">
                <label for="patient_id" class="form-label">Paciente</label>
                <input type="text" class="form-control" value="{{ $medicalHistory->patient->name }}" readonly>
            </div>

            <div class="mb-3">
                <label for="condition" class="form-label">Condición</label>
                <input type="text" name="condition" class="form-control" value="{{ $medicalHistory->condition }}" required @if(auth()->user()->role_id != 1) readonly @endif>
            </div>

            <div class="mb-3">
                <label for="treatment" class="form-label">Tratamiento</label>
                <input type="text" name="treatment" class="form-control" value="{{ $medicalHistory->treatment }}" required @if(auth()->user()->role_id != 1) readonly @endif>
            </div>

            <div class="mb-3">
                <label for="date" class="form-label">Fecha</label>
                <input type="date" name="date" class="form-control" value="{{ $medicalHistory->date->format('Y-m-d') }}" required @if(auth()->user()->role_id != 1) readonly @endif>
            </div>

            @if(auth()->user()->role_id == 1) <!-- Solo para administradores -->
                <button type="submit" class="btn btn-primary">Actualizar Historial</button>
            @endif
        </form>

        <a href="{{ route('medical_histories.index') }}" class="btn btn-secondary mt-3">Regresar al Historial</a>
    </div>
@endsection