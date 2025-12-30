<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    public function index()
    {
        $settings = Setting::all()->keyBy('key');
        return view('admin.settings.index', compact('settings'));
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'site_title' => 'nullable|string|max:255',
            'site_logo' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg,webp|max:2048',
            'whatsapp_active' => 'nullable|boolean',
            'whatsapp_number' => 'nullable|string|max:255',
            'contact_phone' => 'nullable|string|max:255',
            'contact_address' => 'nullable|string',
            'order_completion_sms_template' => 'nullable|string',
            'incomplete_order_sms_template' => 'nullable|string', // New setting for incomplete orders
            'facebook_pixel_id' => 'nullable|string|max:255', // New setting for Facebook Pixel ID
            'sms_api_url' => 'nullable|url',
            'sms_sender_number' => 'nullable|string|max:20',
        ]);

        $settingsToSave = $request->except('_token', 'site_logo'); // Exclude file from direct saving

        if ($request->hasFile('site_logo')) {
            $path = $request->file('site_logo')->store('logos', 'uploads');
            Setting::updateOrCreate(['key' => 'site_logo'], ['value' => $path]);
        }

        foreach ($settingsToSave as $key => $value) {
            Setting::updateOrCreate(['key' => $key], ['value' => $value]);
        }

        return redirect()->route('admin.settings.index')->with('success', 'Ayarlar başarıyla güncellendi.');
    }
}
