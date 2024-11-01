<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('User') }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <x-tiago-f2.tw-tab.tab-container
                :defaultTab="request()->input('activetab', 'profile')"
            >
                <x-slot:triggers class="font-bold">
                    <x-tiago-f2.tw-tab.tab-trigger
                        tabId="profile"
                        :label="__('Profile')"
                    />
                    <x-tiago-f2.tw-tab.tab-trigger
                        tabId="resources"
                        :label="__('Resources')"
                    />
                    {{-- <x-tiago-f2.tw-tab.tab-trigger
                        tabId="settings"
                        :label="__('Settings')"
                    />
                    <x-tiago-f2.tw-tab.tab-trigger
                        tabId="contacts"
                        :label="__('Contacts')"
                    /> --}}
                </x-slot>

                <x-slot:items class="font-bold">
                    <x-tiago-f2.tw-tab.tab-item
                        tabId="profile"
                        :label="__('Profile')"
                    >
                        <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg">
                            <div class="max-w-xl">
                                @include('customers.partials.update-profile-information-form')
                            </div>
                        </div>

                        <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg">
                            <div class="max-w-xl">
                                @include('customers.partials.update-password-form')
                            </div>
                        </div>
                    </x-tiago-f2.tw-tab.tab-item>

                    <x-tiago-f2.tw-tab.tab-item
                        tabId="resources"
                        :label="__('Resources')"
                    >
                        <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg">
                            <div class="max-w-full">
                                @include('customers.partials.update-customer-resources-form')
                            </div>
                        </div>
                    </x-tiago-f2.tw-tab.tab-item>

                    {{-- <x-tiago-f2.tw-tab.tab-item
                        tabId="settings"
                        :label="__('Settings')"
                    >
                        <p class="text-sm text-gray-500 dark:text-gray-400">This is some placeholder content the <strong class="font-medium text-gray-800 dark:text-white">Settings tab's associated content</strong>. Clicking another tab will toggle the visibility of this one for the next. The tab JavaScript swaps classes to control the content visibility and styling.</p>
                    </x-tiago-f2.tw-tab.tab-item> --}}

                    {{-- <x-tiago-f2.tw-tab.tab-item
                        tabId="contacts"
                        :label="__('Contacts')"
                    >
                        <p class="text-sm text-gray-500 dark:text-gray-400">This is some placeholder content the <strong class="font-medium text-gray-800 dark:text-white">Contacts tab's associated content</strong>. Clicking another tab will toggle the visibility of this one for the next. The tab JavaScript swaps classes to control the content visibility and styling.</p>
                    </x-tiago-f2.tw-tab.tab-item> --}}
                </x-slot:items>
            </x-tiago-f2.tw-tab.tab-container>

            @if (false)
            <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg">
                <div class="max-w-xl">
                    @include('profile.partials.delete-user-form')
                </div>
            </div>
            @endif
        </div>
    </div>
</x-app-layout>
