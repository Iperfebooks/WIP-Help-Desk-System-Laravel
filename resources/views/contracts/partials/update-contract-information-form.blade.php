<section>
    <header>
        <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
            {{ __('Company info') }}
        </h2>
    </header>

    <form id="send-verification" method="post" action="{{ route('verification.send') }}">
        @csrf
    </form>

    <form method="post" action="{{ route('contracts.update', $contract) }}" class="mt-6 space-y-6">
        @csrf
        @method('patch')

        <div>
            <div class="grid gap-6 mb-6 md:grid-cols-2">
                <div>
                    <label for="contract_id" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">@lang('Document type')</label>
                    <select id="contract_id" name="document_type" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                        <option value="" selected>@lang('Choose a option')</option>
                        <option value="">@lang('Empty')</option>
                        @foreach ($documentTypeOptions as $documentTyp)
                            <option
                                value="{{ $documentTyp?->value }}"
                                @selected(old('document_value') == $documentTyp?->value)
                            >{{ $documentTyp?->label }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label for="document_value" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">@lang('Document value')</label>
                    <input
                        type="text"
                        id="document_value"
                        name="document_value" value="{{ old('document_value') }}"
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                        placeholder="{{ __('Document value') }}"
                    />
                </div>
            </div>
        </div>

        <div>
            <x-input-label for="name" :value="__('Name')" />
            <x-text-input id="name" name="name" type="text" class="mt-1 block w-full" :value="old('name', $contract->name)" required autofocus autocomplete="name" />
            <x-input-error class="mt-2" :messages="$errors->get('name')" />
        </div>

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
            >{{ $contract->email ?? null }}</div>
        </div>

        <div class="flex items-center gap-4">
            <x-primary-button>{{ __('Save') }}</x-primary-button>

            @if (session('status') === 'contract-updated')
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
