<?php

namespace Database\Seeders;

use App\Models\Patient;
use App\Models\User;
use Illuminate\Database\Seeder;

class PatientSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $patientProfiles = [
            [
                'email' => 'john.smith@example.com',
                'blood_type' => 'O+',
                'height' => 180,
                'weight' => 82,
                'allergies' => 'Penicillin',
                'medical_history' => 'Appendectomy (2015), Mild hypertension',
                'emergency_contact_name' => 'Mary Smith',
                'emergency_contact_phone' => '+1 (555) 400-0001',
                'emergency_contact_relationship' => 'Spouse',
                'insurance_provider' => 'Blue Cross Blue Shield',
                'insurance_number' => 'BCBS-123456789',
            ],
            [
                'email' => 'jane.doe@example.com',
                'blood_type' => 'A-',
                'height' => 165,
                'weight' => 58,
                'allergies' => 'None known',
                'medical_history' => 'Asthma (mild)',
                'emergency_contact_name' => 'Tom Doe',
                'emergency_contact_phone' => '+1 (555) 400-0002',
                'emergency_contact_relationship' => 'Parent',
                'insurance_provider' => 'Aetna',
                'insurance_number' => 'AET-987654321',
            ],
            [
                'email' => 'robert.wilson@example.com',
                'blood_type' => 'B+',
                'height' => 175,
                'weight' => 78,
                'allergies' => 'Latex, Shellfish',
                'medical_history' => 'No significant medical history',
                'emergency_contact_name' => 'Susan Wilson',
                'emergency_contact_phone' => '+1 (555) 400-0003',
                'emergency_contact_relationship' => 'Sibling',
                'insurance_provider' => 'United Healthcare',
                'insurance_number' => 'UHC-456789123',
            ],
            [
                'email' => 'lisa.anderson@example.com',
                'blood_type' => 'AB+',
                'height' => 168,
                'weight' => 62,
                'allergies' => 'Aspirin',
                'medical_history' => 'Type 2 Diabetes (controlled with medication)',
                'emergency_contact_name' => 'Mike Anderson',
                'emergency_contact_phone' => '+1 (555) 400-0004',
                'emergency_contact_relationship' => 'Spouse',
                'insurance_provider' => 'Cigna',
                'insurance_number' => 'CIG-741852963',
            ],
            [
                'email' => 'david.martinez@example.com',
                'blood_type' => 'O-',
                'height' => 185,
                'weight' => 90,
                'allergies' => 'Sulfa drugs',
                'medical_history' => 'Knee surgery (2019), High cholesterol',
                'emergency_contact_name' => 'Carmen Martinez',
                'emergency_contact_phone' => '+1 (555) 400-0005',
                'emergency_contact_relationship' => 'Parent',
                'insurance_provider' => 'Humana',
                'insurance_number' => 'HUM-369258147',
            ],
        ];

        foreach ($patientProfiles as $profile) {
            $user = User::where('email', $profile['email'])->first();
            
            if ($user) {
                Patient::create([
                    'user_id' => $user->id,
                    'blood_type' => $profile['blood_type'],
                    'height' => $profile['height'],
                    'weight' => $profile['weight'],
                    'allergies' => $profile['allergies'],
                    'medical_history' => $profile['medical_history'],
                    'emergency_contact_name' => $profile['emergency_contact_name'],
                    'emergency_contact_phone' => $profile['emergency_contact_phone'],
                    'emergency_contact_relationship' => $profile['emergency_contact_relationship'],
                    'insurance_provider' => $profile['insurance_provider'],
                    'insurance_number' => $profile['insurance_number'],
                ]);
            }
        }
    }
}
