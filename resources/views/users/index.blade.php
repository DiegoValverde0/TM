@extends('layouts.app')

@section('content')
<div class="container">
    <h1 class="my-4">Usuarios del Sistema</h1>

    @can('create', App\Models\User::class)  <!-- Solo los administradores pueden ver el botón -->
        <a href="{{ route('users.create') }}" class="btn btn-primary mb-3">Nuevo Usuario</a>
    @endcan

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <table class="table table-striped">
        <thead>
            <tr>
                <th>Nombre</th>
                <th>Correo Electrónico</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            @foreach($users as $user)
            <tr>
                <td>{{ $user->name }}</td>
                <td>{{ $user->email }}</td>
                <td>
                    @can('update', $user)  <!-- Solo los administradores pueden editar -->
                        <a href="{{ route('users.edit', $user->id) }}" class="btn btn-sm btn-warning">Editar</a>
                    @endcan

                    @can('delete', $user)  <!-- Solo los administradores pueden eliminar -->
                        <form action="{{ route('users.destroy', $user->id) }}" method="POST" style="display:inline-block;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('¿Seguro que deseas eliminar este usuario?')">Eliminar</button>
                        </form>
                    @endcan
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
