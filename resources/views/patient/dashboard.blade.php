@extends('layouts.app')

@section('title', 'Patient Dashboard')
@section('page-title', 'Dashboard')

@section('sidebar-menu')
    <li class="nav-item">
        <a class="nav-link active" href="{{ route('patient.dashboard') }}">
            <i class="bi bi-speedometer2"></i>Dashboard
        </a>
    </li>
    <div class="sidebar-section">Appointments</div>
    <li class="nav-item">
        <a class="nav-link" href="{{ route('patient.appointments.create') }}">
            <i class="bi bi-plus-circle"></i>Book Appointment
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link" href="{{ route('patient.appointments.index') }}">
            <i class="bi bi-calendar-check"></i>My Appointments
        </a>
    </li>
    <div class="sidebar-section">Account</div>
    <li class="nav-item">
        <a class="nav-link" href="{{ route('patient.profile.show') }}">
            <i class="bi bi-person"></i>My Profile
        </a>
    </li>
@endsection

@section('content')
<!-- Stats Cards -->
<div class="row mb-4">
    <div class="col-md-6 col-lg-3 mb-3">
        <div class="stats-card bg-primary">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h3>{{ $stats['total_appointments'] }}</h3>
                    <p>Total Appointments</p>
                </div>
                <i class="bi bi-calendar"></i>
            </div>
        </div>
    </div>
    <div class="col-md-6 col-lg-3 mb-3">
        <div class="stats-card bg-warning">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h3>{{ $stats['pending_appointments'] }}</h3>
                    <p>Pending</p>
                </div>
                <i class="bi bi-hourglass"></i>
            </div>
        </div>
    </div>
    <div class="col-md-6 col-lg-3 mb-3">
        <div class="stats-card bg-info">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h3>{{ $stats['upcoming_appointments'] }}</h3>
                    <p>Upcoming</p>
                </div>
                <i class="bi bi-calendar-week"></i>
            </div>
        </div>
    </div>
    <div class="col-md-6 col-lg-3 mb-3">
        <div class="stats-card bg-success">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h3>{{ $stats['completed_appointments'] }}</h3>
                    <p>Completed</p>
                </div>
                <i class="bi bi-check-circle"></i>
            </div>
        </div>
    </div>
</div>

<!-- Quick Action -->
<div class="row mb-4">
    <div class="col-12">
        <a href="{{ route('patient.appointments.create') }}" class="btn btn-primary btn-lg">
            <i class="bi bi-plus-circle me-2"></i>Book New Appointment
        </a>
    </div>
</div>

<div class="row">
    <!-- Upcoming Appointments -->
    <div class="col-lg-6 mb-4">
        <div class="card h-100">
            <div class="card-header d-flex justify-content-between align-items-center">
                <span><i class="bi bi-calendar-week me-2"></i>Upcoming Appointments</span>
                <a href="{{ route('patient.appointments.index') }}" class="btn btn-sm btn-primary">View All</a>
            </div>
            <div class="card-body">
                @forelse($upcomingAppointments as $appointment)
                    <div class="d-flex justify-content-between align-items-center p-3 border-bottom">
                        <div>
                            <h6 class="mb-1">Dr. {{ $appointment->doctor->user->name }}</h6>
                            <small class="text-muted">{{ $appointment->doctor->specialization }}</small>
                            <br>
                            <small>{{ $appointment->formatted_date }} at {{ $appointment->formatted_time }}</small>
                        </div>
                        <div class="text-end">
                            <span class="badge {{ $appointment->status_badge_class }}">
                                {{ ucfirst($appointment->status) }}
                            </span>
                            <br>
                            <a href="{{ route('patient.appointments.show', $appointment) }}" class="btn btn-sm btn-outline-primary mt-1">View</a>
                        </div>
                    </div>
                @empty
                    <div class="text-center text-muted py-4">
                        <i class="bi bi-calendar-x fs-1 d-block mb-2"></i>
                        <p class="mb-2">No upcoming appointments</p>
                        <a href="{{ route('patient.appointments.create') }}" class="btn btn-sm btn-primary">Book Now</a>
                    </div>
                @endforelse
            </div>
        </div>
    </div>

    <!-- Recent Appointments -->
    <div class="col-lg-6 mb-4">
        <div class="card h-100">
            <div class="card-header">
                <i class="bi bi-clock-history me-2"></i>Recent Appointments
            </div>
            <div class="card-body">
                @forelse($recentAppointments as $appointment)
                    <div class="d-flex justify-content-between align-items-center p-3 border-bottom">
                        <div>
                            <h6 class="mb-1">Dr. {{ $appointment->doctor->user->name }}</h6>
                            <small class="text-muted">{{ $appointment->doctor->specialization }}</small>
                            <br>
                            <small>{{ $appointment->formatted_date }}</small>
                        </div>
                        <div class="text-end">
                            <span class="badge {{ $appointment->status_badge_class }}">
                                {{ ucfirst($appointment->status) }}
                            </span>
                            <br>
                            <a href="{{ route('patient.appointments.show', $appointment) }}" class="btn btn-sm btn-outline-info mt-1">View</a>
                        </div>
                    </div>
                @empty
                    <p class="text-muted text-center mb-0">No appointment history</p>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection
