<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SettingsController extends Controller
{
    public function index()
    {
        if (auth()->user()->role != 'ADMIN') {
            return redirect()
                ->route('hotel-management.index')
                ->with('error', 'Yetkisiz işlem.');
        }

        $settings = Setting::query()
                           ->orderBy('key', 'desc')
                           ->get();
        return view('admin.settings', compact('settings'));
    }

    public function update(Request $request)
    {


        if (auth()->user()->role != 'ADMIN') {
            return redirect()
                ->route('hotel-management.index')
                ->with('error', 'Yetkisiz işlem.');
        }


        foreach ($request->except('_token') as $key => $value) {
            Setting::updateOrCreate(['key' => $key], ['value' => $value]);
        }

        return redirect()
            ->route('settings.index')
            ->with('success', 'Ayarlar güncellendi!');
    }


}
