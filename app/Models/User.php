<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role_id', 
    ];
    

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'role' => 'string',
    ];

    public function triageEntries() {
        return $this->hasMany(TriageEntry::class, 'nurse_id');
    }

    public function medicalCares() {
        return $this->hasMany(MedicalCare::class, 'receptionist_id');
    }
    
    public function specialty() {
        return $this->belongsTo(Specialty::class);
    }   
    public function role()
    {
        return $this->belongsTo(Role::class);
    }
    
    public function hasRole($roleName)
    {
        return $this->role && $this->role->name === $roleName;
    }
    public function doctor()
    {
        return $this->hasOne(Doctor::class);
    }
    public function patient()
    {
        return $this->hasOne(Patient::class);
    }

}