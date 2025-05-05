<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Patient extends Model
{
    protected $fillable = [
        'name',
        'gender',
        'age',
        'identification_number',
        'phone',
        'address',
        'user_id',  // Asegúrate de incluir esta columna en tu tabla de pacientes
    ];

    // Relación con el modelo User (El paciente tiene un usuario)
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Relación con triage entries
    public function triageEntries()
    {
        return $this->hasMany(TriageEntry::class);
    }

    // Relación con medical visits
    public function medicalVisits()
    {
        return $this->hasManyThrough(MedicalVisit::class, TriageEntry::class);
    }

    // Relación con appointments
    public function appointments()
    {
        return $this->hasMany(Appointment::class);
    }

    // Relación con medical histories
    public function medicalHistories()
    {
        return $this->hasMany(MedicalHistory::class);
    }
}
