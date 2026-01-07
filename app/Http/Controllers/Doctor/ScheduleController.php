<?php

namespace App\Http\Controllers\Doctor;

use App\Http\Controllers\Controller;
use App\Models\Schedule;
use Illuminate\Http\Request;

class ScheduleController extends Controller
{
    /**
     * Display a listing of the doctor's schedules.
     */
    public function index(Request $request)
    {
        $doctor = $request->user()->doctor;

        if (!$doctor) {
            return redirect()->route('login')
                ->with('error', 'Doctor profile not found.');
        }

        $schedules = $doctor->schedules()
            ->orderByRaw("FIELD(day_of_week, 'monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday')")
            ->get();

        return view('doctor.schedules.index', compact('schedules'));
    }

    /**
     * Show the form for creating a new schedule.
     */
    public function create()
    {
        $daysOfWeek = Schedule::DAYS_OF_WEEK;
        return view('doctor.schedules.create', compact('daysOfWeek'));
    }

    /**
     * Store a newly created schedule.
     */
    public function store(Request $request)
    {
        $doctor = $request->user()->doctor;

        $validated = $request->validate([
            'day_of_week' => ['required', 'in:monday,tuesday,wednesday,thursday,friday,saturday,sunday'],
            'start_time' => ['required', 'date_format:H:i'],
            'end_time' => ['required', 'date_format:H:i', 'after:start_time'],
            'slot_duration' => ['required', 'integer', 'min:10', 'max:120'],
            'max_patients_per_slot' => ['required', 'integer', 'min:1', 'max:10'],
        ]);

        // Check for overlapping schedules
        $exists = $doctor->schedules()
            ->where('day_of_week', $validated['day_of_week'])
            ->where(function ($query) use ($validated) {
                $query->whereBetween('start_time', [$validated['start_time'], $validated['end_time']])
                    ->orWhereBetween('end_time', [$validated['start_time'], $validated['end_time']])
                    ->orWhere(function ($q) use ($validated) {
                        $q->where('start_time', '<=', $validated['start_time'])
                          ->where('end_time', '>=', $validated['end_time']);
                    });
            })
            ->exists();

        if ($exists) {
            return back()->withErrors(['day_of_week' => 'This time slot overlaps with an existing schedule.'])
                ->withInput();
        }

        $doctor->schedules()->create($validated);

        return redirect()->route('doctor.schedules.index')
            ->with('success', 'Schedule created successfully.');
    }

    /**
     * Show the form for editing the specified schedule.
     */
    public function edit(Schedule $schedule)
    {
        // Ensure the schedule belongs to the logged-in doctor
        if ($schedule->doctor_id !== auth()->user()->doctor->id) {
            abort(403);
        }

        $daysOfWeek = Schedule::DAYS_OF_WEEK;
        return view('doctor.schedules.edit', compact('schedule', 'daysOfWeek'));
    }

    /**
     * Update the specified schedule.
     */
    public function update(Request $request, Schedule $schedule)
    {
        // Ensure the schedule belongs to the logged-in doctor
        if ($schedule->doctor_id !== auth()->user()->doctor->id) {
            abort(403);
        }

        $validated = $request->validate([
            'start_time' => ['required', 'date_format:H:i'],
            'end_time' => ['required', 'date_format:H:i', 'after:start_time'],
            'slot_duration' => ['required', 'integer', 'min:10', 'max:120'],
            'max_patients_per_slot' => ['required', 'integer', 'min:1', 'max:10'],
            'is_active' => ['boolean'],
        ]);

        $schedule->update([
            'start_time' => $validated['start_time'],
            'end_time' => $validated['end_time'],
            'slot_duration' => $validated['slot_duration'],
            'max_patients_per_slot' => $validated['max_patients_per_slot'],
            'is_active' => $request->has('is_active'),
        ]);

        return redirect()->route('doctor.schedules.index')
            ->with('success', 'Schedule updated successfully.');
    }

    /**
     * Remove the specified schedule.
     */
    public function destroy(Schedule $schedule)
    {
        // Ensure the schedule belongs to the logged-in doctor
        if ($schedule->doctor_id !== auth()->user()->doctor->id) {
            abort(403);
        }

        $schedule->delete();

        return redirect()->route('doctor.schedules.index')
            ->with('success', 'Schedule deleted successfully.');
    }

    /**
     * Toggle schedule active status.
     */
    public function toggleStatus(Schedule $schedule)
    {
        // Ensure the schedule belongs to the logged-in doctor
        if ($schedule->doctor_id !== auth()->user()->doctor->id) {
            abort(403);
        }

        $schedule->update(['is_active' => !$schedule->is_active]);

        $status = $schedule->is_active ? 'activated' : 'deactivated';
        return back()->with('success', "Schedule {$status} successfully.");
    }
}
