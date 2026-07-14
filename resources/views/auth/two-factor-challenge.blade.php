<x-guest-layout>
    <div class="mb-4 text-sm text-gray-600 dark:text-gray-400">
        {{ __('This is a secure area of the application. Please enter the authentication code from your authenticator app before continuing.') }}
    </div>

    <form method="POST" action="{{ route('two-factor.login') }}">
        @csrf

        <div>
            <x-input-label for="code" value="{{ __('Authentication Code') }}" />
            
            <x-text-input id="code" class="block mt-1 w-full"
                type="text"
                name="code"
                required
                autofocus
                autocomplete="one-time-code" />

            <x-input-error :messages="$errors->get('code')" class="mt-2" />
        </div>

        <div class="flex justify-end mt-4">
            <x-primary-button>
                {{ __('Verify') }}
            </x-primary-button>
        </div>
    </form>
</x-guest-layout>