<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PatientController;
use App\Http\Controllers\TriageEntryController;
use App\Http\Controllers\MedicalCareController;
use App\Http\Controllers\SpecialtyController;
use App\Http\Controllers\DoctorController;
use App\Http\Controllers\MedicalHistoryController;
use App\Http\Controllers\PrescriptionController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\DiagnosisController;

use Illuminate\Support\Facades\Route;


Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

// Ruta para el índice de usuarios, restringida a administradores
Route::get('users', function () {
    $user = Auth::user();
    
    if (!$user || $user->role_id !== 1) {
        return response()->view('errors.403', [], 403);
    }
    
    $users = \App\Models\User::all();
    return view('users.index', compact('users'));
})->name('users.index');

Route::middleware('auth')->group(function () {
    // Rutas personalizadas

    Route::post('/triage_entries/find-patient', [TriageEntryController::class, 'findPatient'])->name('triage_entries.findPatient');

    // Rutas de recursos
    Route::resource('patients', PatientController::class);
    Route::resource('prescriptions', PrescriptionController::class)->except(['create']);
    Route::resource('triage_entries', TriageEntryController::class);
    Route::resource('specialties', SpecialtyController::class);
    Route::resource('doctors', DoctorController::class);
    Route::resource('medical_histories', MedicalHistoryController::class);
    
    // Rutas para MedicalCare
    Route::resource('medical_cares', MedicalCareController::class); // Incluye todas las rutas

    Route::get('/diagnosis', [DiagnosisController::class, 'showForm'])->name('diagnosis.showForm');
    Route::post('/diagnosis', [DiagnosisController::class, 'processSymptoms'])->name('diagnosis.process');



    Route::post('/diagnosis/request-medical-attention', [DiagnosisController::class, 'requestMedicalAttention'])->name('diagnosis.requestMedicalAttention');
    
    // Perfil
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';