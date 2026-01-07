<?php

namespace App\Http\Controllers\Doctor;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use Illuminate\Http\Request;

class AppointmentController extends Controller
{
    /**
     * Display a listing of the doctor's appointments.
     */
    public function index(Request $request)
    {
        $doctor = $request->user()->doctor;

        if (!$doctor) {
            return redirect()->route('login')
                ->with('error', 'Doctor profile not found.');
        }

        $query = $doctor->appointments()->with(['patient.user']);

        // Filter by status
        if ($request->has('status') && $request->status !== 'all') {
            $query->where('status', $request->status);
        }

        // Filter by date
        if ($request->has('date') && $request->date) {
            $query->whereDate('appointment_date', $request->date);
        }

        $appointments = $query->latest('appointment_date')
            ->latest('appointment_time')
            ->paginate(15);

        return view('doctor.appointments.index', compact('appointments'));
    }

    /**
     * Display the specified appointment.
     */
    public function show(Appointment $appointment)
    {
        // Ensure the appointment belongs to the logged-in doctor
        if ($appointment->doctor_id !== auth()->user()->doctor->id) {
            abort(403);
        }

        $appointment->load(['patient.user', 'schedule']);
        return view('doctor.appointments.show', compact('appointment'));
    }

    /**
     * Approve an appointment.
     */
    public function approve(Appointment $appointment)
    {
        // Ensure the appointment belongs to the logged-in doctor
        if ($appointment->doctor_id !== auth()->user()->doctor->id) {
            abort(403);
        }

        if ($appointment->status !== 'pending') {
            return back()->with('error', 'Only pending appointments can be approved.');
        }

        // Check for conflicts
        if (Appointment::hasConflict(
            $appointment->doctor_id,
            $appointment->appointment_date->format('Y-m-d'),
            $appointment->appointment_time,
            $appointment->id
        )) {
            return back()->with('error', 'Cannot approve: Time slot conflict detected.');
        }

        $appointment->update(['status' => 'approved']);

        return back()->with('success', 'Appointment approved successfully.');
    }

    /**
     * Reject an appointment.
     */
    public function reject(Request $request, Appointment $appointment)
    {
        // Ensure the appointment belongs to the logged-in doctor
        if ($appointment->doctor_id !== auth()->user()->doctor->id) {
            abort(403);
        }

        if ($appointment->status !== 'pending') {
            return back()->with('error', 'Only pending appointments can be rejected.');
        }

        $validated = $request->validate([
            'rejection_reason' => ['required', 'string', 'max:500'],
        ]);

        $appointment->update([
            'status' => 'rejected',
            'rejection_reason' => $validated['rejection_reason'],
        ]);

        return back()->with('success', 'Appointment rejected.');
    }

    /**
     * Mark an appointment as completed with notes.
     */
    public function complete(Request $request, Appointment $appointment)
    {
        // Ensure the appointment belongs to the logged-in doctor
        if ($appointment->doctor_id !== auth()->user()->doctor->id) {
            abort(403);
        }

        if ($appointment->status !== 'approved') {
            return back()->with('error', 'Only approved appointments can be completed.');
        }

        $validated = $request->validate([
            'diagnosis' => ['nullable', 'string', 'max:1000'],
            'prescription' => ['nullable', 'string', 'max:1000'],
            'notes' => ['nullable', 'string', 'max:1000'],
            'fee_charged' => ['nullable', 'numeric', 'min:0'],
        ]);

        $appointment->update([
            'status' => 'completed',
            'diagnosis' => $validated['diagnosis'] ?? null,
            'prescription' => $validated['prescription'] ?? null,
            'notes' => $validated['notes'] ?? null,
            'fee_charged' => $validated['fee_charged'] ?? $appointment->doctor->consultation_fee,
        ]);

        return redirect()->route('doctor.appointments.index')
            ->with('success', 'Appointment marked as completed.');
    }

    /**
     * Mark an appointment as no-show.
     */
    public function noShow(Appointment $appointment)
    {
        // Ensure the appointment belongs to the logged-in doctor
        if ($appointment->doctor_id !== auth()->user()->doctor->id) {
            abort(403);
        }

        if (!in_array($appointment->status, ['pending', 'approved'])) {
            return back()->with('error', 'Invalid appointment status for no-show.');
        }

        $appointment->update(['status' => 'no_show']);

        return back()->with('success', 'Appointment marked as no-show.');
    }

    /**
     * View patient history.
     */
    public function patientHistory(Request $request, $patientId)
    {
        $doctor = $request->user()->doctor;

        $appointments = Appointment::with(['patient.user'])
            ->where('doctor_id', $doctor->id)
            ->where('patient_id', $patientId)
            ->orderBy('appointment_date', 'desc')
            ->orderBy('appointment_time', 'desc')
            ->paginate(10);

        return view('doctor.appointments.patient-history', compact('appointments'));
    }
}
