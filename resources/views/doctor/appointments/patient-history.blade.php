@extends('layouts.app')

@section('title', 'Patient History')
@section('page-title', 'Patient History')

@section('sidebar-menu')
    <li class="nav-item">
        <a class="nav-link" href="{{ route('doctor.dashboard') }}">
            <i class="bi bi-speedometer2"></i>Dashboard
        </a>
    </li>
    <div class="sidebar-section">Management</div>
    <li class="nav-item">
        <a class="nav-link active" href="{{ route('doctor.appointments.index') }}">
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
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <span>
            <i class="bi bi-clock-history me-2"></i>
            Appointment History - {{ $appointments->first()?->patient->user->name ?? 'Patient' }}
        </span>
        <a href="{{ route('doctor.appointments.index') }}" class="btn btn-secondary btn-sm">
            <i class="bi bi-arrow-left me-1"></i>Back
        </a>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Time</th>
                        <th>Reason</th>
                        <th>Diagnosis</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($appointments as $appointment)
                        <tr>
                            <td>{{ $appointment->formatted_date }}</td>
                            <td>{{ $appointment->formatted_time }}</td>
                            <td>{{ Str::limit($appointment->reason_for_visit, 30) }}</td>
                            <td>{{ Str::limit($appointment->diagnosis, 30) ?? '-' }}</td>
                            <td>
                                <span class="badge {{ $appointment->status_badge_class }}">
                                    {{ ucfirst($appointment->status) }}
                                </span>
                            </td>
                            <td>
                                <a href="{{ route('doctor.appointments.show', $appointment) }}" class="btn btn-sm btn-outline-info">
                                    <i class="bi bi-eye"></i> View
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center text-muted">No appointment history found</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="d-flex justify-content-center">
            {{ $appointments->links() }}
        </div>
    </div>
</div>
@endsection
