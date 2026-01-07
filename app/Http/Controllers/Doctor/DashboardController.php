<?php

namespace App\Http\Controllers\Doctor;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    /**
     * Display the doctor dashboard.
     */
    public function index(Request $request)
    {
        $doctor = $request->user()->doctor;

        if (!$doctor) {
            return redirect()->route('login')
                ->with('error', 'Doctor profile not found.');
        }

        $stats = [
            'total_appointments' => $doctor->appointments()->count(),
            'pending_appointments' => $doctor->appointments()->pending()->count(),
            'today_appointments' => $doctor->appointments()->today()->count(),
            'completed_appointments' => $doctor->appointments()->where('status', 'completed')->count(),
        ];

        $todayAppointments = $doctor->appointments()
            ->with(['patient.user'])
            ->today()
            ->whereIn('status', ['approved', 'pending'])
            ->orderBy('appointment_time')
            ->get();

        $upcomingAppointments = $doctor->appointments()
            ->with(['patient.user'])
            ->upcoming()
            ->where('appointment_date', '>', today())
            ->orderBy('appointment_date')
            ->orderBy('appointment_time')
            ->take(5)
            ->get();

        $pendingAppointments = $doctor->appointments()
            ->with(['patient.user'])
            ->pending()
            ->orderBy('appointment_date')
            ->take(5)
            ->get();

        return view('doctor.dashboard', compact(
            'doctor',
            'stats',
            'todayAppointments',
            'upcomingAppointments',
            'pendingAppointments'
        ));
    }
}
