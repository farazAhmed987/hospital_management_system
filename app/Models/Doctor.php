<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Doctor extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'specialization',
        'qualification',
        'experience_years',
        'consultation_fee',
        'bio',
        'license_number',
        'is_available',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'consultation_fee' => 'decimal:2',
        'is_available' => 'boolean',
    ];

    /**
     * Get the user that owns the doctor profile.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the schedules for the doctor.
     */
    public function schedules()
    {
        return $this->hasMany(Schedule::class);
    }

    /**
     * Get the appointments for the doctor.
     */
    public function appointments()
    {
        return $this->hasMany(Appointment::class);
    }

    /**
     * Get active schedules for the doctor.
     */
    public function activeSchedules()
    {
        return $this->hasMany(Schedule::class)->where('is_active', true);
    }

    /**
     * Get the doctor's full name.
     */
    public function getNameAttribute(): string
    {
        return $this->user->name ?? 'Unknown';
    }

    /**
     * Get the doctor's email.
     */
    public function getEmailAttribute(): string
    {
        return $this->user->email ?? 'Unknown';
    }

    /**
     * Check if the doctor is available on a specific day and time.
     */
    public function isAvailableAt(string $dayOfWeek, string $time): bool
    {
        return $this->schedules()
            ->where('day_of_week', strtolower($dayOfWeek))
            ->where('is_active', true)
            ->where('start_time', '<=', $time)
            ->where('end_time', '>', $time)
            ->exists();
    }

    /**
     * Get available time slots for a specific date.
     */
    public function getAvailableSlots(string $date): array
    {
        $dayOfWeek = strtolower(date('l', strtotime($date)));
        $schedules = $this->schedules()
            ->where('day_of_week', $dayOfWeek)
            ->where('is_active', true)
            ->get();

        if ($schedules->isEmpty()) {
            return [];
        }

        $slots = [];
        
        foreach ($schedules as $schedule) {
            $startTime = strtotime($schedule->start_time);
            $endTime = strtotime($schedule->end_time);
            $slotDuration = $schedule->slot_duration * 60; // Convert to seconds

            while ($startTime < $endTime) {
                $slotTime = date('H:i', $startTime);
                
                // Check if slot is already booked
                $isBooked = $this->appointments()
                    ->where('appointment_date', $date)
                    ->where('appointment_time', $slotTime)
                    ->whereIn('status', ['pending', 'approved'])
                    ->exists();

                // Also check with seconds format
                $isBookedWithSeconds = $this->appointments()
                    ->where('appointment_date', $date)
                    ->where('appointment_time', $slotTime . ':00')
                    ->whereIn('status', ['pending', 'approved'])
                    ->exists();

                if (!$isBooked && !$isBookedWithSeconds && !in_array($slotTime, $slots)) {
                    $slots[] = $slotTime;
                }

                $startTime += $slotDuration;
            }
        }

        sort($slots);
        return $slots;
    }
}
