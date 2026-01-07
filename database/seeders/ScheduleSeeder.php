<?php

namespace Database\Seeders;

use App\Models\Doctor;
use App\Models\Schedule;
use Illuminate\Database\Seeder;

class ScheduleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $doctors = Doctor::all();

        // Define typical schedules for each doctor
        $scheduleTemplates = [
            // Monday to Friday, Morning schedule
            [
                ['day_of_week' => 'monday', 'start_time' => '09:00', 'end_time' => '13:00', 'slot_duration' => 30],
                ['day_of_week' => 'tuesday', 'start_time' => '09:00', 'end_time' => '13:00', 'slot_duration' => 30],
                ['day_of_week' => 'wednesday', 'start_time' => '09:00', 'end_time' => '13:00', 'slot_duration' => 30],
                ['day_of_week' => 'thursday', 'start_time' => '09:00', 'end_time' => '13:00', 'slot_duration' => 30],
                ['day_of_week' => 'friday', 'start_time' => '09:00', 'end_time' => '13:00', 'slot_duration' => 30],
            ],
            // Monday, Wednesday, Friday - Full day
            [
                ['day_of_week' => 'monday', 'start_time' => '08:00', 'end_time' => '12:00', 'slot_duration' => 30],
                ['day_of_week' => 'monday', 'start_time' => '14:00', 'end_time' => '18:00', 'slot_duration' => 30],
                ['day_of_week' => 'wednesday', 'start_time' => '08:00', 'end_time' => '12:00', 'slot_duration' => 30],
                ['day_of_week' => 'wednesday', 'start_time' => '14:00', 'end_time' => '18:00', 'slot_duration' => 30],
                ['day_of_week' => 'friday', 'start_time' => '08:00', 'end_time' => '12:00', 'slot_duration' => 30],
                ['day_of_week' => 'friday', 'start_time' => '14:00', 'end_time' => '18:00', 'slot_duration' => 30],
            ],
            // Tuesday, Thursday, Saturday
            [
                ['day_of_week' => 'tuesday', 'start_time' => '10:00', 'end_time' => '14:00', 'slot_duration' => 30],
                ['day_of_week' => 'tuesday', 'start_time' => '16:00', 'end_time' => '20:00', 'slot_duration' => 30],
                ['day_of_week' => 'thursday', 'start_time' => '10:00', 'end_time' => '14:00', 'slot_duration' => 30],
                ['day_of_week' => 'thursday', 'start_time' => '16:00', 'end_time' => '20:00', 'slot_duration' => 30],
                ['day_of_week' => 'saturday', 'start_time' => '09:00', 'end_time' => '13:00', 'slot_duration' => 30],
            ],
            // Weekdays afternoon
            [
                ['day_of_week' => 'monday', 'start_time' => '14:00', 'end_time' => '19:00', 'slot_duration' => 30],
                ['day_of_week' => 'tuesday', 'start_time' => '14:00', 'end_time' => '19:00', 'slot_duration' => 30],
                ['day_of_week' => 'wednesday', 'start_time' => '14:00', 'end_time' => '19:00', 'slot_duration' => 30],
                ['day_of_week' => 'thursday', 'start_time' => '14:00', 'end_time' => '19:00', 'slot_duration' => 30],
                ['day_of_week' => 'friday', 'start_time' => '14:00', 'end_time' => '19:00', 'slot_duration' => 30],
            ],
            // Monday to Saturday, varied
            [
                ['day_of_week' => 'monday', 'start_time' => '09:00', 'end_time' => '12:00', 'slot_duration' => 20],
                ['day_of_week' => 'tuesday', 'start_time' => '15:00', 'end_time' => '19:00', 'slot_duration' => 20],
                ['day_of_week' => 'wednesday', 'start_time' => '09:00', 'end_time' => '12:00', 'slot_duration' => 20],
                ['day_of_week' => 'thursday', 'start_time' => '15:00', 'end_time' => '19:00', 'slot_duration' => 20],
                ['day_of_week' => 'friday', 'start_time' => '09:00', 'end_time' => '12:00', 'slot_duration' => 20],
                ['day_of_week' => 'saturday', 'start_time' => '10:00', 'end_time' => '14:00', 'slot_duration' => 20],
            ],
        ];

        foreach ($doctors as $index => $doctor) {
            $template = $scheduleTemplates[$index % count($scheduleTemplates)];
            
            foreach ($template as $schedule) {
                Schedule::create([
                    'doctor_id' => $doctor->id,
                    'day_of_week' => $schedule['day_of_week'],
                    'start_time' => $schedule['start_time'],
                    'end_time' => $schedule['end_time'],
                    'slot_duration' => $schedule['slot_duration'],
                    'is_active' => true,
                ]);
            }
        }
    }
}
