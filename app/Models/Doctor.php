<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Doctor extends Model {
    protected $fillable = ['user_id', 'specialty_id'];

    public function user() {
        return $this->belongsTo(User::class);
    }

    public function specialty() {
        return $this->belongsTo(Specialty::class);
    }

    public function medicalCares() {
        return $this->hasMany(MedicalCare::class);
    }
}