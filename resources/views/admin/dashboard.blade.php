@extends('layouts.app')

@section('title', 'Admin Dashboard')
@section('page-title', 'Dashboard')

@section('sidebar-menu')
    <li class="nav-item">
        <a class="nav-link active" href="{{ route('admin.dashboard') }}">
            <i class="bi bi-speedometer2"></i>Dashboard
        </a>
    </li>
    <div class="sidebar-section">Management</div>
    <li class="nav-item">
        <a class="nav-link" href="{{ route('admin.users.index') }}">
            <i class="bi bi-people"></i>Users
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link" href="{{ route('admin.appointments.index') }}">
            <i class="bi bi-calendar-check"></i>Appointments
        </a>
    </li>
@endsection

@section('content')
<!-- Stats Cards -->
<div class="row mb-4">
    <div class="col-md-4 col-lg-2 mb-3">
        <div class="stats-card bg-primary">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h3>{{ $stats['total_users'] }}</h3>
                    <p>Total Users</p>
                </div>
                <i class="bi bi-people"></i>
            </div>
        </div>
    </div>
    <div class="col-md-4 col-lg-2 mb-3">
        <div class="stats-card bg-success">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h3>{{ $stats['total_doctors'] }}</h3>
                    <p>Doctors</p>
                </div>
                <i class="bi bi-person-badge"></i>
            </div>
        </div>
    </div>
    <div class="col-md-4 col-lg-2 mb-3">
        <div class="stats-card bg-info">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h3>{{ $stats['total_patients'] }}</h3>
                    <p>Patients</p>
                </div>
                <i class="bi bi-person-heart"></i>
            </div>
        </div>
    </div>
    <div class="col-md-4 col-lg-2 mb-3">
        <div class="stats-card bg-warning">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h3>{{ $stats['total_appointments'] }}</h3>
                    <p>Appointments</p>
                </div>
                <i class="bi bi-calendar"></i>
            </div>
        </div>
    </div>
    <div class="col-md-4 col-lg-2 mb-3">
        <div class="stats-card bg-danger">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h3>{{ $stats['pending_appointments'] }}</h3>
                    <p>Pending</p>
                </div>
                <i class="bi bi-clock"></i>
            </div>
        </div>
    </div>
    <div class="col-md-4 col-lg-2 mb-3">
        <div class="stats-card bg-secondary">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h3>{{ $stats['today_appointments'] }}</h3>
                    <p>Today</p>
                </div>
                <i class="bi bi-calendar-day"></i>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <!-- Recent Appointments -->
    <div class="col-lg-8 mb-4">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <span><i class="bi bi-calendar-check me-2"></i>Recent Appointments</span>
                <a href="{{ route('admin.appointments.index') }}" class="btn btn-sm btn-primary">View All</a>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Patient</th>
                                <th>Doctor</th>
                                <th>Date</th>
                                <th>Time</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($recentAppointments as $appointment)
                                <tr>
                                    <td>{{ $appointment->patient->user->name ?? 'N/A' }}</td>
                                    <td>Dr. {{ $appointment->doctor->user->name ?? 'N/A' }}</td>
                                    <td>{{ $appointment->formatted_date }}</td>
                                    <td>{{ $appointment->formatted_time }}</td>
                                    <td>
                                        <span class="badge {{ $appointment->status_badge_class }}">
                                            {{ ucfirst($appointment->status) }}
                                        </span>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center text-muted">No appointments found</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Users -->
    <div class="col-lg-4 mb-4">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <span><i class="bi bi-person-plus me-2"></i>New Users</span>
                <a href="{{ route('admin.users.index') }}" class="btn btn-sm btn-primary">View All</a>
            </div>
            <div class="card-body">
                <ul class="list-group list-group-flush">
                    @forelse($recentUsers as $user)
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <div>
                                <strong>{{ $user->name }}</strong>
                                <br>
                                <small class="text-muted">{{ $user->email }}</small>
                            </div>
                            <span class="badge bg-{{ $user->role === 'admin' ? 'danger' : ($user->role === 'doctor' ? 'success' : 'primary') }}">
                                {{ ucfirst($user->role) }}
                            </span>
                        </li>
                    @empty
                        <li class="list-group-item text-center text-muted">No users found</li>
                    @endforelse
                </ul>
            </div>
        </div>
    </div>
</div>
@endsection
