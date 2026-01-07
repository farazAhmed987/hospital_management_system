@extends('layouts.app')

@section('title', 'Doctor Dashboard')
@section('page-title', 'Dashboard')

@section('sidebar-menu')
    <li class="nav-item">
        <a class="nav-link active" href="{{ route('doctor.dashboard') }}">
            <i class="bi bi-speedometer2"></i>Dashboard
        </a>
    </li>
    <div class="sidebar-section">Management</div>
    <li class="nav-item">
        <a class="nav-link" href="{{ route('doctor.appointments.index') }}">
            <i class="bi bi-calendar-check"></i>Appointments
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link" href="{{ route('doctor.schedules.index') }}">
            <i class="bi bi-clock"></i>My Schedule
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
                    <h3>{{ $stats['today_appointments'] }}</h3>
                    <p>Today</p>
                </div>
                <i class="bi bi-calendar-day"></i>
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

<div class="row">
    <!-- Today's Appointments -->
    <div class="col-lg-6 mb-4">
        <div class="card h-100">
            <div class="card-header">
                <i class="bi bi-calendar-day me-2"></i>Today's Appointments
            </div>
            <div class="card-body">
                @forelse($todayAppointments as $appointment)
                    <div class="d-flex justify-content-between align-items-center p-3 border-bottom">
                        <div>
                            <h6 class="mb-1">{{ $appointment->patient->user->name }}</h6>
                            <small class="text-muted">{{ $appointment->formatted_time }}</small>
                        </div>
                        <div class="text-end">
                            <span class="badge {{ $appointment->status_badge_class }}">
                                {{ ucfirst($appointment->status) }}
                            </span>
                            <br>
                            <a href="{{ route('doctor.appointments.show', $appointment) }}" class="btn btn-sm btn-outline-primary mt-1">View</a>
                        </div>
                    </div>
                @empty
                    <p class="text-muted text-center mb-0">No appointments for today</p>
                @endforelse
            </div>
        </div>
    </div>

    <!-- Pending Appointments -->
    <div class="col-lg-6 mb-4">
        <div class="card h-100">
            <div class="card-header d-flex justify-content-between align-items-center">
                <span><i class="bi bi-hourglass me-2"></i>Pending Appointments</span>
                <a href="{{ route('doctor.appointments.index', ['status' => 'pending']) }}" class="btn btn-sm btn-primary">View All</a>
            </div>
            <div class="card-body">
                @forelse($pendingAppointments as $appointment)
                    <div class="d-flex justify-content-between align-items-center p-3 border-bottom">
                        <div>
                            <h6 class="mb-1">{{ $appointment->patient->user->name }}</h6>
                            <small class="text-muted">{{ $appointment->formatted_date }} at {{ $appointment->formatted_time }}</small>
                        </div>
                        <div>
                            <form method="POST" action="{{ route('doctor.appointments.approve', $appointment) }}" class="d-inline">
                                @csrf
                                @method('PATCH')
                                <button type="submit" class="btn btn-sm btn-success" title="Approve">
                                    <i class="bi bi-check-lg"></i>
                                </button>
                            </form>
                            <a href="{{ route('doctor.appointments.show', $appointment) }}" class="btn btn-sm btn-outline-info">
                                <i class="bi bi-eye"></i>
                            </a>
                        </div>
                    </div>
                @empty
                    <p class="text-muted text-center mb-0">No pending appointments</p>
                @endforelse
            </div>
        </div>
    </div>
</div>

<!-- Upcoming Appointments -->
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <span><i class="bi bi-calendar-week me-2"></i>Upcoming Appointments</span>
        <a href="{{ route('doctor.appointments.index') }}" class="btn btn-sm btn-primary">View All</a>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>Patient</th>
                        <th>Date</th>
                        <th>Time</th>
                        <th>Reason</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($upcomingAppointments as $appointment)
                        <tr>
                            <td>{{ $appointment->patient->user->name }}</td>
                            <td>{{ $appointment->formatted_date }}</td>
                            <td>{{ $appointment->formatted_time }}</td>
                            <td>{{ Str::limit($appointment->reason_for_visit, 30) }}</td>
                            <td>
                                <span class="badge {{ $appointment->status_badge_class }}">
                                    {{ ucfirst($appointment->status) }}
                                </span>
                            </td>
                            <td>
                                <a href="{{ route('doctor.appointments.show', $appointment) }}" class="btn btn-sm btn-outline-info">View</a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center text-muted">No upcoming appointments</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
