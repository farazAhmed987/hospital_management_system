@extends('layouts.app')

@section('title', 'My Appointments')
@section('page-title', 'My Appointments')

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
    <div class="card-header">
        <i class="bi bi-calendar-check me-2"></i>My Appointments
    </div>
    <div class="card-body">
        <!-- Filters -->
        <form method="GET" action="{{ route('doctor.appointments.index') }}" class="row g-3 mb-4">
            <div class="col-md-3">
                <select name="status" class="form-select">
                    <option value="all">All Statuses</option>
                    @foreach(\App\Models\Appointment::STATUSES as $key => $value)
                        <option value="{{ $key }}" {{ request('status') === $key ? 'selected' : '' }}>{{ $value }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3">
                <input type="date" name="date" class="form-control" value="{{ request('date') }}" placeholder="Date">
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
                        <th>Patient</th>
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
                            <td>
                                <strong>{{ $appointment->patient->user->name ?? 'N/A' }}</strong>
                                <br>
                                <small class="text-muted">{{ $appointment->patient->user->email ?? '' }}</small>
                            </td>
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
                                    <a href="{{ route('doctor.appointments.show', $appointment) }}" class="btn btn-sm btn-outline-info" title="View">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                    @if($appointment->status === 'pending')
                                        <form method="POST" action="{{ route('doctor.appointments.approve', $appointment) }}" class="d-inline">
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
                                    @if($appointment->status === 'approved')
                                        <button type="button" class="btn btn-sm btn-outline-primary" title="Complete" 
                                                data-bs-toggle="modal" data-bs-target="#completeModal{{ $appointment->id }}">
                                            <i class="bi bi-check-circle"></i>
                                        </button>
                                        <form method="POST" action="{{ route('doctor.appointments.no-show', $appointment) }}" class="d-inline">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" class="btn btn-sm btn-outline-dark" title="No Show">
                                                <i class="bi bi-person-x"></i>
                                            </button>
                                        </form>
                                    @endif
                                </div>

                                <!-- Reject Modal -->
                                @if($appointment->status === 'pending')
                                <div class="modal fade" id="rejectModal{{ $appointment->id }}" tabindex="-1">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title">Reject Appointment</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                            </div>
                                            <form method="POST" action="{{ route('doctor.appointments.reject', $appointment) }}">
                                                @csrf
                                                @method('PATCH')
                                                <div class="modal-body">
                                                    <div class="mb-3">
                                                        <label class="form-label">Reason for Rejection</label>
                                                        <textarea class="form-control" name="rejection_reason" rows="3" required></textarea>
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

                                <!-- Complete Modal -->
                                @if($appointment->status === 'approved')
                                <div class="modal fade" id="completeModal{{ $appointment->id }}" tabindex="-1">
                                    <div class="modal-dialog modal-lg">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title">Complete Appointment</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                            </div>
                                            <form method="POST" action="{{ route('doctor.appointments.complete', $appointment) }}">
                                                @csrf
                                                @method('PATCH')
                                                <div class="modal-body">
                                                    <div class="mb-3">
                                                        <label class="form-label">Diagnosis</label>
                                                        <textarea class="form-control" name="diagnosis" rows="2"></textarea>
                                                    </div>
                                                    <div class="mb-3">
                                                        <label class="form-label">Prescription</label>
                                                        <textarea class="form-control" name="prescription" rows="3"></textarea>
                                                    </div>
                                                    <div class="mb-3">
                                                        <label class="form-label">Notes</label>
                                                        <textarea class="form-control" name="notes" rows="2"></textarea>
                                                    </div>
                                                    <div class="mb-3">
                                                        <label class="form-label">Fee Charged ($)</label>
                                                        <input type="number" class="form-control" name="fee_charged" step="0.01" min="0" 
                                                               value="{{ $appointment->doctor->consultation_fee }}">
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                                    <button type="submit" class="btn btn-success">Complete</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center text-muted">No appointments found</td>
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
