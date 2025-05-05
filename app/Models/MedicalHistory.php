<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MedicalHistory extends Model {
    protected $fillable = ['patient_id', 'condition', 'treatment', 'date','medical_care_id',];

    // Asegúrate de que el campo `date` se convierta a un objeto Carbon
    protected $casts = [
        'date' => 'datetime',
    ];

    public function patient() {
        return $this->belongsTo(Patient::class);
    }
    public function medicalCare()
    {
        return $this->belongsTo(MedicalCare::class);
    }
}