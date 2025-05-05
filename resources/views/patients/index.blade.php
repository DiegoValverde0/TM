@extends('layouts.app')

@section('content')
<div class="container">
    <h1 class="my-4">Registros de Pacientes</h1>

    <!-- Formulario de búsqueda en la misma página -->
    <form method="GET" action="{{ route('patients.index') }}" class="mb-4">
        <div class="form-group">
            <input type="text" name="search" class="form-control" placeholder="Buscar por nombre o número de documento" value="{{ request('search') }}">
        </div>
    </form>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @if(in_array(Auth::user()->role_id, [1, 4])) 
        <a href="{{ route('patients.create') }}" class="btn btn-primary mb-3">Nuevo Paciente</a>
    @endif

    <table class="table table-striped">
        <thead>
            <tr>
                <th>Nombre</th>
                <th>Género</th>
                <th>Edad</th>
                <th>Número de Identificación</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            @foreach($patients as $patient)
            <tr>
                <td>{{ $patient->name }}</td>
                <td>{{ ucfirst($patient->gender) }}</td>
                <td>{{ $patient->age }}</td>
                <td>{{ $patient->identification_number }}</td>
                <td>
                    @if(in_array(Auth::user()->role_id, [1])) 
                        <a href="{{ route('patients.edit', $patient->id) }}" class="btn btn-sm btn-warning">Editar</a>

                        <form action="{{ route('patients.destroy', $patient->id) }}" method="POST" style="display:inline-block;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('¿Seguro que deseas eliminar este paciente?')">Eliminar</button>
                        </form>
                    @endif

                    @if(in_array(Auth::user()->role_id, [1, 2, 4])) 
                        <a href="{{ route('patients.show', $patient->id) }}" class="btn btn-sm btn-info">Ver detalles</a>
                    @endif
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
