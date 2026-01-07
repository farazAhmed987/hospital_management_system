@extends('layouts.app')

@section('title', 'Appointment Details')
@section('page-title', 'Appointment Details')

@section('sidebar-menu')
    <li class="nav-item">
        <a class="nav-link" href="{{ route('admin.dashboard') }}">
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
        <a class="nav-link active" href="{{ route('admin.appointments.index') }}">
            <i class="bi bi-calendar-check"></i>Appointments
        </a>
    </li>
@endsection

@section('content')
<div class="row">
    <div class="col-md-8">
        <div class="card mb-4">
            <div class="card-header d-flex justify-content-between align-items-center">
                <span><i class="bi bi-calendar-check me-2"></i>Appointment #{{ $appointment->id }}</span>
                <span class="badge {{ $appointment->status_badge_class }} fs-6">{{ ucfirst($appointment->status) }}</span>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6 mb-4">
                        <h6 class="text-muted mb-2">Date & Time</h6>
                        <p class="fs-5 mb-0">
                            <i class="bi bi-calendar me-2"></i>{{ $appointment->formatted_date }}
                            <br>
                            <i class="bi bi-clock me-2"></i>{{ $appointment->formatted_time }}
                        </p>
                    </div>
                    <div class="col-md-6 mb-4">
                        <h6 class="text-muted mb-2">Fee</h6>
                        <p class="fs-5 mb-0">
                            ${{ number_format($appointment->fee_charged ?? $appointment->doctor->consultation_fee, 2) }}
                            @if($appointment->is_paid)
                                <span class="badge bg-success ms-2">Paid</span>
                            @else
                                <span class="badge bg-warning text-dark ms-2">Unpaid</span>
                            @endif
                        </p>
                    </div>
                </div>

                <hr>

                <div class="mb-4">
                    <h6 class="text-muted mb-2">Reason for Visit</h6>
                    <p class="mb-0">{{ $appointment->reason_for_visit ?? 'Not specified' }}</p>
                </div>

                @if($appointment->symptoms)
                    <div class="mb-4">
                        <h6 class="text-muted mb-2">Symptoms</h6>
                        <p class="mb-0">{{ $appointment->symptoms }}</p>
                    </div>
                @endif

                @if($appointment->diagnosis)
                    <div class="mb-4">
                        <h6 class="text-muted mb-2">Diagnosis</h6>
                        <p class="mb-0">{{ $appointment->diagnosis }}</p>
                    </div>
                @endif

                @if($appointment->prescription)
                    <div class="mb-4">
                        <h6 class="text-muted mb-2">Prescription</h6>
                        <p class="mb-0">{{ $appointment->prescription }}</p>
                    </div>
                @endif

                @if($appointment->notes)
                    <div class="mb-4">
                        <h6 class="text-muted mb-2">Notes</h6>
                        <p class="mb-0">{{ $appointment->notes }}</p>
                    </div>
                @endif

                @if($appointment->rejection_reason)
                    <div class="alert alert-danger">
                        <h6 class="alert-heading"><i class="bi bi-x-circle me-2"></i>Rejection Reason</h6>
                        <p class="mb-0">{{ $appointment->rejection_reason }}</p>
                    </div>
                @endif
            </div>
        </div>

        @if($appointment->status === 'pending')
            <div class="card">
                <div class="card-header">
                    <i class="bi bi-gear me-2"></i>Actions
                </div>
                <div class="card-body">
                    <div class="d-flex gap-2">
                        <form method="POST" action="{{ route('admin.appointments.approve', $appointment) }}">
                            @csrf
                            @method('PATCH')
                            <button type="submit" class="btn btn-success">
                                <i class="bi bi-check-lg me-1"></i>Approve
                            </button>
                        </form>
                        <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#rejectModal">
                            <i class="bi bi-x-lg me-1"></i>Reject
                        </button>
                    </div>
                </div>
            </div>

            <!-- Reject Modal -->
            <div class="modal fade" id="rejectModal" tabindex="-1">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Reject Appointment</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <form method="POST" action="{{ route('admin.appointments.reject', $appointment) }}">
                            @csrf
                            @method('PATCH')
                            <div class="modal-body">
                                <div class="mb-3">
                                    <label for="rejection_reason" class="form-label">Reason for Rejection</label>
                                    <textarea class="form-control" id="rejection_reason" name="rejection_reason" rows="3" required></textarea>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                <button type="submit" class="btn btn-danger">Reject</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        @endif
    </div>

    <div class="col-md-4">
        <!-- Patient Info -->
        <div class="card mb-4">
            <div class="card-header">
                <i class="bi bi-person me-2"></i>Patient
            </div>
            <div class="card-body">
                <h5>{{ $appointment->patient->user->name ?? 'N/A' }}</h5>
                <p class="text-muted mb-2">{{ $appointment->patient->user->email ?? 'N/A' }}</p>
                <p class="mb-1"><i class="bi bi-telephone me-2"></i>{{ $appointment->patient->user->phone ?? 'N/A' }}</p>
                @if($appointment->patient->blood_group)
                    <p class="mb-0"><i class="bi bi-droplet me-2"></i>Blood Group: {{ $appointment->patient->blood_group }}</p>
                @endif
            </div>
        </div>

        <!-- Doctor Info -->
        <div class="card mb-4">
            <div class="card-header">
                <i class="bi bi-person-badge me-2"></i>Doctor
            </div>
            <div class="card-body">
                <h5>Dr. {{ $appointment->doctor->user->name ?? 'N/A' }}</h5>
                <p class="text-primary mb-2">{{ $appointment->doctor->specialization ?? 'N/A' }}</p>
                <p class="mb-1"><i class="bi bi-envelope me-2"></i>{{ $appointment->doctor->user->email ?? 'N/A' }}</p>
                <p class="mb-0"><i class="bi bi-currency-dollar me-2"></i>Fee: ${{ number_format($appointment->doctor->consultation_fee, 2) }}</p>
            </div>
        </div>

        <a href="{{ route('admin.appointments.index') }}" class="btn btn-secondary w-100">
            <i class="bi bi-arrow-left me-1"></i>Back to List
        </a>
    </div>
</div>
@endsection
