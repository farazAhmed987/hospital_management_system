@extends('layouts.app')

@section('title', 'My Profile')
@section('page-title', 'My Profile')

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
        <a class="nav-link" href="{{ route('patient.appointments.index') }}">
            <i class="bi bi-calendar-check"></i>My Appointments
        </a>
    </li>
    <div class="sidebar-section">Account</div>
    <li class="nav-item">
        <a class="nav-link active" href="{{ route('patient.profile.show') }}">
            <i class="bi bi-person"></i>My Profile
        </a>
    </li>
@endsection

@section('content')
<div class="row">
    <div class="col-lg-4">
        <!-- Profile Card -->
        <div class="card mb-4">
            <div class="card-body text-center">
                <div class="avatar-xl bg-primary rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 100px; height: 100px;">
                    <i class="bi bi-person-fill text-white" style="font-size: 3rem;"></i>
                </div>
                <h4 class="mb-1">{{ $user->name }}</h4>
                <p class="text-muted mb-3">{{ $user->email }}</p>
                <span class="badge bg-success fs-6">Patient</span>
            </div>
            <div class="card-footer bg-transparent">
                <div class="d-grid gap-2">
                    <a href="{{ route('patient.profile.edit') }}" class="btn btn-primary">
                        <i class="bi bi-pencil me-1"></i>Edit Profile
                    </a>
                    <a href="{{ route('patient.profile.edit-password') }}" class="btn btn-outline-secondary">
                        <i class="bi bi-key me-1"></i>Change Password
                    </a>
                </div>
            </div>
        </div>

        <!-- Quick Stats -->
        <div class="card">
            <div class="card-header">
                <i class="bi bi-bar-chart me-2"></i>My Statistics
            </div>
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <span>Total Appointments</span>
                    <span class="badge bg-primary rounded-pill fs-6">{{ $stats['total_appointments'] ?? 0 }}</span>
                </div>
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <span>Completed</span>
                    <span class="badge bg-success rounded-pill fs-6">{{ $stats['completed'] ?? 0 }}</span>
                </div>
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <span>Upcoming</span>
                    <span class="badge bg-info rounded-pill fs-6">{{ $stats['upcoming'] ?? 0 }}</span>
                </div>
                <div class="d-flex justify-content-between align-items-center">
                    <span>Cancelled</span>
                    <span class="badge bg-secondary rounded-pill fs-6">{{ $stats['cancelled'] ?? 0 }}</span>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-lg-8">
        <!-- Personal Information -->
        <div class="card mb-4">
            <div class="card-header">
                <i class="bi bi-person-vcard me-2"></i>Personal Information
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="text-muted small">Full Name</label>
                        <p class="mb-0 fw-semibold">{{ $user->name }}</p>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="text-muted small">Email Address</label>
                        <p class="mb-0 fw-semibold">{{ $user->email }}</p>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="text-muted small">Phone Number</label>
                        <p class="mb-0 fw-semibold">{{ $user->phone ?? 'Not provided' }}</p>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="text-muted small">Date of Birth</label>
                        <p class="mb-0 fw-semibold">{{ $user->date_of_birth ? $user->date_of_birth->format('F d, Y') : 'Not provided' }}</p>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="text-muted small">Gender</label>
                        <p class="mb-0 fw-semibold">{{ ucfirst($user->gender ?? 'Not provided') }}</p>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="text-muted small">Member Since</label>
                        <p class="mb-0 fw-semibold">{{ $user->created_at->format('F d, Y') }}</p>
                    </div>
                    <div class="col-12">
                        <label class="text-muted small">Address</label>
                        <p class="mb-0 fw-semibold">{{ $user->address ?? 'Not provided' }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Medical Information -->
        @if($patient)
            <div class="card mb-4">
                <div class="card-header">
                    <i class="bi bi-heart-pulse me-2"></i>Medical Information
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="text-muted small">Blood Type</label>
                            <p class="mb-0 fw-semibold">{{ $patient->blood_type ?? 'Not provided' }}</p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="text-muted small">Height</label>
                            <p class="mb-0 fw-semibold">{{ $patient->height ? $patient->height . ' cm' : 'Not provided' }}</p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="text-muted small">Weight</label>
                            <p class="mb-0 fw-semibold">{{ $patient->weight ? $patient->weight . ' kg' : 'Not provided' }}</p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="text-muted small">Insurance Provider</label>
                            <p class="mb-0 fw-semibold">{{ $patient->insurance_provider ?? 'Not provided' }}</p>
                        </div>
                        <div class="col-12 mb-3">
                            <label class="text-muted small">Known Allergies</label>
                            <p class="mb-0 fw-semibold">{{ $patient->allergies ?? 'None reported' }}</p>
                        </div>
                        <div class="col-12">
                            <label class="text-muted small">Medical History</label>
                            <p class="mb-0 fw-semibold">{{ $patient->medical_history ?? 'No medical history recorded' }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Emergency Contact -->
            <div class="card">
                <div class="card-header bg-danger text-white">
                    <i class="bi bi-exclamation-triangle me-2"></i>Emergency Contact
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label class="text-muted small">Contact Name</label>
                            <p class="mb-0 fw-semibold">{{ $patient->emergency_contact_name ?? 'Not provided' }}</p>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="text-muted small">Contact Phone</label>
                            <p class="mb-0 fw-semibold">{{ $patient->emergency_contact_phone ?? 'Not provided' }}</p>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="text-muted small">Relationship</label>
                            <p class="mb-0 fw-semibold">{{ $patient->emergency_contact_relationship ?? 'Not provided' }}</p>
                        </div>
                    </div>
                    @if(!$patient->emergency_contact_name)
                        <div class="alert alert-warning mb-0">
                            <i class="bi bi-exclamation-triangle me-1"></i>
                            <strong>Important:</strong> Please add an emergency contact for your safety.
                            <a href="{{ route('patient.profile.edit') }}" class="alert-link">Update now</a>
                        </div>
                    @endif
                </div>
            </div>
        @else
            <div class="alert alert-info">
                <i class="bi bi-info-circle me-1"></i>
                Your patient profile is not complete. <a href="{{ route('patient.profile.edit') }}" class="alert-link">Complete your profile now</a> to provide medical information.
            </div>
        @endif
    </div>
</div>
@endsection
