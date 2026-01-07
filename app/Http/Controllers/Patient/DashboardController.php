<?php

namespace App\Http\Controllers\Patient;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    /**
     * Display the patient dashboard.
     */
    public function index(Request $request)
    {
        $patient = $request->user()->patient;

        if (!$patient) {
            return redirect()->route('login')
                ->with('error', 'Patient profile not found.');
        }

        $stats = [
            'total_appointments' => $patient->appointments()->count(),
            'pending_appointments' => $patient->appointments()->pending()->count(),
            'upcoming_appointments' => $patient->upcomingAppointments()->count(),
            'completed_appointments' => $patient->appointments()->where('status', 'completed')->count(),
        ];

        $upcomingAppointments = $patient->upcomingAppointments()
            ->with(['doctor.user'])
            ->take(5)
            ->get();

        $recentAppointments = $patient->pastAppointments()
            ->with(['doctor.user'])
            ->take(5)
            ->get();

        return view('patient.dashboard', compact(
            'patient',
            'stats',
            'upcomingAppointments',
            'recentAppointments'
        ));
    }
}
