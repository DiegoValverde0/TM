@extends('layouts.app')

@section('content')
<div class="container">
    <h1 class="my-4">Nuevo Triaje</h1>

    {{-- Mostrar mensaje de error si no se encuentra el paciente --}}
    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    {{-- Formulario para buscar paciente --}}
    <form action="{{ route('triage_entries.findPatient') }}" method="POST" class="mb-4">
        @csrf
        <div class="input-group">
            <input type="text" name="identification_number" class="form-control" placeholder="Número de identificación" required>
            <button type="submit" class="btn btn-secondary">Buscar Paciente</button>
        </div>
    </form>

    {{-- Formulario de triaje solo si el paciente ha sido encontrado --}}
    @if(isset($patient))
        <form action="{{ route('triage_entries.store') }}" method="POST">
            @csrf

            <input type="hidden" name="patient_id" value="{{ $patient->id }}">

            <div class="mb-3">
                <label class="form-label">Paciente</label>
                <input type="text" class="form-control" value="{{ $patient->name }} ({{ $patient->identification_number }})" disabled>
            </div>

            @include('triage_entries.partials.form')

            <div class="d-flex justify-content-end">
                <button type="submit" class="btn btn-primary px-4">Guardar Triaje</button>
            </div>
        </form>
    @endif
</div>
@endsection
