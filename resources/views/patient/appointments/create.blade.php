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
                        <div id="timeSlotsContainer">
                            <div class="alert alert-secondary">
                                <i class="bi bi-info-circle me-1"></i>Please select a doctor and date first to see available time slots.
                            </div>
                        </div>
                        <input type="hidden" name="appointment_time" id="appointment_time" value="{{ old('appointment_time') }}">
                        @error('appointment_time')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
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
    const timeSlotsContainer = document.getElementById('timeSlotsContainer');
    const appointmentTimeInput = document.getElementById('appointment_time');
    const submitBtn = document.getElementById('submitBtn');
    const doctorInfo = document.getElementById('doctorInfo');
    const bookingSummary = document.getElementById('bookingSummary');
    
    let selectedTimeSlot = null;

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
        
        loadTimeSlots();
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
        
        loadTimeSlots();
    });

    // Load time slots on date change
    dateInput.addEventListener('change', loadTimeSlots);

    function loadTimeSlots() {
        const doctorId = doctorSelect.value;
        const date = dateInput.value;
        
        selectedTimeSlot = null;
        appointmentTimeInput.value = '';
        submitBtn.disabled = true;
        bookingSummary.style.display = 'none';
        
        if (!doctorId || !date) {
            timeSlotsContainer.innerHTML = `
                <div class="alert alert-secondary">
                    <i class="bi bi-info-circle me-1"></i>Please select a doctor and date first to see available time slots.
                </div>
            `;
            return;
        }
        
        timeSlotsContainer.innerHTML = `
            <div class="text-center py-3">
                <div class="spinner-border text-primary" role="status">
                    <span class="visually-hidden">Loading...</span>
                </div>
                <p class="mt-2 text-muted">Loading available slots...</p>
            </div>
        `;
        
        fetch(`{{ route('patient.appointments.slots') }}?doctor_id=${doctorId}&date=${date}`)
            .then(response => response.json())
            .then(data => {
                if (data.slots && data.slots.length > 0) {
                    let slotsHtml = '<div class="row g-2">';
                    data.slots.forEach(slot => {
                        slotsHtml += `
                            <div class="col-6 col-md-3">
                                <button type="button" class="btn btn-outline-primary w-100 time-slot-btn" data-time="${slot}">
                                    <i class="bi bi-clock me-1"></i>${formatTime(slot)}
                                </button>
                            </div>
                        `;
                    });
                    slotsHtml += '</div>';
                    timeSlotsContainer.innerHTML = slotsHtml;
                    
                    // Add click handlers
                    document.querySelectorAll('.time-slot-btn').forEach(btn => {
                        btn.addEventListener('click', function() {
                            document.querySelectorAll('.time-slot-btn').forEach(b => {
                                b.classList.remove('btn-primary');
                                b.classList.add('btn-outline-primary');
                            });
                            this.classList.remove('btn-outline-primary');
                            this.classList.add('btn-primary');
                            
                            selectedTimeSlot = this.dataset.time;
                            appointmentTimeInput.value = selectedTimeSlot;
                            submitBtn.disabled = false;
                            
                            document.getElementById('summaryTime').textContent = formatTime(selectedTimeSlot);
                            document.getElementById('summaryDate').textContent = formatDate(dateInput.value);
                            bookingSummary.style.display = 'block';
                        });
                    });
                } else {
                    timeSlotsContainer.innerHTML = `
                        <div class="alert alert-warning">
                            <i class="bi bi-exclamation-triangle me-1"></i>
                            No available time slots for this date. The doctor may not be available or all slots are booked.
                            Please try another date.
                        </div>
                    `;
                }
            })
            .catch(error => {
                console.error('Error:', error);
                timeSlotsContainer.innerHTML = `
                    <div class="alert alert-danger">
                        <i class="bi bi-x-circle me-1"></i>Error loading time slots. Please try again.
                    </div>
                `;
            });
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
