<?php

namespace Database\Seeders;

use App\Models\Doctor;
use App\Models\User;
use Illuminate\Database\Seeder;

class DoctorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $doctorProfiles = [
            [
                'email' => 'sarah.johnson@careconnect.com',
                'specialization' => 'Cardiology',
                'qualification' => 'MD, FACC',
                'license_number' => 'LIC-CARD-001',
                'consultation_fee' => 150.00,
                'experience_years' => 15,
                'bio' => 'Dr. Sarah Johnson is a board-certified cardiologist with over 15 years of experience in treating heart conditions. She specializes in preventive cardiology and heart failure management.',
            ],
            [
                'email' => 'michael.chen@careconnect.com',
                'specialization' => 'Orthopedics',
                'qualification' => 'MD, FAAOS',
                'license_number' => 'LIC-ORTH-002',
                'consultation_fee' => 175.00,
                'experience_years' => 20,
                'bio' => 'Dr. Michael Chen is an experienced orthopedic surgeon specializing in sports medicine and joint replacement surgery. He has performed over 3000 successful surgeries.',
            ],
            [
                'email' => 'emily.williams@careconnect.com',
                'specialization' => 'Pediatrics',
                'qualification' => 'MD, FAAP',
                'license_number' => 'LIC-PEDI-003',
                'consultation_fee' => 100.00,
                'experience_years' => 12,
                'bio' => 'Dr. Emily Williams is a compassionate pediatrician dedicated to providing comprehensive healthcare for children from infancy through adolescence.',
            ],
            [
                'email' => 'james.brown@careconnect.com',
                'specialization' => 'Neurology',
                'qualification' => 'MD, PhD',
                'license_number' => 'LIC-NEUR-004',
                'consultation_fee' => 200.00,
                'experience_years' => 18,
                'bio' => 'Dr. James Brown is a renowned neurologist with expertise in treating complex neurological disorders including epilepsy, stroke, and movement disorders.',
            ],
            [
                'email' => 'maria.garcia@careconnect.com',
                'specialization' => 'Dermatology',
                'qualification' => 'MD, FAAD',
                'license_number' => 'LIC-DERM-005',
                'consultation_fee' => 125.00,
                'experience_years' => 10,
                'bio' => 'Dr. Maria Garcia specializes in medical and cosmetic dermatology, helping patients achieve healthy skin through personalized treatment plans.',
            ],
        ];

        foreach ($doctorProfiles as $profile) {
            $user = User::where('email', $profile['email'])->first();
            
            if ($user) {
                Doctor::create([
                    'user_id' => $user->id,
                    'specialization' => $profile['specialization'],
                    'qualification' => $profile['qualification'],
                    'license_number' => $profile['license_number'],
                    'consultation_fee' => $profile['consultation_fee'],
                    'experience_years' => $profile['experience_years'],
                    'bio' => $profile['bio'],
                    'is_available' => true,
                ]);
            }
        }
    }
}
