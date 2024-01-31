<x-guest-layout>
    @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif
    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('login') }}">
        @csrf

        <!-- Email Address -->
        <div>
            <x-input-label for="Telefon Numarası" :value="__('Telefon Numarası (Başında 0 olmadan giriniz)')" />
            <x-text-input id="phone_number" class="block mt-1 w-full" type="number" name="phone_number" :value="old('phone_number')" required autofocus autocomplete="phone_number" />
            <x-input-error :messages="$errors->get('phone_number')" class="mt-2" />
        </div>
        <div class="block mt-4">
            <label for="with_sms" class="inline-flex items-center">
                <input id="with_sms" type="radio" checked class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500" name="with_password">
                <span class="ms-2 text-sm text-gray-600">{{ __('Şifre ile giriş yap') }}</span>
            </label>
        </div>
        <div class="block mt-4">
            <label for="with_sms" class="inline-flex items-center">
                <input id="with_sms" type="radio" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500" name="with_sms">
                <span class="ms-2 text-sm text-gray-600">{{ __('Sms ile giriş yap') }}</span>
            </label>
        </div>



        <!-- Password -->
{{--        <div class="mt-4">--}}
{{--            <x-input-label for="Şifre" :value="__('Password')" />--}}

{{--            <x-text-input id="password" class="block mt-1 w-full"--}}
{{--                            type="password"--}}
{{--                            name="password"--}}
{{--                            required autocomplete="current-password" />--}}

{{--            <x-input-error :messages="$errors->get('password')" class="mt-2" />--}}
{{--        </div>--}}

        <!-- Remember Me -->
        <div class="block mt-4">
            <label for="remember_me" class="inline-flex items-center">
                <input id="remember_me" type="checkbox" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500" name="remember">
                <span class="ms-2 text-sm text-gray-600">{{ __('Beni Hatırla') }}</span>
            </label>
        </div>

        <div class="flex items-center justify-end mt-4">
            @if (Route::has('password.request'))
{{--                <a class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500" href="{{ route('password.request') }}">--}}
{{--                    {{ __('Şifreni mi unuttun ? ') }}--}}
{{--                </a>--}}
            @endif

{{--                <a href="/register" class="btn btn-primary ms-3">--}}
{{--                    {{ __('Kayıt Ol') }}--}}
{{--                </a>--}}

                <x-primary-button class="ms-3">
                    {{ __('Giriş Yap') }}
                </x-primary-button>
        </div>
    </form>
</x-guest-layout>
