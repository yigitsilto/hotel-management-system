<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\admin\HotelCreateRequest;
use App\Http\Requests\admin\HotelUpdateRequest;
use App\Http\Requests\admin\UserCreateRequest;
use App\Http\Requests\admin\UserUpdateEquest;
use App\Models\Hotel;
use App\Models\Room;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\View\View;

class UserController extends Controller
{
    public function index(): View
    {
        $users = User::query()
                       ->orderBy('updated_at', 'desc')
                       ->orderBy('created_at', 'desc')
                       ->get();

        return view('admin.userManagement.index', compact('users'));
    }

    public function create(): View
    {
        return view('admin.userManagement.create');
    }

    public function store(UserCreateRequest $request)
    {
        $validated = $request->validated();
        $validated['password'] = bcrypt($validated['password']);
        $validated['role'] = 'USER';
        $validated['phone_number'] = str_replace('-', '', $validated['phone_number']);
        unset($validated['password_confirmation']);
        User::query()->create($validated);

        return redirect()->route('user.index')->with('success', 'Kullanıcı başarıyla oluşturuldu.');
    }

    public function edit($userId): View
    {
        $user = User::query()->findOrFail($userId);
        return view('admin.userManagement.edit', compact('user'));
    }

    public function update(UserUpdateEquest $request, User $user)
    {
        $validated = $request->validated();
        $validated['phone_number'] = str_replace('-', '', $validated['phone_number']);
        
        if ($request->email != $user->email){
            return redirect()->back()->withErrors(['email' => 'Email değiştirilemez.']);
        }

        if ($validated['password'] != null){
            $validated['password'] = bcrypt($validated['password']);
            if ($validated['password'] != $validated['password_confirmation']){
                return redirect()->back()->withErrors(['password' => 'Şifreler eşleşmiyor.']);
            }
        }else{
            unset($validated['password']);
        }

        unset($validated['password_confirmation']);

        $user->update($validated);
        $user->can_do_reservation = $validated['can_do_reservation'] == '1';
        $user->save();

        return redirect()->route('user.index')->with('success', 'Kullanıcı başarıyla güncellendi.');
    }


}
