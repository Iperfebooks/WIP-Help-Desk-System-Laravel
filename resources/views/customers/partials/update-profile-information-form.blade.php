<section>
    <header>
        <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
            {{ __('Customer Information') }}
        </h2>

        <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
            {{ __("Update your account's customer information and email address.") }}
        </p>
    </header>

    <form id="send-verification" method="post" action="{{ route('verification.send') }}">
        @csrf
    </form>

    <form method="post" action="{{ route('customers.users.update', $customer) }}" class="mt-6 space-y-6">
        @csrf
        @method('patch')

        <div>
            <label for="contract_id" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">@lang('Company')</label>

            <select id="contract_id" name="contract_id" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                <option value="">@lang('Choose a company')</option>
                <option
                    value=""
                    @selected(!$customer?->contract_id)
                >@lang('Empty') - (@lang('No company'))</option>
                @foreach ($contracts as $contract)
                    <option
                        value="{{ $contract?->id }}"
                        @selected($customer?->contract_id == $contract?->id)
                    >{{ $contract?->label }} @selected($customer?->contract_id == $contract?->id)</option>
                @endforeach
            </select>
        </div>

        <div>
            <x-input-label for="name" :value="__('Name')" />
            <x-text-input id="name" name="name" type="text" class="mt-1 block w-full" :value="old('name', $customer->name)" required autofocus autocomplete="name" />
            <x-input-error class="mt-2" :messages="$errors->get('name')" />
        </div>

        @if (false)
        <div>
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" name="email" type="email" class="mt-1 block w-full" :value="old('email', $customer->email)" required autocomplete="username" />
            <x-input-error class="mt-2" :messages="$errors->get('email')" />

            @if ($customer instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $customer->hasVerifiedEmail())
                <div>
                    <p class="text-sm mt-2 text-gray-800 dark:text-gray-200">
                        {{ __('Your email address is unverified.') }}

                        <button form="send-verification" class="underline text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100 rounded-md focus:outline-none focus:ring-0 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-800">
                            {{ __('Click here to re-send the verification email.') }}
                        </button>
                    </p>

                    @if (session('status') === 'verification-link-sent')
                        <p class="mt-2 font-medium text-sm text-green-600 dark:text-green-400">
                            {{ __('A new verification link has been sent to your email address.') }}
                        </p>
                    @endif
                </div>
            @endif
        </div>
        @else
        <div>
            <x-input-label :value="__('Email')" />
            <div
                @class([
                    'border',
                    'bg-gray-400/50',
                    'border-gray-500',
                    'dark:border-gray-600',
                    'dark:bg-gray-600/50',
                    'dark:text-gray-300',
                    'focus:border-indigo-500',
                    'dark:focus:border-indigo-600',
                    'focus:ring-indigo-500',
                    'dark:focus:ring-indigo-600',
                    'rounded-md',
                    'shadow-sm',
                    'mt-1',
                    'block',
                    'w-full',
                    'px-3',
                    'py-2',
                ])
            >{{ $customer->email ?? null }}</div>
        </div>
        @endif

        <div class="flex items-center gap-4">
            <x-primary-button>{{ __('Save') }}</x-primary-button>

            @if (session('status') === 'customer-updated')
                <p
                    x-data="{ show: true }"
                    x-show="show"
                    x-transition
                    x-init="setTimeout(() => show = false, 2000)"
                    class="text-sm text-gray-600 dark:text-gray-400"
                >{{ __('Saved.') }}</p>
            @endif
        </div>
    </form>
</section>
