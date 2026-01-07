@extends('layouts.app')

@section('title', 'Edit Schedule')
@section('page-title', 'Edit Schedule')

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
    <div class="card-header">
        <i class="bi bi-pencil me-2"></i>Edit Schedule - {{ $schedule->day_name }}
    </div>
    <div class="card-body">
        <form method="POST" action="{{ route('doctor.schedules.update', $schedule) }}">
            @csrf
            @method('PUT')

            <div class="row">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label class="form-label">Day of Week</label>
                        <input type="text" class="form-control" value="{{ $schedule->day_name }}" disabled>
                        <small class="text-muted">Day cannot be changed. Create a new schedule for a different day.</small>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="start_time" class="form-label">Start Time <span class="text-danger">*</span></label>
                            <input type="time" class="form-control @error('start_time') is-invalid @enderror" 
                                   id="start_time" name="start_time" value="{{ old('start_time', date('H:i', strtotime($schedule->start_time))) }}" required>
                            @error('start_time')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="end_time" class="form-label">End Time <span class="text-danger">*</span></label>
                            <input type="time" class="form-control @error('end_time') is-invalid @enderror" 
                                   id="end_time" name="end_time" value="{{ old('end_time', date('H:i', strtotime($schedule->end_time))) }}" required>
                            @error('end_time')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="slot_duration" class="form-label">Slot Duration (minutes) <span class="text-danger">*</span></label>
                            <select class="form-select @error('slot_duration') is-invalid @enderror" id="slot_duration" name="slot_duration" required>
                                <option value="15" {{ old('slot_duration', $schedule->slot_duration) == 15 ? 'selected' : '' }}>15 minutes</option>
                                <option value="20" {{ old('slot_duration', $schedule->slot_duration) == 20 ? 'selected' : '' }}>20 minutes</option>
                                <option value="30" {{ old('slot_duration', $schedule->slot_duration) == 30 ? 'selected' : '' }}>30 minutes</option>
                                <option value="45" {{ old('slot_duration', $schedule->slot_duration) == 45 ? 'selected' : '' }}>45 minutes</option>
                                <option value="60" {{ old('slot_duration', $schedule->slot_duration) == 60 ? 'selected' : '' }}>60 minutes</option>
                            </select>
                            @error('slot_duration')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="max_patients_per_slot" class="form-label">Max Patients/Slot <span class="text-danger">*</span></label>
                            <input type="number" class="form-control @error('max_patients_per_slot') is-invalid @enderror" 
                                   id="max_patients_per_slot" name="max_patients_per_slot" value="{{ old('max_patients_per_slot', $schedule->max_patients_per_slot) }}" min="1" max="10" required>
                            @error('max_patients_per_slot')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="mb-3 form-check">
                        <input type="checkbox" class="form-check-input" id="is_active" name="is_active" value="1"
                               {{ old('is_active', $schedule->is_active) ? 'checked' : '' }}>
                        <label class="form-check-label" for="is_active">Schedule is Active</label>
                    </div>
                </div>
            </div>

            <hr>

            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-check-circle me-1"></i>Update Schedule
                </button>
                <a href="{{ route('doctor.schedules.index') }}" class="btn btn-secondary">
                    <i class="bi bi-x-circle me-1"></i>Cancel
                </a>
            </div>
        </form>
    </div>
</div>
@endsection
