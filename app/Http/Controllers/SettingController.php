<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Setting;
class SettingController extends Controller
{
    public function edit()
    {
        $setting = Setting::where('key', 'reservation_expiration_time')->first();
        return view('admin.settings.edit', compact('setting'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'value' => 'required|integer|min:1',
        ]);

        $setting = Setting::updateOrCreate(
            ['key' => 'reservation_expiration_time'],
            ['value' => $request->value]
        );

        return redirect()->route('admin.settings.edit')->with('success', 'Setting updated successfully!');
    }
}
