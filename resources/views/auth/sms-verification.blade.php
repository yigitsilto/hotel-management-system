<x-sms-layout>
    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('sms-verification-check', $phone_number) }}">
        @csrf
        <!-- Email Address -->
        <div>
            @if($user->sms_verified_at == null)
                <h5>Lütfen cep telefonunuza gönderilen güvenlik kodunu giriniz.</h5>
                <hr>
                <br>
                <x-input-label for="code" :value="__('Sms Kodu')" />

                @else
                <x-input-label for="code" :value="__('Şifre')" />
            @endif
            <x-text-input id="code" class="block mt-1 w-full" type="password" name="code" required autofocus />
            <x-input-error :messages="$errors->get('code')" class="mt-2" />
        </div>

        <!-- Remember Me -->
        <div class="flex items-center justify-end mt-4">

        <x-primary-button class="ms-3 btn-block">
            {{ __('Doğrula') }}
        </x-primary-button>

        </div>

    </form>
</x-sms-layout>
