<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Models\User;
use App\Providers\RouteServiceProvider;
use App\Services\SmsService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Validation\ValidationException;
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
    public function store(LoginRequest $request): RedirectResponse
    {
        $user = User::where('phone_number', $request->phone_number)
                    ->first();



        if (!$user) {
            return redirect()
                ->route('login')
                ->withErrors(['email' => 'Telefon numarası veya şifre yanlış']);
        }

        if (!$user->can_do_reservation) {
            return redirect()
                ->route('login')
                ->withErrors(['phone_number' => 'Rezervasyon yapmak için lütfen iletişime geçiniz.']);
        }


        if ($user->sms_verified_at == null) {
            $this->smsService->sendVerificationSms($user);
            return redirect()
                ->route('sms-verification', ['phone_number' => $user->phone_number]);
        }

        $request->authenticate();

        $request->session()
                ->regenerate();

        return redirect()->intended(RouteServiceProvider::HOME);
    }



    public function smsVerification($phone_number): View
    {
        return view('auth.sms-verification', compact('phone_number'));
    }

    public function smsVerificationCheck(Request $request, string $phone_number)
    {

//        $request->validate([
//                               'code' => 'required|exists:sms_verifications,code',
//                           ]);

        $user = User::query()
                    ->where('phone_number', $phone_number)
                    ->first();

        if (!$user) {
            return redirect()
                ->route('login')
                ->withErrors(['phone_number' => 'İşleminize şuan devam edemiyoruz. Lütfen tekrar deneyiniz.']);
        }
        $smsVerification = \App\Models\SmsVerification::query()
                                                      ->where('code', $request->code)
                                                      ->where('user_id', $user->id)
                                                      ->orderBy('id', 'desc')
                                                      ->first();

//        if ($smsVerification->expires_at < now()) {
//
//            $this->smsService->sendVerificationSms($user);
//            return redirect()
//                ->route('sms-verification', ['phone_number' => $user->phone_number])
//                ->with(['email' => $user->phone_number])
//                ->withErrors(['code' => 'Girdiğiniz kodun süresi doldu. Tekrar Gönderildi']);
//        }


        $user->sms_verified_at = now();
//        $user->password = Hash::make($smsVerification->code);
        $user->password = Hash::make("123123123");
        $user->save();

        $smsVerification->delete();

        return redirect()
            ->route('login')
            ->with('success', 'Sms doğrulaması başarılı. Lütfen giriş yapınız');

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
