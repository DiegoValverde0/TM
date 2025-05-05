@extends('layouts.app')

@section('content')
<div class="container py-4">
    @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    @if (session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
    @endif

    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-sm">
                <div class="card-body">
                    <h2 class="card-title text-success">Diagnóstico Preliminar</h2>

                    <h4>Síntomas Identificados:</h4>
                    <ul class="list-group mb-4">
                        @foreach ($diagnosis as $symptom)
                            <li class="list-group-item">
                                <strong>{{ $symptom['name'] }}:</strong> {{ $symptom['common_name'] }}
                            </li>
                        @endforeach
                    </ul>

                    <h4>Recomendación de Especialidad:</h4>
                    <p>Consulta con un <strong>{{ $specialist }}</strong>.</p>

                    <form action="{{ route('diagnosis.requestMedicalAttention') }}" method="POST">
    @csrf
    <!-- Concatenar los nombres de los síntomas en un solo string -->
    <input type="hidden" name="symptoms" value="{{ implode(', ', array_column($diagnosis, 'name')) }}">
    <button type="submit" class="btn btn-primary">Solicitar Atención Médica</button>
</form>

                    <a href="{{ route('diagnosis.showForm') }}" class="btn btn-outline-secondary">Volver a intentar con otros síntomas</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection