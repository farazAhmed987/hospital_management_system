<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Schedule extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'doctor_id',
        'day_of_week',
        'start_time',
        'end_time',
        'slot_duration',
        'max_patients_per_slot',
        'is_active',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'is_active' => 'boolean',
    ];

    /**
     * Days of the week.
     */
    public const DAYS_OF_WEEK = [
        'monday' => 'Monday',
        'tuesday' => 'Tuesday',
        'wednesday' => 'Wednesday',
        'thursday' => 'Thursday',
        'friday' => 'Friday',
        'saturday' => 'Saturday',
        'sunday' => 'Sunday',
    ];

    /**
     * Get the doctor that owns the schedule.
     */
    public function doctor()
    {
        return $this->belongsTo(Doctor::class);
    }

    /**
     * Get the appointments for this schedule.
     */
    public function appointments()
    {
        return $this->hasMany(Appointment::class);
    }

    /**
     * Get formatted day name.
     */
    public function getDayNameAttribute(): string
    {
        return self::DAYS_OF_WEEK[$this->day_of_week] ?? ucfirst($this->day_of_week);
    }

    /**
     * Get formatted start time.
     */
    public function getFormattedStartTimeAttribute(): string
    {
        return date('g:i A', strtotime($this->start_time));
    }

    /**
     * Get formatted end time.
     */
    public function getFormattedEndTimeAttribute(): string
    {
        return date('g:i A', strtotime($this->end_time));
    }

    /**
     * Get available time slots for a specific date.
     */
    public function getAvailableSlots(string $date): array
    {
        $slots = [];
        $startTime = strtotime($this->start_time);
        $endTime = strtotime($this->end_time);
        $slotDuration = $this->slot_duration * 60; // Convert to seconds

        while ($startTime < $endTime) {
            $slotTime = date('H:i:s', $startTime);
            
            // Count existing bookings for this slot
            $bookedCount = Appointment::where('schedule_id', $this->id)
                ->where('appointment_date', $date)
                ->where('appointment_time', $slotTime)
                ->whereIn('status', ['pending', 'approved'])
                ->count();

            if ($bookedCount < $this->max_patients_per_slot) {
                $slots[] = [
                    'time' => $slotTime,
                    'formatted' => date('g:i A', $startTime),
                    'available' => $this->max_patients_per_slot - $bookedCount,
                ];
            }

            $startTime += $slotDuration;
        }

        return $slots;
    }

    /**
     * Check if a specific time slot is available.
     */
    public function isSlotAvailable(string $date, string $time): bool
    {
        $bookedCount = Appointment::where('schedule_id', $this->id)
            ->where('appointment_date', $date)
            ->where('appointment_time', $time)
            ->whereIn('status', ['pending', 'approved'])
            ->count();

        return $bookedCount < $this->max_patients_per_slot;
    }
}
