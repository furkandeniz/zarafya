<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    public function index()
    {
        $commissionRate = Setting::get('commission_rate', '15');

        return view('admin.pages.settings', compact('commissionRate'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'commission_rate' => ['required', 'numeric', 'min:0', 'max:100', 'regex:/^\d+(\.\d{1,2})?$/'],
        ], [
            'commission_rate.required' => 'Komisyon oranı zorunludur.',
            'commission_rate.numeric'  => 'Komisyon oranı sayısal olmalıdır.',
            'commission_rate.min'      => 'Komisyon oranı 0\'dan küçük olamaz.',
            'commission_rate.max'      => 'Komisyon oranı 100\'den büyük olamaz.',
        ]);

        $value = $request->filled('commission_rate') ? $request->commission_rate : \App\Models\Setting::DEFAULTS['commission_rate'];
        Setting::set('commission_rate', $value);

        return back()->with('success', 'Komisyon oranı %' . $request->commission_rate . ' olarak güncellendi.');
    }
}
