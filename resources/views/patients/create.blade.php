@extends('layouts.app')

@section('content')
<div class="container">
    <h1 class="my-4">Nuevo Paciente</h1>

    <form action="{{ route('patients.store') }}" method="POST">
        @csrf

        <div class="mb-3">
            <label for="name" class="form-label">Nombre</label>
            <input type="text" class="form-control" id="name" name="name" required>
        </div>

        <div class="mb-3">
            <label for="email" class="form-label">Correo Electrónico</label>
            <input type="email" class="form-control" id="email" name="email" required>
        </div>

        <div class="mb-3">
            <label for="gender" class="form-label">Género</label>
            <select class="form-control" id="gender" name="gender" required>
                <option value="male">Masculino</option>
                <option value="female">Femenino</option>
            </select>
        </div>

        <div class="mb-3">
            <label for="age" class="form-label">Edad</label>
            <input type="number" class="form-control" id="age" name="age" required min="1">
        </div>

        <div class="mb-3">
            <label for="identification_number" class="form-label">Número de Identificación</label>
            <input type="text" class="form-control" id="identification_number" name="identification_number" required>
        </div>

        <div class="mb-3">
            <label for="phone" class="form-label">Teléfono</label>
            <input type="text" class="form-control" id="phone" name="phone">
        </div>

        <div class="mb-3">
            <label for="address" class="form-label">Dirección</label>
            <textarea class="form-control" id="address" name="address"></textarea>
        </div>

        <button type="submit" class="btn btn-primary">Crear Paciente</button>
    </form>
</div>
@endsection
