<?php

namespace App\Http\Controllers\Patient;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;

class ProfileController extends Controller
{
    /**
     * Show the patient profile.
     */
    public function show(Request $request)
    {
        $user = $request->user();
        $patient = $user->patient;

        $stats = [];
        if ($patient) {
            $stats = [
                'total_appointments' => $patient->appointments()->count(),
                'completed' => $patient->appointments()->where('status', 'completed')->count(),
                'upcoming' => $patient->appointments()->whereIn('status', ['pending', 'approved'])->where('appointment_date', '>=', now()->toDateString())->count(),
                'cancelled' => $patient->appointments()->where('status', 'cancelled')->count(),
            ];
        }

        return view('patient.profile.show', compact('user', 'patient', 'stats'));
    }

    /**
     * Show the form for editing the profile.
     */
    public function edit(Request $request)
    {
        $user = $request->user();
        $patient = $user->patient;

        return view('patient.profile.edit', compact('user', 'patient'));
    }

    /**
     * Update the profile.
     */
    public function update(Request $request)
    {
        $user = $request->user();
        $patient = $user->patient;

        $validated = $request->validate([
            // User fields
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email,' . $user->id],
            'phone' => ['nullable', 'string', 'max:20'],
            'date_of_birth' => ['nullable', 'date', 'before:today'],
            'gender' => ['nullable', 'in:male,female,other'],
            'address' => ['nullable', 'string', 'max:500'],
            // Patient fields
            'blood_group' => ['nullable', 'string', 'max:5'],
            'allergies' => ['nullable', 'string', 'max:500'],
            'medical_history' => ['nullable', 'string', 'max:1000'],
            'current_medications' => ['nullable', 'string', 'max:500'],
            'emergency_contact_name' => ['nullable', 'string', 'max:255'],
            'emergency_contact_phone' => ['nullable', 'string', 'max:20'],
            'insurance_provider' => ['nullable', 'string', 'max:255'],
            'insurance_policy_number' => ['nullable', 'string', 'max:100'],
        ]);

        // Update user
        $user->update([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'] ?? null,
            'date_of_birth' => $validated['date_of_birth'] ?? null,
            'gender' => $validated['gender'] ?? null,
            'address' => $validated['address'] ?? null,
        ]);

        // Update patient profile
        if ($patient) {
            $patient->update([
                'blood_group' => $validated['blood_group'] ?? null,
                'allergies' => $validated['allergies'] ?? null,
                'medical_history' => $validated['medical_history'] ?? null,
                'current_medications' => $validated['current_medications'] ?? null,
                'emergency_contact_name' => $validated['emergency_contact_name'] ?? null,
                'emergency_contact_phone' => $validated['emergency_contact_phone'] ?? null,
                'insurance_provider' => $validated['insurance_provider'] ?? null,
                'insurance_policy_number' => $validated['insurance_policy_number'] ?? null,
            ]);
        }

        return redirect()->route('patient.profile.show')
            ->with('success', 'Profile updated successfully.');
    }

    /**
     * Show the form for changing password.
     */
    public function editPassword()
    {
        return view('patient.profile.change-password');
    }

    /**
     * Update the password.
     */
    public function updatePassword(Request $request)
    {
        $validated = $request->validate([
            'current_password' => ['required', 'current_password'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        $request->user()->update([
            'password' => Hash::make($validated['password']),
        ]);

        return redirect()->route('patient.profile.show')
            ->with('success', 'Password changed successfully.');
    }
}
