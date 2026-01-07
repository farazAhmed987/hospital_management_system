<?php

namespace App\Http\Controllers\Patient;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\Doctor;
use Illuminate\Http\Request;

class AppointmentController extends Controller
{
    /**
     * Display a listing of the patient's appointments.
     */
    public function index(Request $request)
    {
        $patient = $request->user()->patient;

        if (!$patient) {
            return redirect()->route('login')
                ->with('error', 'Patient profile not found.');
        }

        $query = $patient->appointments()->with(['doctor.user']);

        // Filter by status
        if ($request->has('status') && $request->status !== 'all') {
            $query->where('status', $request->status);
        }

        $appointments = $query->latest('appointment_date')
            ->latest('appointment_time')
            ->paginate(10);

        return view('patient.appointments.index', compact('appointments'));
    }

    /**
     * Show the form for creating a new appointment (book appointment).
     */
    public function create(Request $request)
    {
        $doctors = Doctor::with(['user', 'schedules'])
            ->where('is_available', true)
            ->whereHas('user', function ($query) {
                $query->where('is_active', true);
            })
            ->get();

        // Get unique specializations from doctors
        $specializations = Doctor::where('is_available', true)
            ->whereHas('user', function ($query) {
                $query->where('is_active', true);
            })
            ->distinct()
            ->pluck('specialization')
            ->filter()
            ->values();

        $selectedDoctor = null;
        $availableSlots = [];

        if ($request->has('doctor_id') && $request->has('date')) {
            $selectedDoctor = Doctor::find($request->doctor_id);
            if ($selectedDoctor) {
                $availableSlots = $selectedDoctor->getAvailableSlots($request->date);
            }
        }

        return view('patient.appointments.create', compact('doctors', 'specializations', 'selectedDoctor', 'availableSlots'));
    }

    /**
     * Get available slots for a doctor on a specific date (AJAX).
     */
    public function getAvailableSlots(Request $request)
    {
        $validated = $request->validate([
            'doctor_id' => ['required', 'exists:doctors,id'],
            'date' => ['required', 'date', 'after_or_equal:today'],
        ]);

        $doctor = Doctor::find($validated['doctor_id']);
        $slots = $doctor->getAvailableSlots($validated['date']);

        return response()->json(['slots' => $slots]);
    }

    /**
     * Store a newly created appointment (book appointment).
     */
    public function store(Request $request)
    {
        $patient = $request->user()->patient;

        if (!$patient) {
            return redirect()->route('login')
                ->with('error', 'Patient profile not found.');
        }

        $validated = $request->validate([
            'doctor_id' => ['required', 'exists:doctors,id'],
            'appointment_date' => ['required', 'date', 'after_or_equal:today'],
            'appointment_time' => ['required'],
            'reason' => ['nullable', 'string', 'max:500'],
            'notes' => ['nullable', 'string', 'max:1000'],
        ]);

        $doctor = Doctor::find($validated['doctor_id']);

        // Check if doctor is available
        if (!$doctor->is_available) {
            return back()->with('error', 'This doctor is currently not available.')
                ->withInput();
        }

        // Check for conflicts (double booking prevention)
        if (Appointment::hasConflict(
            $validated['doctor_id'],
            $validated['appointment_date'],
            $validated['appointment_time']
        )) {
            return back()->with('error', 'This time slot is no longer available. Please select another slot.')
                ->withInput();
        }

        // Check if patient already has an appointment at this time
        $patientConflict = Appointment::where('patient_id', $patient->id)
            ->where('appointment_date', $validated['appointment_date'])
            ->where('appointment_time', $validated['appointment_time'])
            ->whereIn('status', ['pending', 'approved'])
            ->exists();

        if ($patientConflict) {
            return back()->with('error', 'You already have an appointment at this time.')
                ->withInput();
        }

        // Get the schedule ID for this appointment
        $dayOfWeek = strtolower(date('l', strtotime($validated['appointment_date'])));
        $schedule = $doctor->schedules()
            ->where('day_of_week', $dayOfWeek)
            ->where('is_active', true)
            ->where('start_time', '<=', $validated['appointment_time'])
            ->where('end_time', '>', $validated['appointment_time'])
            ->first();

        // Create the appointment
        Appointment::create([
            'patient_id' => $patient->id,
            'doctor_id' => $validated['doctor_id'],
            'schedule_id' => $schedule?->id,
            'appointment_date' => $validated['appointment_date'],
            'appointment_time' => $validated['appointment_time'],
            'reason' => $validated['reason'] ?? null,
            'notes' => $validated['notes'] ?? null,
            'status' => 'pending',
        ]);

        return redirect()->route('patient.appointments.index')
            ->with('success', 'Appointment booked successfully! Please wait for confirmation.');
    }

    /**
     * Display the specified appointment.
     */
    public function show(Appointment $appointment)
    {
        // Ensure the appointment belongs to the logged-in patient
        if ($appointment->patient_id !== auth()->user()->patient->id) {
            abort(403);
        }

        $appointment->load(['doctor.user', 'schedule']);
        return view('patient.appointments.show', compact('appointment'));
    }

    /**
     * Cancel an appointment.
     */
    public function cancel(Appointment $appointment)
    {
        // Ensure the appointment belongs to the logged-in patient
        if ($appointment->patient_id !== auth()->user()->patient->id) {
            abort(403);
        }

        if (!$appointment->canBeCancelled()) {
            return back()->with('error', 'This appointment cannot be cancelled.');
        }

        $appointment->update(['status' => 'cancelled']);

        return back()->with('success', 'Appointment cancelled successfully.');
    }
}
