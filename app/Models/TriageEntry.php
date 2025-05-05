<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TriageEntry extends Model {
    protected $fillable = [
        'patient_id', 
        'nurse_id', 
        'heart_rate', 
        'blood_pressure', 
        'temperature', 
        'oxygen_saturation', 
        'respiratory_rate', 
        'symptoms', 
        'priority', 
        'notes'
    ];

    public function patient() {
        return $this->belongsTo(Patient::class);
    }

    public function nurse() {
        return $this->belongsTo(User::class, 'nurse_id');
    }

    public function medicalCares() {
        return $this->hasMany(MedicalCare::class);
    }
}