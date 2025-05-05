@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="card mb-4">
            <div class="card-header">
                <h2>Detalles de la Atención Médica</h2>
            </div>
            <div class="card-body">
                <div class="row mb-3">
                    <div class="col-md-4"><strong>Doctor:</strong></div>
                    <div class="col-md-8">{{ $medicalCare->doctor->user->name }}</div>
                </div>
                <div class="row mb-3">
                    <div class="col-md-4"><strong>Diagnóstico:</strong></div>
                    <div class="col-md-8">{{ $medicalCare->diagnosis }}</div>
                </div>
                <div class="row mb-3">
                    <div class="col-md-4"><strong>Tratamiento:</strong></div>
                    <div class="col-md-8">{{ $medicalCare->treatment }}</div>
                </div>
                <div class="row mb-3">
                    <div class="col-md-4"><strong>Estado:</strong></div>
                    <div class="col-md-8">{{ ucfirst($medicalCare->status) }}</div>
                </div>
            </div>
        </div>

        @if(auth()->user()->role_id == 2)
            <div class="card mb-4">
                <div class="card-header"><strong>Cambiar Estado</strong></div>
                <div class="card-body">
                    <form action="{{ route('medical_cares.update', $medicalCare) }}" method="POST">
                        @csrf
                        @method('PATCH')

                        <div class="mb-3">
                            <label for="status" class="form-label"><strong>Seleccionar nuevo estado</strong></label>
                            <select name="status" id="status" class="form-control" required>
                                <option value="pending" {{ $medicalCare->status == 'pending' ? 'selected' : '' }}>Pendiente</option>
                                <option value="completed" {{ $medicalCare->status == 'completed' ? 'selected' : '' }}>Completado</option>
                                <option value="referred" {{ $medicalCare->status == 'referred' ? 'selected' : '' }}>Referido</option>
                            </select>
                        </div>

                        <button type="submit" class="btn btn-success">Actualizar Estado</button>
                    </form>
                </div>
            </div>
        @endif
    </div>
@endsection