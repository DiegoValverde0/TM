<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Prescription extends Model {
    protected $fillable = ['medical_care_id', 'medication', 'dosage', 'instructions'];

    public function medicalCare() {
        return $this->belongsTo(MedicalCare::class);
    }
}