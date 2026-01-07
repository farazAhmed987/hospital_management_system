@extends('layouts.app')

@section('title', 'Appointment Details')
@section('page-title', 'Appointment Details')

@section('sidebar-menu')
    <li class="nav-item">
        <a class="nav-link" href="{{ route('patient.dashboard') }}">
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
        <a class="nav-link active" href="{{ route('patient.appointments.index') }}">
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
<div class="row">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <span><i class="bi bi-calendar-event me-2"></i>Appointment #{{ $appointment->id }}</span>
                <span class="badge {{ $appointment->status_badge_class }} fs-6">{{ ucfirst($appointment->status) }}</span>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6 mb-4">
                        <h6 class="text-muted mb-2"><i class="bi bi-calendar3 me-1"></i>Date & Time</h6>
                        <p class="fs-5 mb-0">{{ $appointment->formatted_date }}</p>
                        <p class="fs-5 text-primary">{{ $appointment->formatted_time }}</p>
                    </div>
                    <div class="col-md-6 mb-4">
                        <h6 class="text-muted mb-2"><i class="bi bi-person-badge me-1"></i>Doctor</h6>
                        <p class="fs-5 mb-0">Dr. {{ $appointment->doctor->user->name ?? 'N/A' }}</p>
                        <p class="text-muted">{{ $appointment->doctor->specialization ?? 'N/A' }}</p>
                    </div>
                </div>
                
                @if($appointment->reason)
                    <div class="mb-4">
                        <h6 class="text-muted mb-2"><i class="bi bi-clipboard-pulse me-1"></i>Reason for Visit</h6>
                        <p class="mb-0">{{ $appointment->reason }}</p>
                    </div>
                @endif
                
                @if($appointment->notes)
                    <div class="mb-4">
                        <h6 class="text-muted mb-2"><i class="bi bi-sticky me-1"></i>Your Notes</h6>
                        <p class="mb-0">{{ $appointment->notes }}</p>
                    </div>
                @endif

                @if($appointment->status === 'completed' && $appointment->diagnosis)
                    <div class="alert alert-success mb-4">
                        <h6 class="alert-heading"><i class="bi bi-heart-pulse me-1"></i>Doctor's Diagnosis</h6>
                        <p class="mb-0">{{ $appointment->diagnosis }}</p>
                    </div>
                @endif

                @if($appointment->status === 'completed' && $appointment->prescription)
                    <div class="alert alert-info mb-4">
                        <h6 class="alert-heading"><i class="bi bi-prescription2 me-1"></i>Prescription</h6>
                        <p class="mb-0" style="white-space: pre-line;">{{ $appointment->prescription }}</p>
                    </div>
                @endif

                @if($appointment->status === 'rejected' && $appointment->rejection_reason)
                    <div class="alert alert-danger mb-4">
                        <h6 class="alert-heading"><i class="bi bi-x-circle me-1"></i>Rejection Reason</h6>
                        <p class="mb-0">{{ $appointment->rejection_reason }}</p>
                    </div>
                @endif

                <div class="d-flex gap-2">
                    <a href="{{ route('patient.appointments.index') }}" class="btn btn-outline-secondary">
                        <i class="bi bi-arrow-left me-1"></i>Back to Appointments
                    </a>
                    @if($appointment->canBeCancelled())
                        <form method="POST" action="{{ route('patient.appointments.cancel', $appointment) }}" onsubmit="return confirm('Are you sure you want to cancel this appointment?')">
                            @csrf
                            @method('PATCH')
                            <button type="submit" class="btn btn-danger">
                                <i class="bi bi-x-lg me-1"></i>Cancel Appointment
                            </button>
                        </form>
                    @endif
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-lg-4">
        <!-- Doctor Info Card -->
        <div class="card mb-3">
            <div class="card-header">
                <i class="bi bi-person-badge me-2"></i>Doctor Information
            </div>
            <div class="card-body">
                <div class="text-center mb-3">
                    <div class="avatar-lg bg-primary rounded-circle d-inline-flex align-items-center justify-content-center" style="width: 80px; height: 80px;">
                        <i class="bi bi-person-fill text-white fs-1"></i>
                    </div>
                </div>
                <h5 class="text-center mb-1">Dr. {{ $appointment->doctor->user->name ?? 'N/A' }}</h5>
                <p class="text-center text-muted mb-3">{{ $appointment->doctor->specialization ?? 'N/A' }}</p>
                <table class="table table-sm table-borderless">
                    <tr>
                        <td><i class="bi bi-mortarboard me-1"></i>Qualification:</td>
                        <td class="text-end">{{ $appointment->doctor->qualification ?? 'N/A' }}</td>
                    </tr>
                    <tr>
                        <td><i class="bi bi-telephone me-1"></i>Phone:</td>
                        <td class="text-end">{{ $appointment->doctor->user->phone ?? 'N/A' }}</td>
                    </tr>
                    <tr>
                        <td><i class="bi bi-currency-dollar me-1"></i>Consultation Fee:</td>
                        <td class="text-end fw-bold text-primary">${{ number_format($appointment->doctor->consultation_fee ?? 0, 2) }}</td>
                    </tr>
                </table>
            </div>
        </div>

        <!-- Status Timeline -->
        <div class="card">
            <div class="card-header">
                <i class="bi bi-clock-history me-2"></i>Appointment Status
            </div>
            <div class="card-body">
                <div class="timeline">
                    <div class="timeline-item {{ in_array($appointment->status, ['pending', 'approved', 'completed', 'rejected', 'cancelled']) ? 'completed' : '' }}">
                        <div class="timeline-marker {{ $appointment->status === 'pending' ? 'bg-warning' : 'bg-success' }}"></div>
                        <div class="timeline-content">
                            <p class="mb-0 fw-bold">Booked</p>
                            <small class="text-muted">{{ $appointment->created_at->format('M d, Y h:i A') }}</small>
                        </div>
                    </div>
                    
                    @if($appointment->status === 'approved')
                        <div class="timeline-item completed">
                            <div class="timeline-marker bg-info"></div>
                            <div class="timeline-content">
                                <p class="mb-0 fw-bold">Approved</p>
                                <small class="text-muted">{{ $appointment->updated_at->format('M d, Y h:i A') }}</small>
                            </div>
                        </div>
                    @elseif($appointment->status === 'completed')
                        <div class="timeline-item completed">
                            <div class="timeline-marker bg-info"></div>
                            <div class="timeline-content">
                                <p class="mb-0 fw-bold">Approved</p>
                            </div>
                        </div>
                        <div class="timeline-item completed">
                            <div class="timeline-marker bg-success"></div>
                            <div class="timeline-content">
                                <p class="mb-0 fw-bold">Completed</p>
                                <small class="text-muted">{{ $appointment->updated_at->format('M d, Y h:i A') }}</small>
                            </div>
                        </div>
                    @elseif($appointment->status === 'rejected')
                        <div class="timeline-item completed">
                            <div class="timeline-marker bg-danger"></div>
                            <div class="timeline-content">
                                <p class="mb-0 fw-bold">Rejected</p>
                                <small class="text-muted">{{ $appointment->updated_at->format('M d, Y h:i A') }}</small>
                            </div>
                        </div>
                    @elseif($appointment->status === 'cancelled')
                        <div class="timeline-item completed">
                            <div class="timeline-marker bg-secondary"></div>
                            <div class="timeline-content">
                                <p class="mb-0 fw-bold">Cancelled</p>
                                <small class="text-muted">{{ $appointment->updated_at->format('M d, Y h:i A') }}</small>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.timeline {
    position: relative;
    padding-left: 30px;
}
.timeline::before {
    content: '';
    position: absolute;
    left: 10px;
    top: 0;
    bottom: 0;
    width: 2px;
    background: #dee2e6;
}
.timeline-item {
    position: relative;
    padding-bottom: 20px;
}
.timeline-item:last-child {
    padding-bottom: 0;
}
.timeline-marker {
    position: absolute;
    left: -24px;
    width: 14px;
    height: 14px;
    border-radius: 50%;
    border: 2px solid #fff;
    box-shadow: 0 0 0 2px #dee2e6;
}
.timeline-content {
    padding-left: 10px;
}
</style>
@endsection
