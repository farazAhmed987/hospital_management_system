@extends('layouts.app')

@section('title', 'My Appointments')
@section('page-title', 'My Appointments')

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
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <span><i class="bi bi-calendar-check me-2"></i>My Appointments</span>
        <a href="{{ route('patient.appointments.create') }}" class="btn btn-primary btn-sm">
            <i class="bi bi-plus-circle me-1"></i>Book New
        </a>
    </div>
    <div class="card-body">
        <!-- Filters -->
        <form method="GET" action="{{ route('patient.appointments.index') }}" class="row g-3 mb-4">
            <div class="col-md-3">
                <select name="status" class="form-select">
                    <option value="all">All Statuses</option>
                    @foreach(\App\Models\Appointment::STATUSES as $key => $value)
                        <option value="{{ $key }}" {{ request('status') === $key ? 'selected' : '' }}>{{ $value }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <button type="submit" class="btn btn-outline-primary w-100">
                    <i class="bi bi-search me-1"></i>Filter
                </button>
            </div>
        </form>

        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>Doctor</th>
                        <th>Specialization</th>
                        <th>Date</th>
                        <th>Time</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($appointments as $appointment)
                        <tr>
                            <td>Dr. {{ $appointment->doctor->user->name ?? 'N/A' }}</td>
                            <td>{{ $appointment->doctor->specialization ?? 'N/A' }}</td>
                            <td>{{ $appointment->formatted_date }}</td>
                            <td>{{ $appointment->formatted_time }}</td>
                            <td>
                                <span class="badge {{ $appointment->status_badge_class }}">
                                    {{ ucfirst($appointment->status) }}
                                </span>
                            </td>
                            <td>
                                <div class="btn-group" role="group">
                                    <a href="{{ route('patient.appointments.show', $appointment) }}" class="btn btn-sm btn-outline-info" title="View">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                    @if($appointment->canBeCancelled())
                                        <form method="POST" action="{{ route('patient.appointments.cancel', $appointment) }}" class="d-inline" onsubmit="return confirm('Are you sure you want to cancel this appointment?')">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" class="btn btn-sm btn-outline-danger" title="Cancel">
                                                <i class="bi bi-x-lg"></i>
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center text-muted py-4">
                                <i class="bi bi-calendar-x fs-1 d-block mb-2"></i>
                                <p class="mb-2">No appointments found</p>
                                <a href="{{ route('patient.appointments.create') }}" class="btn btn-sm btn-primary">Book Your First Appointment</a>
                            </td>
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
