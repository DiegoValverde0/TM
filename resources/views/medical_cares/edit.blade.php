@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>Editar Atención Médica</h1>
        <form action="{{ route('medical_cares.update', $medicalCare) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="mb-3">
                <label for="triage_entry_id" class="form-label">Triaje</label>
                @if(auth()->user()->role_id == 1) <!-- Administrador -->
                    <select name="triage_entry_id" class="form-control" required>
                        @foreach ($triageEntries as $triageEntry)
                            <option value="{{ $triageEntry->id }}" {{ $triageEntry->id == $medicalCare->triage_entry_id ? 'selected' : '' }}>
                                {{ $triageEntry->id }}
                            </option>
                        @endforeach
                    </select>
                @else <!-- Doctor: solo lectura -->
                    <input type="text" class="form-control readonly-input" value="{{ $medicalCare->triageEntry->id }}" readonly>
                @endif
            </div>

            <div class="mb-3">
                <label for="doctor_id" class="form-label">Doctor</label>
                @if(auth()->user()->role_id == 1) <!-- Administrador -->
                    <select name="doctor_id" class="form-control" required>
                        @foreach ($doctors as $doctor)
                            <option value="{{ $doctor->id }}" {{ $doctor->id == $medicalCare->doctor_id ? 'selected' : '' }}>
                                {{ $doctor->user->name }} - {{ $doctor->specialty->name }}
                            </option>
                        @endforeach
                    </select>
                @else <!-- Doctor: solo lectura -->
                    <input type="text" class="form-control readonly-input" value="{{ $medicalCare->doctor->user->name }} - {{ $medicalCare->doctor->specialty->name }}" readonly>
                @endif
            </div>

            <div class="mb-3">
                <label for="diagnosis" class="form-label">Diagnóstico</label>
                <input type="text" name="diagnosis" class="form-control" value="{{ $medicalCare->diagnosis }}" required>
            </div>

            <div class="mb-3">
                <label for="treatment" class="form-label">Tratamiento</label>
                <input type="text" name="treatment" class="form-control" value="{{ $medicalCare->treatment }}" required>
            </div>

            <div class="mb-3">
                <label for="status" class="form-label">Estado</label>
                <select name="status" class="form-control" @if(auth()->user()->role_id != 1) required @endif>
                    <option value="pending" {{ $medicalCare->status == 'pending' ? 'selected' : '' }}>Pendiente</option>
                    <option value="completed" {{ $medicalCare->status == 'completed' ? 'selected' : '' }}>Completado</option>
                </select>
            </div>

            <input type="hidden" name="date" value="{{ $medicalCare->date }}">
            <input type="hidden" name="time" value="{{ $medicalCare->time }}">

            <button type="submit" class="btn btn-primary">Actualizar Atención</button>
        </form>
    </div>
@endsection