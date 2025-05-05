@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>Crear Atención Médica</h1>
        <form action="{{ route('medical_cares.store') }}" method="POST">
            @csrf
            
            <div class="mb-3">
                <label for="triage_entry_id" class="form-label">Triaje</label>
                <select id="triage_entry_id" name="triage_entry_id" class="form-control" required>
                    <option value="">Selecciona un triaje</option>
                    @foreach ($triageEntries as $triageEntry)
                        @php
                            // Acortar la descripción del síntoma a 20 caracteres
                            $shortDescription = strlen($triageEntry->symptoms) > 50 
                                ? substr($triageEntry->symptoms, 0, 50) . '...' 
                                : $triageEntry->symptoms;
                            // Concatenar ID, nombre del paciente y descripción del síntoma
                            $optionLabel = "{$triageEntry->id} - {$triageEntry->patient->name} - {$shortDescription}";
                        @endphp
                        <option value="{{ $triageEntry->id }}">
                            {{ $optionLabel }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="mb-3">
                <label for="doctor_id" class="form-label">Doctor</label>
                <select id="doctor_id" name="doctor_id" class="form-control" required>
                    <option value="">Selecciona un doctor</option>
                    @foreach ($doctors as $doctor)
                        <option value="{{ $doctor->id }}">
                            {{ $doctor->user->name }} - {{ $doctor->specialty->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <button type="submit" class="btn btn-primary">Crear Atención</button>
        </form>
    </div>
@endsection