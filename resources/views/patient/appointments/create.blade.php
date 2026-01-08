@extends('layouts.app')

@section('title', 'Book Appointment')
@section('page-title', 'Book Appointment')

@section('sidebar-menu')
    <li class="nav-item">
        <a class="nav-link" href="{{ route('patient.dashboard') }}">
            <i class="bi bi-speedometer2"></i>Dashboard
        </a>
    </li>
    <div class="sidebar-section">Appointments</div>
    <li class="nav-item">
        <a class="nav-link active" href="{{ route('patient.appointments.create') }}">
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
        <a class="nav-link" href="{{ route('patient.profile.show') }}">
            <i class="bi bi-person"></i>My Profile
        </a>
    </li>
@endsection

@section('content')
<div class="row">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header">
                <i class="bi bi-calendar-plus me-2"></i>Book New Appointment
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('patient.appointments.store') }}" id="appointmentForm">
                    @csrf
                    
                    <!-- Step 1: Select Doctor -->
                    <div class="mb-4">
                        <h5 class="border-bottom pb-2"><span class="badge bg-primary rounded-circle me-2">1</span>Select Doctor</h5>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="specialization" class="form-label">Specialization</label>
                                <select class="form-select" id="specialization" name="specialization">
                                    <option value="">All Specializations</option>
                                    @foreach($specializations as $spec)
                                        <option value="{{ $spec }}" {{ old('specialization') === $spec ? 'selected' : '' }}>{{ $spec }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="doctor_id" class="form-label">Doctor <span class="text-danger">*</span></label>
                                <select class="form-select @error('doctor_id') is-invalid @enderror" id="doctor_id" name="doctor_id" required>
                                    <option value="">Select a Doctor</option>
                                    @foreach($doctors as $doctor)
                                        <option value="{{ $doctor->id }}" 
                                            data-specialization="{{ $doctor->specialization }}"
                                            data-fee="{{ $doctor->consultation_fee }}"
                                            {{ old('doctor_id') == $doctor->id ? 'selected' : '' }}>
                                            Dr. {{ $doctor->user->name }} - {{ $doctor->specialization }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('doctor_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        
                        <div id="doctorInfo" class="alert alert-info d-none">
                            <div class="row">
                                <div class="col-md-6">
                                    <strong><i class="bi bi-person-badge me-1"></i>Doctor:</strong> <span id="doctorName"></span>
                                </div>
                                <div class="col-md-3">
                                    <strong><i class="bi bi-award me-1"></i>Specialization:</strong> <span id="doctorSpec"></span>
                                </div>
                                <div class="col-md-3">
                                    <strong><i class="bi bi-currency-dollar me-1"></i>Fee:</strong> $<span id="doctorFee"></span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Step 2: Select Date -->
                    <div class="mb-4">
                        <h5 class="border-bottom pb-2"><span class="badge bg-primary rounded-circle me-2">2</span>Select Date</h5>
                        <div class="row">
                            <div class="col-md-6">
                                <label for="appointment_date" class="form-label">Appointment Date <span class="text-danger">*</span></label>
                                <input type="date" class="form-control @error('appointment_date') is-invalid @enderror" 
                                    id="appointment_date" name="appointment_date" 
                                    min="{{ date('Y-m-d') }}" 
                                    max="{{ date('Y-m-d', strtotime('+30 days')) }}"
                                    value="{{ old('appointment_date') }}" required>
                                @error('appointment_date')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="text-muted">You can book up to 30 days in advance</small>
                            </div>
                        </div>
                    </div>

                    <!-- Step 3: Select Time Slot -->
                    <div class="mb-4">
                        <h5 class="border-bottom pb-2"><span class="badge bg-primary rounded-circle me-2">3</span>Select Time Slot</h5>
                        <div class="row">
                            <div class="col-md-6">
                                <label for="appointment_time" class="form-label">Appointment Time <span class="text-danger">*</span></label>
                                <select class="form-select @error('appointment_time') is-invalid @enderror" 
                                    id="appointment_time" name="appointment_time" required>
                                    <option value="">-- Select Time Slot --</option>
                                    <option value="09:00" {{ old('appointment_time') == '09:00' ? 'selected' : '' }}>09:00 AM - Morning</option>
                                    <option value="10:00" {{ old('appointment_time') == '10:00' ? 'selected' : '' }}>10:00 AM - Morning</option>
                                    <option value="11:00" {{ old('appointment_time') == '11:00' ? 'selected' : '' }}>11:00 AM - Late Morning</option>
                                    <option value="14:00" {{ old('appointment_time') == '14:00' ? 'selected' : '' }}>02:00 PM - Afternoon</option>
                                    <option value="15:00" {{ old('appointment_time') == '15:00' ? 'selected' : '' }}>03:00 PM - Afternoon</option>
                                    <option value="16:00" {{ old('appointment_time') == '16:00' ? 'selected' : '' }}>04:00 PM - Late Afternoon</option>
                                    <option value="17:00" {{ old('appointment_time') == '17:00' ? 'selected' : '' }}>05:00 PM - Evening</option>
                                </select>
                                @error('appointment_time')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="text-muted">Select your preferred time slot</small>
                            </div>
                        </div>
                    </div>

                    <!-- Step 4: Additional Information -->
                    <div class="mb-4">
                        <h5 class="border-bottom pb-2"><span class="badge bg-primary rounded-circle me-2">4</span>Additional Information</h5>
                        <div class="mb-3">
                            <label for="reason" class="form-label">Reason for Visit</label>
                            <textarea class="form-control @error('reason') is-invalid @enderror" id="reason" name="reason" rows="3" placeholder="Please describe your symptoms or reason for the appointment...">{{ old('reason') }}</textarea>
                            @error('reason')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label for="notes" class="form-label">Additional Notes</label>
                            <textarea class="form-control @error('notes') is-invalid @enderror" id="notes" name="notes" rows="2" placeholder="Any additional notes for the doctor...">{{ old('notes') }}</textarea>
                            @error('notes')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary" id="submitBtn" disabled>
                            <i class="bi bi-calendar-check me-1"></i>Book Appointment
                        </button>
                        <a href="{{ route('patient.appointments.index') }}" class="btn btn-outline-secondary">
                            <i class="bi bi-arrow-left me-1"></i>Cancel
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
    <div class="col-lg-4">
        <div class="card">
            <div class="card-header bg-info text-white">
                <i class="bi bi-info-circle me-2"></i>Booking Guidelines
            </div>
            <div class="card-body">
                <ul class="list-unstyled mb-0">
                    <li class="mb-3">
                        <i class="bi bi-check-circle text-success me-2"></i>
                        <strong>Arrive Early:</strong> Please arrive 15 minutes before your appointment.
                    </li>
                    <li class="mb-3">
                        <i class="bi bi-check-circle text-success me-2"></i>
                        <strong>Bring Documents:</strong> Carry your ID and any relevant medical records.
                    </li>
                    <li class="mb-3">
                        <i class="bi bi-check-circle text-success me-2"></i>
                        <strong>Cancellation:</strong> You can cancel up to 24 hours before the appointment.
                    </li>
                    <li>
                        <i class="bi bi-check-circle text-success me-2"></i>
                        <strong>Payment:</strong> Consultation fee is payable at the time of visit.
                    </li>
                </ul>
            </div>
        </div>
        
        <div class="card mt-3" id="bookingSummary" style="display: none;">
            <div class="card-header bg-success text-white">
                <i class="bi bi-receipt me-2"></i>Booking Summary
            </div>
            <div class="card-body">
                <table class="table table-sm table-borderless mb-0">
                    <tr>
                        <th>Doctor:</th>
                        <td id="summaryDoctor">-</td>
                    </tr>
                    <tr>
                        <th>Date:</th>
                        <td id="summaryDate">-</td>
                    </tr>
                    <tr>
                        <th>Time:</th>
                        <td id="summaryTime">-</td>
                    </tr>
                    <tr class="border-top">
                        <th>Fee:</th>
                        <td id="summaryFee" class="fw-bold text-primary">-</td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const specializationSelect = document.getElementById('specialization');
    const doctorSelect = document.getElementById('doctor_id');
    const dateInput = document.getElementById('appointment_date');
    const appointmentTimeInput = document.getElementById('appointment_time');
    const submitBtn = document.getElementById('submitBtn');
    const doctorInfo = document.getElementById('doctorInfo');
    const bookingSummary = document.getElementById('bookingSummary');

    // Filter doctors by specialization
    specializationSelect.addEventListener('change', function() {
        const spec = this.value;
        const options = doctorSelect.querySelectorAll('option[data-specialization]');
        
        doctorSelect.value = '';
        doctorInfo.classList.add('d-none');
        
        options.forEach(option => {
            if (!spec || option.dataset.specialization === spec) {
                option.style.display = '';
            } else {
                option.style.display = 'none';
            }
        });
        
        checkFormCompletion();
    });

    // Show doctor info on selection
    doctorSelect.addEventListener('change', function() {
        const option = this.options[this.selectedIndex];
        if (this.value) {
            document.getElementById('doctorName').textContent = option.text.split(' - ')[0];
            document.getElementById('doctorSpec').textContent = option.dataset.specialization;
            document.getElementById('doctorFee').textContent = parseFloat(option.dataset.fee).toFixed(2);
            doctorInfo.classList.remove('d-none');
            
            document.getElementById('summaryDoctor').textContent = option.text.split(' - ')[0];
            document.getElementById('summaryFee').textContent = '$' + parseFloat(option.dataset.fee).toFixed(2);
        } else {
            doctorInfo.classList.add('d-none');
        }
        
        checkFormCompletion();
    });

    // Check form completion on date change
    dateInput.addEventListener('change', function() {
        if (this.value) {
            document.getElementById('summaryDate').textContent = formatDate(this.value);
        }
        checkFormCompletion();
    });

    // Check form completion on time change
    appointmentTimeInput.addEventListener('change', function() {
        if (this.value) {
            document.getElementById('summaryTime').textContent = formatTime(this.value);
        }
        checkFormCompletion();
    });

    function checkFormCompletion() {
        const doctorId = doctorSelect.value;
        const date = dateInput.value;
        const time = appointmentTimeInput.value;
        
        if (doctorId && date && time) {
            submitBtn.disabled = false;
            bookingSummary.style.display = 'block';
        } else {
            submitBtn.disabled = true;
            bookingSummary.style.display = 'none';
        }
    }
    
    function formatTime(time) {
        const [hours, minutes] = time.split(':');
        const hour = parseInt(hours);
        const ampm = hour >= 12 ? 'PM' : 'AM';
        const displayHour = hour % 12 || 12;
        return `${displayHour}:${minutes} ${ampm}`;
    }
    
    function formatDate(dateStr) {
        const date = new Date(dateStr);
        return date.toLocaleDateString('en-US', { weekday: 'short', month: 'short', day: 'numeric', year: 'numeric' });
    }
});
</script>
@endpush
@endsection
