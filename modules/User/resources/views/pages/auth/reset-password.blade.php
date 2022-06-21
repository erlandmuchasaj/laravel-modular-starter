<x-core::guest-layout>
    <x-user::auth-card>
        <x-slot name="logo">
            <a href="/">
                <x-core::application-logo class="w-20 h-20 fill-current text-gray-500" />
            </a>
        </x-slot>

        <!-- Validation Errors -->
        <x-user::auth-validation-errors class="mb-4" :errors="$errors" />

        <form method="POST" action="{{ route('password.update') }}">
            @csrf

            <!-- Password Reset Token -->
            <input type="hidden" name="token" value="{{ $request->route('token') }}">

            <!-- Email Address -->
            <div>
                <x-core::label for="email" :value="__('Email')" />

                <x-core::input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email', $request->email)" required autofocus />
            </div>

            <!-- Password -->
            <div class="mt-4">
                <x-core::label for="password" :value="__('Password')" />

                <x-core::input id="password" class="block mt-1 w-full" type="password" name="password" required />
            </div>

            <!-- Confirm Password -->
            <div class="mt-4">
                <x-core::label for="password_confirmation" :value="__('Confirm Password')" />

                <x-core::input id="password_confirmation" class="block mt-1 w-full"
                                    type="password"
                                    name="password_confirmation" required />
            </div>

            <div class="flex items-center justify-end mt-4">
                <x-core::button>
                    {{ __('Reset Password') }}
                </x-core::button>
            </div>
        </form>
    </x-user::auth-card>
</x-core::guest-layout>
