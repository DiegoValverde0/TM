@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>Atenciones Médicas</h1>

        @if(auth()->user()->role_id != 2)
            <a href="{{ route('medical_cares.create') }}" class="btn btn-primary">Agregar Atención</a>
        @endif

        <table class="table mt-4">
            <thead>
                <tr>
                    <th>Triaje</th>
                    <th>Doctor</th>
                    <th>Diagnóstico</th>
                    <th>Tratamiento</th>
                    <th>Estado</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($medicalCares as $medicalCare)
                    <tr>
                        <td>{{ $medicalCare->triageEntry->id }}</td>
                        <td>{{ $medicalCare->doctor->user->name }}</td>
                        <td>{{ $medicalCare->diagnosis }}</td>
                        <td>{{ $medicalCare->treatment }}</td>
                        <td>{{ $medicalCare->status }}</td>
                        <td>
                            <a href="{{ route('medical_cares.show', $medicalCare) }}" class="btn btn-info btn-sm">Ver Detalles</a>

                            @if(auth()->user()->role_id == 1 || (auth()->user()->role_id == 2 && $medicalCare->doctor_id == auth()->user()->doctor->id))
                                <a href="{{ route('medical_cares.edit', $medicalCare) }}" class="btn btn-warning btn-sm">Editar</a>
                            @endif

                            @if(auth()->user()->role_id == 1) <!-- Solo Administrador puede eliminar -->
                                <form action="{{ route('medical_cares.destroy', $medicalCare) }}" method="POST" style="display:inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm">Eliminar</button>
                                </form>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection