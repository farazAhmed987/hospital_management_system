<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create Admin User
        User::create([
            'name' => 'Admin User',
            'email' => 'admin@careconnect.com',
            'password' => Hash::make('password123'),
            'role' => 'admin',
            'phone' => '+1 (555) 100-0001',
            'address' => '100 Admin Street, Healthcare City, HC 10001',
            'gender' => 'male',
            'date_of_birth' => '1985-01-15',
            'is_active' => true,
            'email_verified_at' => now(),
        ]);

        // Create Doctor Users
        $doctors = [
            [
                'name' => 'Dr. Sarah Johnson',
                'email' => 'sarah.johnson@careconnect.com',
                'phone' => '+1 (555) 200-0001',
                'gender' => 'female',
                'date_of_birth' => '1980-03-20',
            ],
            [
                'name' => 'Dr. Michael Chen',
                'email' => 'michael.chen@careconnect.com',
                'phone' => '+1 (555) 200-0002',
                'gender' => 'male',
                'date_of_birth' => '1975-07-12',
            ],
            [
                'name' => 'Dr. Emily Williams',
                'email' => 'emily.williams@careconnect.com',
                'phone' => '+1 (555) 200-0003',
                'gender' => 'female',
                'date_of_birth' => '1982-11-05',
            ],
            [
                'name' => 'Dr. James Brown',
                'email' => 'james.brown@careconnect.com',
                'phone' => '+1 (555) 200-0004',
                'gender' => 'male',
                'date_of_birth' => '1978-09-28',
            ],
            [
                'name' => 'Dr. Maria Garcia',
                'email' => 'maria.garcia@careconnect.com',
                'phone' => '+1 (555) 200-0005',
                'gender' => 'female',
                'date_of_birth' => '1983-02-14',
            ],
        ];

        foreach ($doctors as $doctor) {
            User::create([
                'name' => $doctor['name'],
                'email' => $doctor['email'],
                'password' => Hash::make('password123'),
                'role' => 'doctor',
                'phone' => $doctor['phone'],
                'address' => '200 Medical Plaza, Healthcare City, HC 10002',
                'gender' => $doctor['gender'],
                'date_of_birth' => $doctor['date_of_birth'],
                'is_active' => true,
                'email_verified_at' => now(),
            ]);
        }

        // Create Patient Users
        $patients = [
            [
                'name' => 'John Smith',
                'email' => 'john.smith@example.com',
                'phone' => '+1 (555) 300-0001',
                'gender' => 'male',
                'date_of_birth' => '1990-05-15',
            ],
            [
                'name' => 'Jane Doe',
                'email' => 'jane.doe@example.com',
                'phone' => '+1 (555) 300-0002',
                'gender' => 'female',
                'date_of_birth' => '1988-08-22',
            ],
            [
                'name' => 'Robert Wilson',
                'email' => 'robert.wilson@example.com',
                'phone' => '+1 (555) 300-0003',
                'gender' => 'male',
                'date_of_birth' => '1995-12-03',
            ],
            [
                'name' => 'Lisa Anderson',
                'email' => 'lisa.anderson@example.com',
                'phone' => '+1 (555) 300-0004',
                'gender' => 'female',
                'date_of_birth' => '1992-04-18',
            ],
            [
                'name' => 'David Martinez',
                'email' => 'david.martinez@example.com',
                'phone' => '+1 (555) 300-0005',
                'gender' => 'male',
                'date_of_birth' => '1987-10-30',
            ],
        ];

        foreach ($patients as $patient) {
            User::create([
                'name' => $patient['name'],
                'email' => $patient['email'],
                'password' => Hash::make('password123'),
                'role' => 'patient',
                'phone' => $patient['phone'],
                'address' => '300 Residential Ave, Healthcare City, HC 10003',
                'gender' => $patient['gender'],
                'date_of_birth' => $patient['date_of_birth'],
                'is_active' => true,
                'email_verified_at' => now(),
            ]);
        }
    }
}
