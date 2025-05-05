@extends('layouts.app')

@section('content')
<div class="container">
    <h1 class="my-4">Detalle del Triaje</h1>

    <div class="card">
        <div class="card-body">
            <h5 class="card-title">Paciente: {{ $triageEntry->patient->name }}</h5>
            <h6 class="card-subtitle mb-2 text-muted">Enfermero: {{ $triageEntry->nurse->name }}</h6>

            <hr>

            <p><strong>Frecuencia cardíaca:</strong> {{ $triageEntry->heart_rate ?? 'N/A' }} bpm</p>
            <p><strong>Presión arterial:</strong> {{ $triageEntry->blood_pressure ?? 'N/A' }}</p>
            <p><strong>Temperatura:</strong> {{ $triageEntry->temperature ?? 'N/A' }} °C</p>
            <p><strong>Saturación de oxígeno:</strong> {{ $triageEntry->oxygen_saturation ?? 'N/A' }}%</p>
            <p><strong>Frecuencia respiratoria:</strong> {{ $triageEntry->respiratory_rate ?? 'N/A' }} rpm</p>
            <p><strong>Síntomas:</strong> {{ $triageEntry->symptoms }}</p>
            <p><strong>Prioridad:</strong> 
                <span class="badge 
                    {{ $triageEntry->priority == 'red' ? 'bg-danger' : 
                       ($triageEntry->priority == 'yellow' ? 'bg-warning' : 
                       ($triageEntry->priority == 'green' ? 'bg-success' : 'bg-primary')) }}">
                    {{ strtoupper($triageEntry->priority) }}
                </span>
            </p>
            <p><strong>Notas:</strong> {{ $triageEntry->notes ?? 'N/A' }}</p>
            <p><strong>Fecha de registro:</strong> {{ $triageEntry->created_at->format('d/m/Y H:i') }}</p>
        </div>
    </div>

    <a href="{{ route('triage_entries.index') }}" class="btn btn-secondary mt-3">Volver</a>
</div>
@endsection
