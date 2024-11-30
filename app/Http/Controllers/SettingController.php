<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Setting;
class SettingController extends Controller
{
    // Show the edit form for the setting
    public function edit()
    {
        // Fetch the setting based on the 'key' (reservation_expiration_time)
        $setting = Setting::where('key', 'reservation_expiration_time')->first();

        // Pass the setting data to the view
        return view('admin.settings.edit', compact('setting'));
    }

    // Update the existing setting
    public function update(Request $request)
    {
        // Validate the request data
        $request->validate([
            'value' => 'required|integer|min:1', // Validate value as an integer
            'description' => 'nullable|string',  // Validate description as optional string
        ]);

        // Fetch the setting or create a new one if it doesn't exist
        $setting = Setting::updateOrCreate(
            ['key' => 'reservation_expiration_time'], // Find the setting by key
            [
                'value' => $request->value,  // Update the value
                'description' => $request->description ?? null,  // Optional description
            ]
        );

        // Redirect back to the edit page with success message
        return redirect()->route('admin.settings.edit')->with('success', 'Setting updated successfully!');
    }

    // Store a new setting
    public function store(Request $request)
    {
        // Validate the request data
        $validated = $request->validate([
            'reservation_expiration_time' => 'required|integer', // Validate value as an integer
            'description' => 'nullable|string', // Optional description
        ]);

        // Create a new setting with the validated data
        $setting = Setting::create($validated);

        // Return a success response with the created setting data
        return response()->json([
            'success' => true,
            'message' => 'Setting added successfully!',
            'data' => $setting,
        ], 201); // HTTP 201 Created
    }

}
