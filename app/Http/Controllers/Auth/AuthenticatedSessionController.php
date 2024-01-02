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

        $user = User::where('email', $request->email)
                    ->first();

        if (!$user) {
            return redirect()
                ->route('login')
                ->withErrors(['email' => 'Kullanıcı adı veya şifre yanlış']);
        }

        if ($user && $user->sms_verified_at == null) {
            $this->smsService->sendVerificationSms($user);
            return redirect()
                ->route('sms-verification', ['email' => $user->email]);
        }

        $request->authenticate();

        $request->session()
                ->regenerate();

        return redirect()->intended(RouteServiceProvider::HOME);
    }

    public function smsVerification($email): View
    {
        return view('auth.sms-verification', compact('email'));
    }

    public function smsVerificationCheck(Request $request, string $email)
    {

        $request->validate([
                               'code' => 'required|exists:sms_verifications,code',
                           ]);

        $user = User::query()
                    ->where('email', $email)
                    ->first();

        if (!$user) {
            return redirect()
                ->route('login')
                ->withErrors(['email' => 'İşleminize şuan devam edemiyoruz. Lütfen tekrar deneyiniz.']);
        }

        $smsVerification = \App\Models\SmsVerification::query()
                                                      ->where('code', $request->code)
                                                      ->where('user_id', $user->id)
                                                      ->orderBy('id', 'desc')
                                                      ->first();

        if ($smsVerification->expires_at < now()) {

            $this->smsService->sendVerificationSms($user);
            return redirect()
                ->route('sms-verification', ['email' => $user->email])
                ->with(['email' => $user->email])
                ->withErrors(['code' => 'Girdiğiniz kodun süresi doldu. Tekrar Gönderildi']);
        }


        $user->sms_verified_at = now();
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
