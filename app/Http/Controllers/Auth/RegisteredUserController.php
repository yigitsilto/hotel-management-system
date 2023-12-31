<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Providers\RouteServiceProvider;
use App\Services\TCService;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{

    private TCService $tcService;

    public function __construct(TCService $tcService)
    {
        $this->tcService = $tcService;
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

        try {
            $check = $this->identityNumberCheck($request);
            if (!$check) {
                return redirect()
                    ->back()
                    ->withInput()
                    ->withErrors(['identity_number' => 'Kimlik bilgisi doğrulanamadı.']);
            }
        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->withInput()
                ->withErrors(['identity_number' => $e->getMessage()]);
        }

        $name = $request->name . ' ' . $request->surname;

        $user = User::create([
                                 'name' => $name,
                                 'birthday' => $request->birthday,
                                 'email' => $request->email,
                                 'password' => Hash::make($request->password),
                                 'identity_number' => $request->identity_number,
                                 'phone_number' => $request->phone_number,
                             ]);

        event(new Registered($user));

        Auth::login($user);

        return redirect(RouteServiceProvider::HOME);
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
