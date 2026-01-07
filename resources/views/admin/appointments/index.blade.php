@extends('layouts.app')

@section('title', 'Appointment Management')
@section('page-title', 'Appointment Management')

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
<div class="card">
    <div class="card-header">
        <i class="bi bi-calendar-check me-2"></i>All Appointments
    </div>
    <div class="card-body">
        <!-- Filters -->
        <form method="GET" action="{{ route('admin.appointments.index') }}" class="row g-3 mb-4">
            <div class="col-md-2">
                <select name="status" class="form-select">
                    <option value="all">All Statuses</option>
                    @foreach(\App\Models\Appointment::STATUSES as $key => $value)
                        <option value="{{ $key }}" {{ request('status') === $key ? 'selected' : '' }}>{{ $value }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <input type="date" name="date" class="form-control" value="{{ request('date') }}" placeholder="Date">
            </div>
            <div class="col-md-2">
                <select name="doctor_id" class="form-select">
                    <option value="">All Doctors</option>
                    @foreach($doctors as $doctor)
                        <option value="{{ $doctor->id }}" {{ request('doctor_id') == $doctor->id ? 'selected' : '' }}>
                            Dr. {{ $doctor->user->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3">
                <input type="text" name="search" class="form-control" placeholder="Search patient..." value="{{ request('search') }}">
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
                        <th>ID</th>
                        <th>Patient</th>
                        <th>Doctor</th>
                        <th>Date</th>
                        <th>Time</th>
                        <th>Reason</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($appointments as $appointment)
                        <tr>
                            <td>{{ $appointment->id }}</td>
                            <td>{{ $appointment->patient->user->name ?? 'N/A' }}</td>
                            <td>Dr. {{ $appointment->doctor->user->name ?? 'N/A' }}</td>
                            <td>{{ $appointment->formatted_date }}</td>
                            <td>{{ $appointment->formatted_time }}</td>
                            <td>{{ Str::limit($appointment->reason_for_visit, 30) }}</td>
                            <td>
                                <span class="badge {{ $appointment->status_badge_class }}">
                                    {{ ucfirst($appointment->status) }}
                                </span>
                            </td>
                            <td>
                                <div class="btn-group" role="group">
                                    <a href="{{ route('admin.appointments.show', $appointment) }}" class="btn btn-sm btn-outline-info" title="View">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                    @if($appointment->status === 'pending')
                                        <form method="POST" action="{{ route('admin.appointments.approve', $appointment) }}" class="d-inline">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" class="btn btn-sm btn-outline-success" title="Approve">
                                                <i class="bi bi-check-lg"></i>
                                            </button>
                                        </form>
                                        <button type="button" class="btn btn-sm btn-outline-danger" title="Reject" 
                                                data-bs-toggle="modal" data-bs-target="#rejectModal{{ $appointment->id }}">
                                            <i class="bi bi-x-lg"></i>
                                        </button>
                                    @endif
                                </div>

                                <!-- Reject Modal -->
                                <div class="modal fade" id="rejectModal{{ $appointment->id }}" tabindex="-1">
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
                                                        <label for="rejection_reason{{ $appointment->id }}" class="form-label">Reason for Rejection</label>
                                                        <textarea class="form-control" id="rejection_reason{{ $appointment->id }}" name="rejection_reason" rows="3" required></textarea>
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
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center text-muted">No appointments found</td>
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
