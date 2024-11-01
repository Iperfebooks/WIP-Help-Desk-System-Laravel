<x-guest-layout>
    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form
        method="POST"
        x-bind:action="actionUrl"
        x-data="alpineLoginForm"
    >
        @csrf

        <div class="text-center">
            <div class="mb-3">
                <p
                    x-show="loginAs"
                    class="me-3 text-sm text-center font-medium text-gray-900 dark:text-gray-300"
                >@lang('Login as') <span x-text="loginAs"></span></p>
            </div>

            <label
                class="inline-flex items-center me-5 cursor-pointer"
                x-show="loginOptions && loginOptions?.length"
            >
                <span class="me-3 text-sm font-medium text-gray-900 dark:text-gray-300">@lang('User')</span>
                <input
                    type="checkbox"
                    data-name="loginAs"
                    data-value="admin"
                    x-on:change="toggleLoginAs"
                    class="sr-only peer"
                    x-bind:checked="toLoginIs('admin')"
                >
                <div class="relative w-11 h-6 bg-gray-200 rounded-full peer peer-focus:ring-0 peer-focus:ring-red-300 dark:peer-focus:ring-red-800 dark:bg-gray-700 peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-0.5 after:start-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-600 peer-checked:bg-red-600"></div>
                <span class="ms-3 text-sm font-medium text-gray-900 dark:text-gray-300">@lang('Admin')</span>
            </label>
        </div>

        <div
            x-show="toLoginIs('customer') && !customerCanLoginHere"
            class="mt-8 flex flex-col text-center text-md font-medium text-gray-900 dark:text-gray-300"
        >
            <p>
                Para efetuar login, acesse o portal do HESK.
            </p>
            <p><a
                href="{{ config('app-extra.customers.hesk_customer_login_url') }}"
                target="_blank"
                class="underline text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100 rounded-md focus:outline-none focus:ring-0 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-800"
            >Acessar portal HESK</a>.</p>
        </div>

        <div
            x-show="toLoginIs('admin')"
        >
            <!-- Email Address -->
            <div>
                <x-input-label for="email" :value="__('Email')" />
                <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" />
                <x-input-error :messages="$errors->get('email')" class="mt-2" />
            </div>

            <!-- Password -->
            <div class="mt-4">
                <x-input-label for="password" :value="__('Password')" />

                <x-text-input id="password" class="block mt-1 w-full"
                                type="password"
                                name="password"
                                required autocomplete="current-password" />

                <x-input-error :messages="$errors->get('password')" class="mt-2" />
            </div>

            <!-- Remember Me -->
            <div class="block mt-4">
                <label for="remember_me" class="inline-flex items-center">
                    <input id="remember_me" type="checkbox" class="rounded dark:bg-gray-900 border-gray-300 dark:border-gray-700 text-indigo-600 shadow-sm focus:ring-indigo-500 dark:focus:ring-indigo-600 dark:focus:ring-offset-gray-800" name="remember">
                    <span class="ms-2 text-sm text-gray-600 dark:text-gray-400">{{ __('Remember me') }}</span>
                </label>
            </div>

            <div class="flex items-center justify-end mt-4">
                @if (Route::has('password.request'))
                    <a class="underline text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100 rounded-md focus:outline-none focus:ring-0 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-800" href="{{ route('password.request') }}">
                        {{ __('Forgot your password?') }}
                    </a>
                @endif
            </div>

            <div class="flex items-center justify-end mt-4">
                @if (config('app-extra.customers.can_self_register') && Route::has('register'))
                    <a href="{{ route('register') }}" class="mr-4 font-semibold text-gray-600 hover:text-gray-900 dark:text-gray-400 dark:hover:text-white focus:outline focus:outline-2 focus:rounded-sm focus:outline-red-500">Register</a>
                @endif

                <x-primary-button class="ms-3">
                    {{ __('Log in') }}
                </x-primary-button>
            </div>
        </div>
    </form>

    <script>
        document.addEventListener('alpine:init', () => {
        Alpine.data('alpineLoginForm', () => ({
            loginAs: 'admin',
            customerCanLoginHere: false,
            get loginOptions() {
                return ['admin', 'customer'];
            },
            toLoginIs(value) {
                return this.loginAs === value;
            },
            setLoginAs(value) {
                if (!value || typeof value !== 'string' || !this.loginOptions?.includes(value)) {
                    return;
                }

                this.loginAs = value
            },
            get actionUrl() {
                let actionUrls = {
                    admin: `{{ route('login') }}`,
                    customer: `{{ Route::has('customer_login') ? route('customer_login') : url('customer_login') }}`,
                }

                console.log('this.loginAs', this.loginAs);

                if (!this.loginAs) {
                    return '';
                }

                return (this.loginAs in actionUrls) ? actionUrls[this.loginAs] : (actionUrls['admin'] || '');
            },
            toggleLoginAs() {
                console.log('toggleLoginAs', this.loginAs);
                if (this.loginOptions.length <= 1) {
                    this.setLoginAs(this.loginOptions[0] || null);
                    return;
                }

                let newValue = (this.loginAs === this.loginOptions[0]) ? this.loginOptions[1] : this.loginOptions[0];

                this.setLoginAs(newValue);
            },
        }))
    })
    </script>
</x-guest-layout>
