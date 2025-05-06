<div class="card shadow-sm rounded-4 p-4 mb-4">
    <h4 class="mb-4">Datos del Médico</h4>

    <div class="mb-3">
        <label for="name" class="form-label fw-semibold">Nombre Completo</label>
        <input type="text" name="name" id="name" class="form-control rounded-3" 
            value="{{ old('name', $doctor->user->name ?? '') }}" required>
    </div>

    <div class="mb-3">
        <label for="email" class="form-label fw-semibold">Correo Electrónico</label>
        <input type="email" name="email" id="email" class="form-control rounded-3" 
            value="{{ old('email', $doctor->user->email ?? '') }}" required>
    </div>

    <div class="mb-3">
        <label for="specialty_id" class="form-label fw-semibold">Especialidad</label>
        <select name="specialty_id" id="specialty_id" class="form-select rounded-3" required>
            <option value="">Seleccione</option>
            @foreach($specialties as $specialty)
                <option value="{{ $specialty->id }}" 
                    {{ old('specialty_id', $doctor->specialty_id ?? '') == $specialty->id ? 'selected' : '' }}>
                    {{ $specialty->name }}
                </option>
            @endforeach
        </select>
    </div>

</div>
