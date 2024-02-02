<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Jobs\SendSmsJob;
use App\Models\SmsVerification;
use App\Models\User;
use App\Providers\RouteServiceProvider;
use App\Services\SmsService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Bus;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    private SmsService $smsService;

    public function __construct(SmsService $smsService)
    {
        $this->smsService = $smsService;
    }

    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request)
    {



        $authMethod = $request->validated()['authentication_method'];

        $user = User::where('phone_number', $request->phone_number)
                    ->first();


        if (!$user) {
            return redirect()
                ->route('login')
                ->withErrors(['phone_number' => 'Telefon numarası veya şifre yanlış']);
        }


        if ($authMethod == 'with_sms') {

            if ($user->role != 'ADMIN'){
                User::where('phone_number', $request->phone_number)
                    ->update(['sms_verified_at' => null]);
            }

            $user = User::where('phone_number', $request->phone_number)
                ->first();
        }



        if (!$user->can_do_reservation) {
            return redirect()
                ->route('login')
                ->withErrors(['phone_number' => 'Rezervasyon yapmak için lütfen iletişime geçiniz.']);
        }

        $phone_number = $user->phone_number;

        if ($user->sms_verified_at == null) {
            SendSmsJob::dispatch($this->smsService, $user);
        }

        return view('auth.sms-verification', compact('phone_number', 'user'));

    }


    public function smsVerification($phone_number): View
    {
        return view('auth.sms-verification', compact('phone_number'));
    }

    public function smsVerificationCheck(Request $request, string $phone_number)
    {

        $user = User::query()
                    ->where('phone_number', $phone_number)
                    ->first();

        if (!$user) {
            return redirect()
                ->route('login')
                ->withErrors(['phone_number' => 'İşleminize şuan devam edemiyoruz. Lütfen tekrar deneyiniz.']);
        }

        if ($user->sms_verified_at == null) {

            $smsVerification = \App\Models\SmsVerification::query()
                                                          ->where('code', $request->code)
                                                          ->where('user_id', $user->id)
                                                          ->orderBy('id', 'desc')
                                                          ->first();


            if (empty($smsVerification)){
                return redirect()
                    ->back()
                    ->withErrors(['code' => 'Kod yanlış veya süresi dolmuş tekrar gönderildi'])->withInput();
            }


            $user->sms_verified_at = now();
            $user->password = Hash::make($smsVerification->code);
            $user->save();

            if (!Auth::attempt([
                                   'phone_number' => $phone_number,
                                   'password' => $smsVerification->code
                               ], true
            )) {

                return redirect()
                    ->back()
                    ->withErrors(['code' => 'Kod yanlış']);
            }


            $smsVerification->delete();

            return redirect()->intended(RouteServiceProvider::HOME);


        } else {

            if (!Auth::attempt([
                                   'phone_number' => $phone_number,
                                   'password' => $request->code
                               ], true
            )) {
                return redirect()
                    ->back()
                    ->withErrors(['phone_number' => 'Telefon numarası veya şifre yanlış']);
            }


//        $smsVerification->delete();

            return redirect()->intended(RouteServiceProvider::HOME);

        }


//        return redirect()
//            ->route('login')
//            ->with('success', 'Sms doğrulaması başarılı. Lütfen giriş yapınız');

    }


    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')
            ->logout();

        $request->session()
                ->invalidate();

        $request->session()
                ->regenerateToken();

        return redirect('/');
    }


}
