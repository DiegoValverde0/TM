@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>Historial Médico</h1>
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Paciente</th>
                    <th>Condición</th>
                    <th>Tratamiento</th>
                    <th>Fecha</th>
                    <th>Detalles</th>
                    @if(auth()->user()->role_id == 1) <!-- Solo para administradores -->
                        <th>Acciones</th>
                    @endif
                </tr>
            </thead>
            <tbody>
                @foreach($medicalHistories as $history)
                <tr>
                    <td>{{ $history->patient->name }}</td>
                    <td>{{ Str::limit($history->condition, 50) }}</td>
                    <td>{{ Str::limit($history->treatment, 50) }}</td>
                    <td>{{ $history->date->format('d/m/Y') }}</td>
                    <td>
                        <a href="{{ route('medical_histories.show', $history->id) }}" class="btn btn-sm btn-info">Detalles</a>
                    </td>
                    @if(auth()->user()->role_id == 1) <!-- Solo para administradores -->
                        <td>
                            <a href="{{ route('medical_histories.edit', $history->id) }}" class="btn btn-sm btn-warning">Editar</a>
                            <form action="{{ route('medical_histories.destroy', $history->id) }}" method="POST" style="display:inline-block;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('¿Seguro que deseas eliminar este historial?')">Eliminar</button>
                            </form>
                        </td>
                    @endif
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection