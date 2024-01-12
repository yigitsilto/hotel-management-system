<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\admin\UserCreateRequest;
use App\Http\Requests\admin\UserUpdateEquest;
use App\Models\Setting;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SettingsController extends Controller
{
    public function index(): View
    {
        $settings = Setting::query()->orderBy('key', 'desc')->get();
        return view('admin.settings', compact('settings'));
    }

    public function update(Request $request)
    {
        foreach ($request->except('_token') as $key => $value) {
            Setting::updateOrCreate(['key' => $key], ['value' => $value]);
        }

        return redirect()->route('settings.index')->with('success', 'Ayarlar güncellendi!');
    }




}
