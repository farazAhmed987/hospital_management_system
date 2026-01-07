<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Patient extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'blood_group',
        'allergies',
        'medical_history',
        'current_medications',
        'emergency_contact_name',
        'emergency_contact_phone',
        'insurance_provider',
        'insurance_policy_number',
    ];

    /**
     * Get the user that owns the patient profile.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the appointments for the patient.
     */
    public function appointments()
    {
        return $this->hasMany(Appointment::class);
    }

    /**
     * Get the patient's full name.
     */
    public function getNameAttribute(): string
    {
        return $this->user->name ?? 'Unknown';
    }

    /**
     * Get the patient's email.
     */
    public function getEmailAttribute(): string
    {
        return $this->user->email ?? 'Unknown';
    }

    /**
     * Get the patient's phone.
     */
    public function getPhoneAttribute(): string
    {
        return $this->user->phone ?? 'N/A';
    }

    /**
     * Get the patient's age.
     */
    public function getAgeAttribute(): ?int
    {
        if ($this->user->date_of_birth) {
            return $this->user->date_of_birth->age;
        }
        return null;
    }

    /**
     * Get upcoming appointments.
     */
    public function upcomingAppointments()
    {
        return $this->appointments()
            ->where('appointment_date', '>=', now()->toDateString())
            ->whereIn('status', ['pending', 'approved'])
            ->orderBy('appointment_date')
            ->orderBy('appointment_time');
    }

    /**
     * Get past appointments (completed or cancelled).
     */
    public function pastAppointments()
    {
        return $this->appointments()
            ->where(function ($query) {
                $query->where('appointment_date', '<', now()->toDateString())
                    ->orWhereIn('status', ['completed', 'cancelled', 'rejected', 'no_show']);
            })
            ->orderBy('appointment_date', 'desc')
            ->orderBy('appointment_time', 'desc');
    }
}
