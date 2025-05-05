<div class="card shadow-sm rounded-4 p-4 mb-4">
    <h4 class="mb-4">Datos del Paciente</h4>

    <form action="{{ route('patients.store') }}" method="POST">
        @csrf

        <div class="mb-3">
            <label for="name" class="form-label fw-semibold">Nombre</label>
            <input type="text" name="name" id="name" class="form-control rounded-3" value="{{ old('name') }}" required>
        </div>

        <div class="mb-3">
            <label for="email" class="form-label fw-semibold">Correo Electrónico</label>
            <input type="email" name="email" id="email" class="form-control rounded-3" value="{{ old('email') }}" required>
        </div>

        <div class="mb-3">
            <label for="gender" class="form-label fw-semibold">Género</label>
            <select name="gender" id="gender" class="form-select rounded-3" required>
                <option value="">Seleccione</option>
                <option value="male" {{ old('gender') == 'male' ? 'selected' : '' }}>Masculino</option>
                <option value="female" {{ old('gender') == 'female' ? 'selected' : '' }}>Femenino</option>
                <option value="other" {{ old('gender') == 'other' ? 'selected' : '' }}>Otro</option>
            </select>
        </div>

        <div class="mb-3">
            <label for="age" class="form-label fw-semibold">Edad</label>
            <input type="number" name="age" id="age" class="form-control rounded-3" value="{{ old('age') }}" required>
        </div>

        <div class="mb-3">
            <label for="identification_number" class="form-label fw-semibold">Número de Identificación</label>
            <input type="text" name="identification_number" id="identification_number" class="form-control rounded-3" value="{{ old('identification_number') }}" required>
        </div>

        <div class="mb-3">
            <label for="phone" class="form-label fw-semibold">Teléfono</label>
            <input type="text" name="phone" id="phone" class="form-control rounded-3" value="{{ old('phone') }}">
        </div>

        <div class="mb-3">
            <label for="address" class="form-label fw-semibold">Dirección</label>
            <textarea name="address" id="address" class="form-control rounded-3" rows="3">{{ old('address') }}</textarea>
        </div>

        <div class="d-flex justify-content-end">
            <button type="submit" class="btn btn-primary rounded-3 px-4">Guardar</button>
        </div>
    </form>
</div>
