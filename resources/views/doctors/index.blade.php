@extends('layouts.app')

@section('content')
<div class="container">
    <h1 class="my-4">Registros de Médicos</h1>

    <!-- Barra de búsqueda -->
    <form method="GET" class="mb-3 row g-2">
    <div class="col-md-6">
        <input type="text" name="search" value="{{ request('search') }}" class="form-control" placeholder="Buscar por nombre o email...">
    </div>
    <div class="col-md-4">
        <select name="specialty_id" class="form-select">
            <option value="">-- Todas las especialidades --</option>
            @foreach($specialties as $specialty)
                <option value="{{ $specialty->id }}" {{ request('specialty_id') == $specialty->id ? 'selected' : '' }}>
                    {{ $specialty->name }}
                </option>
            @endforeach
        </select>
    </div>
    <div class="col-md-2">
        <button type="submit" class="btn btn-primary w-100">Filtrar</button>
    </div>
</form>


    <!-- Botón para crear (solo admin) -->
    @if(Auth::user()->role_id === 1)
        <a href="{{ route('doctors.create') }}" class="btn btn-primary mb-3">Nuevo Médico</a>
    @endif

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <table class="table table-striped">
        <thead>
            <tr>
                <th>Nombre</th>
                <th>Especialidad</th>
                <th>Email</th>
                @if(Auth::user()->role_id === 1)
                    <th>Acciones</th>
                @endif
            </tr>
        </thead>
        <tbody>
            
            @forelse($doctors as $doctor)
                <tr>
                    <td>{{ $doctor->user->name }}</td>
                    <td>{{ $doctor->specialty->name }}</td>
                    <td>{{ $doctor->user->email }}</td>
                    @if(Auth::user()->role_id === 1)
                        <td>
                            <a href="{{ route('doctors.edit', $doctor->id) }}" class="btn btn-sm btn-warning">Editar</a>
                            <form action="{{ route('doctors.destroy', $doctor->id) }}" method="POST" style="display:inline-block;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('¿Seguro que deseas eliminar este médico?')">Eliminar</button>
                            </form>
                        </td>
                    @endif
                </tr>
            @empty
                <tr>
                    <td colspan="4">No se encontraron médicos.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
