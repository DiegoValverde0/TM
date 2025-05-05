@extends('layouts.app')

@section('content')
<div class="container">
    <h1 class="my-4">Registros de Triaje</h1>

    @php
        $user = auth()->user();
    @endphp

    {{-- Botón de creación solo visible para roles 1 y 4 --}}
    @if(in_array($user->role_id, [1, 4]))
        <a href="{{ route('triage_entries.create') }}" class="btn btn-primary mb-3">Nuevo Triaje</a>
    @endif

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <table class="table table-striped">
        <thead>
            <tr>
                <th>Paciente</th>
                <th>Enfermero</th>
                <th>Síntomas</th>
                <th>Prioridad</th>
                <th>Fecha</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            @foreach($triageEntries as $triage)
            <tr>
                <td>{{ $triage->patient->name }}</td>
                <td>{{ $triage->nurse->name }}</td>
                <td>{{ Str::limit($triage->symptoms, 50) }}</td>
                <td>
                    <span class="badge 
                        {{ $triage->priority == 'red' ? 'bg-danger' : 
                           ($triage->priority == 'yellow' ? 'bg-warning' : 
                           ($triage->priority == 'green' ? 'bg-success' : 'bg-primary')) }}">
                        {{ strtoupper($triage->priority) }}
                    </span>
                </td>
                <td>{{ $triage->created_at->format('d/m/Y H:i') }}</td>
                <td>
                    {{-- Ver detalles: disponible para todos --}}
                    <a href="{{ route('triage_entries.show', $triage->id) }}" class="btn btn-sm btn-info">Ver</a>

                    {{-- Editar: solo para roles 1 y 4 --}}
                    @if(in_array($user->role_id, [1, 4]))
                        <a href="{{ route('triage_entries.edit', $triage->id) }}" class="btn btn-sm btn-warning">Editar</a>
                    @endif

                    {{-- Eliminar: solo para administrador --}}
                    @if($user->role_id === 1)
                    <form action="{{ route('triage_entries.destroy', $triage->id) }}" method="POST" style="display:inline-block;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('¿Seguro que deseas eliminar este registro?')">Eliminar</button>
                    </form>
                    @endif
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
