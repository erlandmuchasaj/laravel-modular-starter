<x-core::guest-layout>
    <x-user::auth-card>
        <x-slot name="logo">
            <a href="/">
                <x-core::application-logo class="w-20 h-20 fill-current text-gray-500" />
            </a>
        </x-slot>

        <div class="mb-4 text-sm text-gray-600">
            <p>{!! __("Doing this will <strong>permanently delete all of your data</strong>.") !!}</p>
            <p>{{ __("Once your account is deleted, you can’t reactivate it, recover any data, or regain access.") }}</p>
            <p>{!! __("You'll need to set up a new account if you want to use <strong>:app_name</strong> again.", ['app_name' => config
                ('app.name', 'Laravel')]) !!}</p>
        </div>

        <!-- Validation Errors -->
        <x-user::auth-validation-errors class="mb-4" :errors="$errors" />

        <form method="POST" action="{{ route('deactivate.account') }}">
            @csrf
            @method('DELETE')
            <x-honeypot />

            <!-- Password -->
            <div>
                <x-core::label for="password" :value="__('Password')" />

                <x-core::input id="password" class="block mt-1 w-full"
                               type="password"
                               name="password"
                               placeholder="{{ __('Type your current password') }}"
                               required autocomplete="current-password" />
            </div>

            <div class="flex justify-end mt-4">

                <button type="submit" value="submit" class="inline-flex items-center px-4 py-2 mr-5 underline text-xs text-dark uppercase">
                    {{ __('Delete my account forever') }}
                </button>


                <a href="/" class="inline-flex items-center px-4 py-2 ml-5 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest">
                    {{ __('Cancel') }}
                </a>
            </div>
        </form>
    </x-user::auth-card>
</x-core::guest-layout>
