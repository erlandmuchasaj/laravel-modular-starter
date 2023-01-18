<x-core::guest-layout>
    <x-user::auth-card>
        <x-slot name="logo">
            <a href="/">
                <x-core::application-logo class="w-20 h-20 fill-current text-gray-500" />
            </a>
        </x-slot>

        <div class="mb-4 text-sm text-gray-600">
            {{ __('Did you lose your phone or leave your account logged in at a public computer? You can log out everywhere else, and stay logged in here.') }}
        </div>

        <!-- Validation Errors -->
        <x-user::auth-validation-errors class="mb-4" :errors="$errors" />

        <form method="POST" action="{{ route('password.destroy') }}">
            @csrf
            @method('DELETE')
            <x-honeypot />

            <!-- Password -->
            <div>
                <x-core::label for="password" :value="__('Password')" />

                <x-core::input id="password" class="block mt-1 w-full"
                               type="password"
                               name="password"
                               autocomplete="current-password"
                               required=""/>
            </div>

            <div class="flex justify-end mt-4">

                <a href="/" class="inline-flex items-center px-4 py-2 mx-2 underline font-semibold text-xs text-dark uppercase">
                    {{ __('Cancel') }}
                </a>

                <x-core::button>
                    {{ __('Logout everywhere else') }}
                </x-core::button>
            </div>
        </form>
    </x-user::auth-card>
</x-core::guest-layout>
