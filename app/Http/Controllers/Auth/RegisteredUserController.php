<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\SmsService;
use App\Services\TCService;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{

    private TCService $tcService;
    private SmsService $smsService;

    public function __construct(TCService $tcService, SmsService $smsService)
    {
        $this->tcService = $tcService;
        $this->smsService = $smsService;
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
                               'name' => [
                                   'required',
                                   'string',
                                   'max:255'
                               ],
                               'surname' => [
                                   'required',
                                   'string',
                                   'max:255'
                               ],
                               'birthday' => [
                                   'required',
                                   'date',
                               ],
                               'email' => [
                                   'required',
                                   'string',
                                   'lowercase',
                                   'email',
                                   'max:255',
                                   'unique:' . User::class
                               ],
                               'password' => [
                                   'required',
                                   'confirmed',
                                   Rules\Password::defaults()
                               ],
                               'identity_number' => 'required|numeric|digits:11|unique:' . User::class,
                               'phone_number' => 'required|regex:/^\d{3}-\d{3}-\d{4}$/',
                           ]);
        DB::beginTransaction();
        try {
            $check = $this->identityNumberCheck($request);
            if (!$check) {
                return redirect()
                    ->back()
                    ->withInput()
                    ->withErrors(['identity_number' => 'Kimlik bilgisi doğrulanamadı.']);
            }

            $name = $request->name . ' ' . $request->surname;
            $phone_number = str_replace('-', '', $request->phone_number);

            $user = User::query()
                        ->create([
                                     'name' => $name,
                                     'birthday' => $request->birthday,
                                     'email' => $request->email,
                                     'password' => Hash::make($request->password),
                                     'identity_number' => $request->identity_number,
                                     'phone_number' => $phone_number,
                                 ]);

            $this->smsService->sendVerificationSms($user);
            DB::commit();
            event(new Registered($user));

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()
                ->back()
                ->withInput()
                ->withErrors(['identity_number' => $e->getMessage()]);
        }


        //Auth::login($user);

        return redirect()
            ->route('sms-verification', ['email' => $user->email]);
    }

    /**
     * @throws \Exception
     */
    private function identityNumberCheck(Request $request)
    {


        $birthYear = date('Y', strtotime($request->birthday));


        return $this->tcService->sorgula($request->name, $request->surname, $birthYear,
                                         $request->identity_number);


    }

    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register');
    }
}
