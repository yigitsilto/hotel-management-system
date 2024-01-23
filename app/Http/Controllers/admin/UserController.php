<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\admin\UserCreateRequest;
use App\Http\Requests\admin\UserUpdateEquest;
use App\Imports\UsersImport;
use App\Models\AuthorizedHotel;
use App\Models\FailedRow;
use App\Models\Hotel;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;
use Maatwebsite\Excel\Facades\Excel;

class UserController extends Controller
{
    public function importFile()
    {

        if (auth()->user()->role != 'ADMIN') {
            return redirect()
                ->route('hotel-management.index')
                ->with('error', 'Yetkisiz işlem.');
        }

        $failedRows = \App\Models\FailedRow::query()
                                           ->get();
        return view('admin.userManagement.import', compact('failedRows'));
    }

    public function index(): View
    {
        $users = User::query()
            ->where('role', '!=', 'ADMIN')
                     ->orderBy('updated_at', 'desc')
                     ->orderBy('created_at', 'desc')
                     ->get();

        return view('admin.userManagement.index', compact('users'));
    }

    public function store(UserCreateRequest $request)
    {
        DB::beginTransaction();
        try {

            $validated = $request->validated();
            $authorizedHotels = $validated['authorized_hotels'] ?? null;
            unset($validated['authorized_hotels']);
            $validated['password'] = bcrypt($validated['password']);

            if (auth()->user()->role != 'ADMIN') {
                $validated['role'] = 'USER';
            }

            if ($validated['role'] == 'WORKER') {
                if ($authorizedHotels == null) {
                    return redirect()
                        ->back()
                        ->with('error', 'Resepsiyonist kullanıcı için en az bir otel seçilmelidir.')
                        ->withInput();
                }
            }
            $validated['phone_number'] = str_replace('-', '', $validated['phone_number']);


            $existsPhone = User::query()
                               ->where('phone_number', $validated['phone_number'])
                               ->count();


            if ($existsPhone > 0) {
                return redirect()
                    ->back()
                    ->with('error', 'Bu telefon numarası ile kayıtlı kullanıcı bulunmaktadır.')
                    ->withInput();
            }


            unset($validated['password_confirmation']);
            $user = User::query()
                        ->create($validated);


            if ($authorizedHotels != null) {
                foreach ($authorizedHotels as $hotelId) {
                    $hotel = Hotel::query()
                                  ->findOrFail($hotelId);
                    AuthorizedHotel::query()
                                   ->updateOrCreate([
                                                        'user_id' => $user->id,
                                                        'hotel_id' => $hotel->id,
                                                    ]);
                }
            }

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()
                ->back()
                ->with('error', 'Bir hata meydana geldi.');
        }


        return redirect()
            ->route('user.index')
            ->with('success', 'Kullanıcı başarıyla oluşturuldu.');
    }

    public function create(): View
    {
        return view('admin.userManagement.create');
    }

    public function edit($userId): View
    {
        $user = User::query()
                    ->findOrFail($userId);
        return view('admin.userManagement.edit', compact('user'));
    }

    public function update(UserUpdateEquest $request, User $user)
    {
        $validated = $request->validated();
        $validated['phone_number'] = str_replace('-', '', $validated['phone_number']);

        $authorizedHotels = $validated['authorized_hotels'] ?? null;
        unset($validated['authorized_hotels']);

        if ($request->email != $user->email) {
            return redirect()
                ->back()
                ->withErrors(['email' => 'Email değiştirilemez.']);
        }

        if ($validated['password'] != null) {
            $validated['password'] = bcrypt($validated['password']);
            if ($validated['password'] != $validated['password_confirmation']) {
                return redirect()
                    ->back()
                    ->withErrors(['password' => 'Şifreler eşleşmiyor.']);
            }
        } else {
            unset($validated['password']);
        }

        unset($validated['password_confirmation']);



       if (isset($validated['role'])){
           if ($validated['role'] == 'WORKER') {
               if ($authorizedHotels == null) {
                   return redirect()
                       ->back()
                       ->with('error', 'Resepsiyonist kullanıcı için en az bir otel seçilmelidir.')
                       ->withInput();
               }
           }

       }
        // phone number cpontrol
        $existsPhone = User::query()
                           ->where('phone_number', $validated['phone_number'])
                           ->where('id', '!=', $user->id)
                           ->count();

        if ($existsPhone > 0) {
            return redirect()
                ->back()
                ->with('error', 'Bu telefon numarası ile kayıtlı kullanıcı bulunmaktadır.')
                ->withInput();
        }


        $user->update($validated);
        $user->can_do_reservation = $validated['can_do_reservation'] == '1';
        $user->save();

        AuthorizedHotel::query()
                       ->where('user_id', $user->id)
                       ->delete();


        if ($authorizedHotels != null) {
            foreach ($authorizedHotels as $hotelId) {
                $hotel = Hotel::query()
                              ->findOrFail($hotelId);
                AuthorizedHotel::query()
                               ->updateOrCreate([
                                                    'user_id' => $user->id,
                                                    'hotel_id' => $hotel->id,
                                                ]);
            }
        }

        return redirect()
            ->route('user.index')
            ->with('success', 'Kullanıcı başarıyla güncellendi.');
    }


    public function exampleDownload()
    {
        return response()->download(public_path('example-file-user.xlsx'));
    }

    public function importDownload(Request $request)
    {

        if (auth()->user()->role != 'ADMIN') {
            return redirect()
                ->route('hotel-management.index')
                ->with('error', 'Yetkisiz işlem.');
        }


        $request->validate([
                               'file' => 'required|file|mimes:xlsx,xls,csv'
                           ]);

        FailedRow::query()
                 ->truncate();

        Excel::import(new UsersImport(), request()->file('file'));

        return redirect()
            ->back()
            ->with('success', 'Kullanıcılar ekleniyor. Takip etmek için kullanıcı listesine gidiniz.');

//        $path = $request->file('file')->getRealPath();
//        $data = \Excel::import(new \App\Imports\UsersImport, $path);
//        return redirect()->route('user.index')->with('success', 'Kullanıcılar başarıyla eklendi.');
    }

}
