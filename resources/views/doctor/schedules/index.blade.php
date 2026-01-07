@extends('layouts.app')

@section('title', 'My Schedule')
@section('page-title', 'My Schedule')

@section('sidebar-menu')
    <li class="nav-item">
        <a class="nav-link" href="{{ route('doctor.dashboard') }}">
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
        <a class="nav-link active" href="{{ route('doctor.schedules.index') }}">
            <i class="bi bi-clock"></i>My Schedule
        </a>
    </li>
@endsection

@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <span><i class="bi bi-clock me-2"></i>My Weekly Schedule</span>
        <a href="{{ route('doctor.schedules.create') }}" class="btn btn-primary btn-sm">
            <i class="bi bi-plus-circle me-1"></i>Add Schedule
        </a>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>Day</th>
                        <th>Start Time</th>
                        <th>End Time</th>
                        <th>Slot Duration</th>
                        <th>Max Patients/Slot</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($schedules as $schedule)
                        <tr>
                            <td><strong>{{ $schedule->day_name }}</strong></td>
                            <td>{{ $schedule->formatted_start_time }}</td>
                            <td>{{ $schedule->formatted_end_time }}</td>
                            <td>{{ $schedule->slot_duration }} minutes</td>
                            <td>{{ $schedule->max_patients_per_slot }}</td>
                            <td>
                                @if($schedule->is_active)
                                    <span class="badge bg-success">Active</span>
                                @else
                                    <span class="badge bg-secondary">Inactive</span>
                                @endif
                            </td>
                            <td>
                                <div class="btn-group" role="group">
                                    <a href="{{ route('doctor.schedules.edit', $schedule) }}" class="btn btn-sm btn-outline-primary" title="Edit">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    <form method="POST" action="{{ route('doctor.schedules.toggle-status', $schedule) }}" class="d-inline">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" class="btn btn-sm btn-outline-{{ $schedule->is_active ? 'warning' : 'success' }}" title="{{ $schedule->is_active ? 'Deactivate' : 'Activate' }}">
                                            <i class="bi bi-{{ $schedule->is_active ? 'pause' : 'play' }}"></i>
                                        </button>
                                    </form>
                                    <form method="POST" action="{{ route('doctor.schedules.destroy', $schedule) }}" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this schedule?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger" title="Delete">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center text-muted">
                                No schedules defined. <a href="{{ route('doctor.schedules.create') }}">Add your first schedule</a>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
