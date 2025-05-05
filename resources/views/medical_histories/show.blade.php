@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>Detalles del Historial Médico</h1>
        <div class="card">
            <div class="card-body">
                <div class="mb-3">
                    <strong>Fecha:</strong> {{ $medicalHistory->date->format('d/m/Y') }}
                </div>
                <div class="mb-3">
                    <strong>Paciente:</strong> {{ $medicalHistory->patient->name ?? 'No disponible' }}
                </div>
                <div class="mb-3">
                    <strong>Médico:</strong> {{ $medicalHistory->medicalCare?->doctor?->user?->name ?? 'No disponible' }}
                </div>
                <div class="mb-3">
                    <strong>Especialidad:</strong> {{ $medicalHistory->medicalCare?->doctor?->specialty?->name ?? 'No disponible' }}
                </div>
                <div class="mb-3">
                    <strong>Condición:</strong> {{ $medicalHistory->condition ?? 'No disponible' }}
                </div>
                <div class="mb-3">
                    <strong>Tratamiento:</strong> {{ $medicalHistory->treatment ?? 'No disponible' }}
                </div>
                <div class="mb-3">
                    <strong>Estado:</strong> {{ $medicalHistory->medicalCare?->status ?? 'No disponible' }}
                </div>
                <a href="{{ route('medical_histories.index') }}" class="btn btn-secondary mt-3">Regresar al Historial</a>
            </div>
        </div>
    </div>
@endsection