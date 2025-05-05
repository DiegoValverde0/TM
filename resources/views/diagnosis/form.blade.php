@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-sm">
                <div class="card-body">
                    <h2 class="card-title text-primary">Formulario de Diagnóstico</h2>

                    @if(session('error'))
                        <div class="alert alert-danger">{{ session('error') }}</div>
                    @endif

                    <form method="POST" action="{{ route('diagnosis.process') }}">
                        @csrf
                        <div class="mb-3">
                            <label for="symptoms" class="form-label">Describe tus síntomas:</label>
                            <input type="text" name="symptoms" id="symptoms" class="form-control" required placeholder="Ej: dolor de cabeza, fiebre, etc.">
                        </div>
                        <button type="submit" class="btn btn-primary w-100">Consultar</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection