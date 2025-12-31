<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Module;
use Illuminate\Http\Request;

class ModuleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $modules = Module::all();
        return view('admin.modules.index', compact('modules'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Module $module)
    {
        return view('admin.modules.edit', compact('module'));
    }

    /**
     * Update the specified resource in storage.
     */
        public function update(Request $request, Module $module)
        {
            // Handle the AJAX status toggle from the index page
            if ($request->has('is_active')) {
                $request->validate(['is_active' => 'required|boolean']);
                $module->update(['is_active' => $request->is_active]);
                return response()->json(['success' => true, 'message' => 'Modül durumu güncellendi.']);
            }
    
            // --- Handle the settings update from the edit form ---
    
            // First, remove the template row from the input before validation
            $settings = $request->input('settings', []);
            if (isset($settings['accounts']['__INDEX__'])) {
                unset($settings['accounts']['__INDEX__']);
                $request->merge(['settings' => $settings]);
            }
    
            $validatedData = $request->validate([
                'settings' => 'nullable|array'
            ]);
    
            if ($module->key === 'bank_transfer') {
                $request->validate([
                    'settings.accounts.*.bank_name' => 'required|string|max:255',
                    'settings.accounts.*.account_holder' => 'required|string|max:255',
                    'settings.accounts.*.iban' => 'required|string|max:255',
                    'settings.accounts.*.description' => 'nullable|string|max:255',
                ]);
            }
    
            // Filter out any other genuinely empty account groups and re-index the array
            if (isset($validatedData['settings']['accounts'])) {
                $filteredAccounts = array_filter($validatedData['settings']['accounts'], function($account) {
                    return !empty($account['bank_name']) && !empty($account['account_holder']) && !empty($account['iban']);
                });
                // Re-index the array to ensure it's stored as a JSON array
                $validatedData['settings']['accounts'] = array_values($filteredAccounts);
            }
            
            $module->update([
                'settings' => $validatedData['settings']
            ]);
    
            return redirect()->route('admin.modules.index')->with('success', 'Modül ayarları başarıyla güncellendi.');
        }}
