@php
$generateRandomPass = !true;
@endphp

<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('New company') }}
        </h2>
    </x-slot>

    <div
        class="mx-2 py-6 md:mx-auto"
        x-data="{
            generateRandomPassword: @js($generateRandomPass),
            toggleGenerateRandomPassword() {
                this.generateRandomPassword = !this.generateRandomPassword;
            },
            onGenerateRandomPasswordToggleChange(event) {
                {{-- this.toggleGenerateRandomPassword(); --}}
            },
        }"
    >
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <form method="post" action="{{ route('contracts.store') }}" class="mt-6 space-y-6">
                @csrf

                <div class="grid gap-6 mb-6 md:grid-cols-3">
                    <div>
                        <label for="name" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">@lang('Name')</label>
                        <input type="text" id="name" name="name" value="{{ old('name') }}" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="{{ __('Name') }}" required />
                    </div>

                    <div>
                        <label for="email" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">@lang('Email address')</label>
                        <input type="email" id="email" name="email" value="{{ old('email') }}" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="john.doe@company.com" />
                    </div>
                </div>

                <div class="grid gap-6 mb-6 md:grid-cols-3">
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

                @if (false)
                <div class="w-full mb-6">
                    <h4 for="email" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">@lang('Resources')</h4>

                    <div class="border border-gray-700/50 dark:border-gray-700 pt-2 pb-0 px-2 rounded-lg">
                        <div class="grid gap-4 mx-2 md:grid-cols-2">
                            @foreach ([
                                [
                                    'name' => 'can_open_tickets',
                                    'value' => old('can_open_tickets') ?? 1,
                                    'label' => __('Create ticket')
                                ],
                                // [
                                //     'name' => 'can_open_tickets',
                                //     'value' => 1,
                                //     'label' => __('Can open tickets')
                                // ],
                                // [
                                //     'name' => 'can_open_tickets',
                                //     'value' => 1,
                                //     'label' => __('Can open tickets')
                                // ],
                            ] as $item)
                            <div>
                                <div class="flex items-start mb-2">
                                    <div class="flex items-center h-5">
                                        <input
                                            id="{{ $item['label'] ?? null }}"
                                            name="{{ $item['label'] ?? null }}"
                                            type="checkbox"
                                            value="{{ $item['value'] ?? 1 }}"
                                            class="w-4 h-4 border border-gray-300 rounded bg-gray-50 focus:ring-0 focus:ring-blue-300 dark:bg-gray-700 dark:border-gray-600 dark:focus:ring-blue-600 dark:ring-offset-gray-800"
                                            {{ $item['value'] ?? 1 ? 'checked' : '' }}
                                        >
                                    </div>
                                    <label
                                        for="{{ $item['label'] ?? null }}"
                                        class="ms-2 text-sm font-medium text-gray-900 dark:text-gray-300">{{ $item['label'] ?? null }}</label>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>
                @endif

                @if (false)
                <div class="w-full mb-6">
                    <div class="border border-gray-700/50 dark:border-gray-700 pt-2 px-2 rounded-lg">
                        <div class="mb-3">
                            <label class="inline-flex items-center cursor-pointer">
                                <input
                                    x-model="generateRandomPassword"
                                    type="checkbox"
                                    value="generate_random_password"
                                    class="sr-only peer"
                                    x-on:change="generateRandomPassword = !generateRandomPassword"
                                    x-bind:checked="generateRandomPassword"
                                >
                                <div class="relative w-11 h-6 bg-gray-200 rounded-full peer peer-focus:ring-0 peer-focus:ring-blue-300 dark:peer-focus:ring-blue-800 dark:bg-gray-700 peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-0.5 after:start-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-600 peer-checked:bg-blue-600"></div>
                                <span class="ms-3 text-sm font-medium text-gray-900 dark:text-gray-300">
                                    @lang('Generate a random password')
                                </span>
                              </label>
                        </div>

                        <p
                            x-cloak x-show="generateRandomPassword"
                            class="mt-0 mb-2 text-sm text-gray-500 dark:text-gray-400"
                        >
                            @lang('Leave blank to generate a random password.')
                        </p>

                        <div class="grid gap-6 mb-3 md:grid-cols-2">
                            <div>
                                <label
                                    for="password"
                                    class="block mb-2 text-sm font-medium text-gray-900 dark:text-white"
                                >
                                    @lang('Password')
                                    <span
                                        x-cloak
                                        x-text="!generateRandomPassword ? '*' : ''"
                                        class="inline text-sm font-medium text-red-700 dark:text-red-500"
                                    >*</span>
                                </label>
                                <input type="password" id="password"
                                    name="password" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="•••••••••"
                                    x-bind:required="!generateRandomPassword"
                                />
                            </div>

                            <div>
                                <label for="password_confirmation" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
                                    @lang('Confirm password')
                                    <span
                                        x-cloak
                                        x-text="!generateRandomPassword ? '*' : ''"
                                        class="inline text-sm font-medium text-red-700 dark:text-red-500"
                                    >*</span>
                                </label>
                                <input type="password" id="password_confirmation" name="password_confirmation" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="•••••••••"
                                x-bind:required="!generateRandomPassword"
                            />
                            </div>
                        </div>
                    </div>
                </div>
                @endif

                <div class="w-full">
                    {{-- <button type="submit" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm w-full sm:w-auto px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">Submit</button> --}}
                    <div class="flex justify-end gap-4">
                        <button type="submit" class="inline-flex items-center px-4 py-2 bg-gray-800 dark:bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-white dark:text-gray-800 uppercase tracking-widest hover:bg-gray-700 dark:hover:bg-white focus:bg-gray-700 dark:focus:bg-white active:bg-gray-900 dark:active:bg-gray-300 focus:outline-none focus:ring-0 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150">
                            @lang('Create')
                        </button>
                    </div>
                </div>

            </form>
        </div>
    </div>
</x-app-layout>
