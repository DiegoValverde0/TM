<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MedicalCare extends Model {
    protected $fillable = [
        'patient_id',
        'receptionist_id',
        'doctor_id',
        'triage_entry_id',
        'date',
        'time',
        'diagnosis',
        'treatment',
        'status',
        'reason'
    ];

    public function patient() {
        return $this->belongsTo(Patient::class);
    }

    public function receptionist() {
        return $this->belongsTo(User::class, 'receptionist_id');
    }

    public function doctor() {
        return $this->belongsTo(Doctor::class);
    }

    public function triageEntry() {
        return $this->belongsTo(TriageEntry::class);
    }

    public function prescriptions() {
        return $this->hasMany(Prescription::class);
    }
    public function medicalHistories()
    {
        return $this->hasMany(MedicalHistory::class); // Relación con MedicalHistory
    }
}