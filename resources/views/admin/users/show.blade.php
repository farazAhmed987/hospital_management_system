@extends('layouts.app')

@section('title', 'User Details')
@section('page-title', 'User Details')

@section('sidebar-menu')
    <li class="nav-item">
        <a class="nav-link" href="{{ route('admin.dashboard') }}">
            <i class="bi bi-speedometer2"></i>Dashboard
        </a>
    </li>
    <div class="sidebar-section">Management</div>
    <li class="nav-item">
        <a class="nav-link active" href="{{ route('admin.users.index') }}">
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
<div class="row">
    <div class="col-md-4 mb-4">
        <div class="card">
            <div class="card-body text-center">
                <div class="mb-3">
                    <i class="bi bi-person-circle" style="font-size: 5rem; color: #6c757d;"></i>
                </div>
                <h4>{{ $user->name }}</h4>
                <p class="text-muted">{{ $user->email }}</p>
                <span class="badge bg-{{ $user->role === 'admin' ? 'danger' : ($user->role === 'doctor' ? 'success' : 'primary') }} fs-6">
                    {{ ucfirst($user->role) }}
                </span>
                <div class="mt-2">
                    @if($user->is_active)
                        <span class="badge bg-success">Active</span>
                    @else
                        <span class="badge bg-secondary">Inactive</span>
                    @endif
                </div>
                <hr>
                <div class="d-grid gap-2">
                    <a href="{{ route('admin.users.edit', $user) }}" class="btn btn-primary">
                        <i class="bi bi-pencil me-1"></i>Edit User
                    </a>
                    <a href="{{ route('admin.users.index') }}" class="btn btn-secondary">
                        <i class="bi bi-arrow-left me-1"></i>Back to List
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-8">
        <div class="card mb-4">
            <div class="card-header">
                <i class="bi bi-info-circle me-2"></i>Basic Information
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label text-muted">Phone</label>
                        <p class="mb-0">{{ $user->phone ?? 'N/A' }}</p>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label text-muted">Date of Birth</label>
                        <p class="mb-0">{{ $user->date_of_birth ? $user->date_of_birth->format('M d, Y') : 'N/A' }}</p>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label text-muted">Gender</label>
                        <p class="mb-0">{{ $user->gender ? ucfirst($user->gender) : 'N/A' }}</p>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label text-muted">Joined</label>
                        <p class="mb-0">{{ $user->created_at->format('M d, Y') }}</p>
                    </div>
                    <div class="col-12">
                        <label class="form-label text-muted">Address</label>
                        <p class="mb-0">{{ $user->address ?? 'N/A' }}</p>
                    </div>
                </div>
            </div>
        </div>

        @if($user->isDoctor() && $user->doctor)
            <div class="card mb-4">
                <div class="card-header">
                    <i class="bi bi-person-badge me-2"></i>Doctor Information
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label text-muted">Specialization</label>
                            <p class="mb-0">{{ $user->doctor->specialization }}</p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label text-muted">Qualification</label>
                            <p class="mb-0">{{ $user->doctor->qualification }}</p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label text-muted">License Number</label>
                            <p class="mb-0">{{ $user->doctor->license_number }}</p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label text-muted">Experience</label>
                            <p class="mb-0">{{ $user->doctor->experience_years }} years</p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label text-muted">Consultation Fee</label>
                            <p class="mb-0">${{ number_format($user->doctor->consultation_fee, 2) }}</p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label text-muted">Availability</label>
                            <p class="mb-0">
                                @if($user->doctor->is_available)
                                    <span class="badge bg-success">Available</span>
                                @else
                                    <span class="badge bg-secondary">Not Available</span>
                                @endif
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        @endif

        @if($user->isPatient() && $user->patient)
            <div class="card">
                <div class="card-header">
                    <i class="bi bi-heart-pulse me-2"></i>Patient Information
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label text-muted">Blood Group</label>
                            <p class="mb-0">{{ $user->patient->blood_group ?? 'N/A' }}</p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label text-muted">Emergency Contact</label>
                            <p class="mb-0">{{ $user->patient->emergency_contact_name ?? 'N/A' }} {{ $user->patient->emergency_contact_phone ? '(' . $user->patient->emergency_contact_phone . ')' : '' }}</p>
                        </div>
                        <div class="col-12 mb-3">
                            <label class="form-label text-muted">Allergies</label>
                            <p class="mb-0">{{ $user->patient->allergies ?? 'None reported' }}</p>
                        </div>
                        <div class="col-12">
                            <label class="form-label text-muted">Medical History</label>
                            <p class="mb-0">{{ $user->patient->medical_history ?? 'No history recorded' }}</p>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </div>
</div>
@endsection
