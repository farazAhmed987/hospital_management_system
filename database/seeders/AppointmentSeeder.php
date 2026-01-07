<?php

namespace Database\Seeders;

use App\Models\Appointment;
use App\Models\Doctor;
use App\Models\Patient;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class AppointmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $doctors = Doctor::all();
        $patients = Patient::all();

        if ($doctors->isEmpty() || $patients->isEmpty()) {
            return;
        }

        $statuses = ['pending', 'approved', 'completed', 'rejected', 'cancelled'];
        $reasons = [
            'Annual checkup',
            'Follow-up consultation',
            'Headache and dizziness',
            'Back pain',
            'Skin rash',
            'Chest discomfort',
            'Joint pain',
            'General consultation',
            'Fever and cold symptoms',
            'Routine examination',
        ];

        // Create some past appointments (completed/cancelled)
        foreach ($patients as $patient) {
            // 2-3 past appointments per patient
            $numPastAppointments = rand(2, 3);
            
            for ($i = 0; $i < $numPastAppointments; $i++) {
                $doctor = $doctors->random();
                $daysAgo = rand(7, 60);
                $appointmentDate = Carbon::now()->subDays($daysAgo);
                
                // Skip weekends for realism
                while ($appointmentDate->isWeekend()) {
                    $appointmentDate->subDay();
                }
                
                $status = rand(0, 10) > 2 ? 'completed' : 'cancelled';
                
                Appointment::create([
                    'patient_id' => $patient->id,
                    'doctor_id' => $doctor->id,
                    'appointment_date' => $appointmentDate->format('Y-m-d'),
                    'appointment_time' => sprintf('%02d:00', rand(9, 17)),
                    'status' => $status,
                    'reason' => $reasons[array_rand($reasons)],
                    'notes' => $status === 'completed' ? 'Patient visited as scheduled.' : null,
                    'diagnosis' => $status === 'completed' ? 'General health assessment - no concerns.' : null,
                    'prescription' => $status === 'completed' ? "Rest and hydration\nVitamin supplements as needed" : null,
                    'created_at' => $appointmentDate->copy()->subDays(rand(1, 7)),
                    'updated_at' => $appointmentDate,
                ]);
            }
        }

        // Create some upcoming/pending appointments
        foreach ($patients->take(3) as $patient) {
            $doctor = $doctors->random();
            $daysAhead = rand(1, 14);
            $appointmentDate = Carbon::now()->addDays($daysAhead);
            
            // Skip weekends
            while ($appointmentDate->isWeekend()) {
                $appointmentDate->addDay();
            }
            
            Appointment::create([
                'patient_id' => $patient->id,
                'doctor_id' => $doctor->id,
                'appointment_date' => $appointmentDate->format('Y-m-d'),
                'appointment_time' => sprintf('%02d:30', rand(9, 16)),
                'status' => 'approved',
                'reason' => $reasons[array_rand($reasons)],
                'notes' => 'Scheduled appointment.',
            ]);
        }

        // Create some pending appointments (awaiting approval)
        foreach ($patients->take(2) as $patient) {
            $doctor = $doctors->random();
            $daysAhead = rand(3, 20);
            $appointmentDate = Carbon::now()->addDays($daysAhead);
            
            while ($appointmentDate->isWeekend()) {
                $appointmentDate->addDay();
            }
            
            Appointment::create([
                'patient_id' => $patient->id,
                'doctor_id' => $doctor->id,
                'appointment_date' => $appointmentDate->format('Y-m-d'),
                'appointment_time' => sprintf('%02d:00', rand(10, 17)),
                'status' => 'pending',
                'reason' => $reasons[array_rand($reasons)],
            ]);
        }
    }
}
