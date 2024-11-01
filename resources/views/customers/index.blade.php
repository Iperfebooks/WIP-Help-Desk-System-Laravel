<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('User list') }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="mb-6 w-full rounded-lg">
                <div class="flex flex-col">
                    <div class="rounded-xl border-0 border-gray-200 bg-gray-100 dark:bg-gray-800 p-3 shadow-md">
                        <form
                            x-data="{
                                showAdvancedForm: false,
                                canOpenTicketsOnly: @js(request()->input('can_open_tickets_only')),
                                contractId: @js(request()->input('contract_id')),
                                dateFrom: @js(request()->input('date.from')),
                                dateTo: @js(request()->input('date.to')),
                                submitForm() {
                                    if (!this.$refs?.filterForm) {
                                        return;
                                    }

                                    this.$refs?.filterForm?.submit();
                                },
                                resetSearch() {
                                    if (!this.$refs?.filterForm) {
                                        return;
                                    }

                                    this.$refs?.filterForm?.reset();
                                    {{-- this.$refs?.filterForm?.submit(); --}}
                                    location.href = location.origin + location.pathname;
                                },
                                init() {
                                    this.showAdvancedForm = @js($showAdvancedSearch);
                                },
                            }"
                            x-ref="filterForm"
                            x-on:keyup.enter="submitForm"
                        >
                            <div class="grid grid-cols-1 gap-6 md:grid-cols-2 lg:grid-cols-2 xl:grid-cols-3">
                                <div class="flex flex-col">
                                    <label for="search" class="block text-sm font-medium text-gray-900 dark:text-white">@lang('Free search')</label>
                                    <div class="relative w-full flex items-center justify-between rounded-md">
                                        <svg class="absolute left-2 block h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                            <circle cx="11" cy="11" r="8" class=""></circle>
                                            <line x1="21" y1="21" x2="16.65" y2="16.65" class=""></line>
                                        </svg>
                                        <input
                                            type="search"
                                            name="search"
                                            value="{{ request('search') }}"
                                            @class([
                                                'h-10',
                                                'w-full',
                                                'cursor-text',
                                                'block',
                                                'p-2',
                                                'ps-10',
                                                'text-sm',
                                                'text-gray-900',
                                                'border',
                                                'border-gray-300',
                                                'rounded-lg',
                                                // 'w-80',
                                                'bg-gray-50',
                                                'focus:ring-blue-500',
                                                'focus:border-blue-500',
                                                'dark:bg-gray-700',
                                                'dark:border-gray-600',
                                                'dark:placeholder-gray-400',
                                                'dark:text-white',
                                                'dark:focus:ring-blue-500',
                                                'dark:focus:border-blue-500',
                                            ])

                                            placeholder="{{ __('Search by :item', ['item' => __('name'). ', email, etc']) }}"
                                        />
                                    </div>
                                </div>

                                <div class="flex flex-col">
                                    <label for="name" class="block text-sm font-medium text-gray-900 dark:text-white">@lang('Name')</label>
                                    <input
                                        type="text"
                                        id="name"
                                        name="name"
                                        value="{{ request('name') }}"
                                        x-on:change="submitForm"
                                        x-on:keyup.enter="submitForm"
                                        placeholder="{{ __('Search by :item', ['item' => __('name')]) }}"
                                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                                </div>

                                <div class="flex flex-col justify-center pt-5">
                                    <button
                                        type="button"
                                        x-on:click="showAdvancedForm = !showAdvancedForm"
                                        class="rounded-lg bg-gray-200 px-8 py-2 font-medium text-gray-700 outline-none hover:opacity-80 focus:ring"
                                    >@lang('Show/hide advanced search')</button>
                                </div>
                            </div>

                            <div
                                x-cloak
                                x-show="showAdvancedForm"
                                class="my-6 md:my-3 grid grid-cols-1 gap-6 md:grid-cols-4"
                            >
                                <div class="flex flex-col">
                                    <label for="can_open_tickets_only" class="block text-sm font-medium text-gray-900 dark:text-white">@lang('Can open tickets')</label>

                                    <select
                                        x-on:change="submitForm"
                                        x-model="canOpenTicketsOnly"
                                        id="can_open_tickets_only"
                                        name="can_open_tickets_only" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                                        <option
                                            value=""
                                            @selected(!request('can_open_tickets_only'))
                                        >@lang('All')</option>
                                        <option
                                            value="true"
                                            @selected(in_array(request('can_open_tickets_only'), ['true', true, 1]))
                                        >@lang('Can open tickets only')</option>
                                        <option
                                            value="false"
                                            @selected(in_array(request('can_open_tickets_only'), ['false', false, '0'], true))
                                        >@lang('Can not open tickets only')</option>
                                    </select>
                                </div>

                                <div class="flex flex-col">
                                    <label for="contract_id" class="block text-sm font-medium text-gray-900 dark:text-white">@lang('Contract')</label>

                                    <select
                                        x-model="contractId"
                                        x-on:change="submitForm"
                                        id="contract_id"
                                        name="contract_id" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                                        <option value="">@lang('Choose a contract')</option>
                                        <option
                                            value="all"
                                            @selected(request('contract_id') == 'all')
                                        >@lang('All')</option>
                                        <option
                                            value="empty_only"
                                            @selected(request('contract_id') == 'empty_only')
                                        >@lang('Empty') - (@lang('No contract'))</option>
                                        @foreach ($contracts as $contract)
                                            <option
                                                value="{{ $contract?->id }}"
                                            >{{ $contract?->label }} @selected(request('contract_id') == $contract?->id)</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="col-span-1 md:col-span-2">
                                    <div class="grid grid-cols-2 gap-4 md:gap-6">
                                        <div class="flex flex-col">
                                            <label for="date_from" class="block text-sm font-medium text-gray-900 dark:text-white">@lang('Updated from')</label>
                                            <input
                                                x-model="dateFrom"
                                                type="date"
                                                id="date_from"
                                                name="date[from]"
                                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                                        </div>

                                        <div class="flex flex-col">
                                            <label for="date_to" class="block text-sm font-medium text-gray-900 dark:text-white">@lang('Updated until')</label>
                                            <input
                                                x-model="dateTo"
                                                type="date"
                                                id="date_to"
                                                name="date[to]"
                                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="mt-6 grid w-full grid-cols-2 justify-end space-x-4 md:flex">
                                <button
                                    type="button"
                                    x-on:click.prevent.capture.stop="resetSearch"
                                    class="rounded-lg bg-gray-200 px-8 py-2 font-medium text-gray-700 outline-none hover:opacity-80 focus:ring"
                                >@lang('Reset')</button>
                                <button
                                    type="button"
                                    x-on:click.prevent.capture.stop="submitForm"
                                    class="rounded-lg bg-blue-600 px-8 py-2 font-medium text-white outline-none hover:opacity-80 focus:ring"
                                >@lang('Search')</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <div class="relative overflow-x-auto shadow-md sm:rounded-lg p-3 bg-gray-800">
                <form
                    class="flex flex-column sm:flex-row flex-wrap space-y-4 sm:space-y-0 items-center justify-between pb-4"
                    x-data="{
                        submitForm() {
                            if (!this.$refs?.filterForm) {
                                return;
                            }

                            this.$refs?.filterForm?.submit();
                        }
                    }"
                    x-ref="filterForm"
                >
                    <div>
                        {{-- <label for="table-search" class="sr-only">Search</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 rtl:inset-r-0 rtl:right-0 flex items-center ps-3 pointer-events-none">
                                <svg class="w-5 h-5 text-gray-500 dark:text-gray-400" aria-hidden="true" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" clip-rule="evenodd"></path></svg>
                            </div>
                            <input
                                type="search"
                                id="table-search"
                                name="q"
                                placeholder="@lang('Search')"
                                value="{{ filter_var(request()?->string('q')) }}"
                                @class([
                                    'block',
                                    'p-2',
                                    'ps-10',
                                    'text-sm',
                                    'text-gray-900',
                                    'border',
                                    'border-gray-300',
                                    'rounded-lg',
                                    'w-80',
                                    'bg-gray-50',
                                    'focus:ring-blue-500',
                                    'focus:border-blue-500',
                                    'dark:bg-gray-700',
                                    'dark:border-gray-600',
                                    'dark:placeholder-gray-400',
                                    'dark:text-white',
                                    'dark:focus:ring-blue-500',
                                    'dark:focus:border-blue-500',
                                ])
                            >
                        </div> --}}
                    </div>

                    <div class="flex items-center gap-2 me-4">
                    </div>

                    <div>
                        <div class="flex items-center gap-4">
                            <a href="{{ route('customers.users.create') }}" class="inline-flex items-center px-4 py-2 bg-gray-800 dark:bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-white dark:text-gray-800 uppercase tracking-widest hover:bg-gray-700 dark:hover:bg-white focus:bg-gray-700 dark:focus:bg-white active:bg-gray-900 dark:active:bg-gray-300 focus:outline-none focus:ring-0 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150">
                                @lang('Add new')
                            </a>
                        </div>
                    </div>
                </form>

                <table
                    class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400"
                    x-data="{
                        invertDirection(direction) {
                            return (direction === 'desc') ? 'asc' : 'desc';
                        },
                        orderBy(col, direction = null) {
                            let sp = new URLSearchParams(location.search);
                            direction = direction || this.invertDirection(sp.get('direction'));
                            direction = ['asc', 'desc'].includes(direction) ? direction : 'desc';
                            sp.set('orderBy', col);
                            sp.set('direction', direction);
                            location.search = sp;
                        },
                        getArrowText(col) {
                            let sp = new URLSearchParams(location.search);
                            if (sp.get('orderBy') !== col) {
                                return null;
                            }

                            return (sp.get('direction') === 'asc') ? `&#8593;` : `&#8595;`;
                        },
                    }"
                >
                    <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                        <tr>
                            <th scope="col" class="py-2 px-3">
                                <div class="flex items-center">
                                    {{--
                                    <input id="checkbox-all-search" type="checkbox" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 dark:focus:ring-offset-gray-800 focus:ring-0 dark:bg-gray-700 dark:border-gray-600">
                                    <label for="checkbox-all-search" class="sr-only">checkbox</label>
                                    --}}
                                </div>
                            </th>
                            <th scope="col" class="py-2 px-3 cursor-pointer" x-on:click.capture="orderBy('id')">
                                <span x-text="getArrowText('id')"></span>
                                {{ __('ID') }}
                            </th>
                            <th scope="col" class="py-2 px-3 cursor-pointer" x-on:click.capture="orderBy('name')">
                                <span x-text="getArrowText('name')"></span>
                                {{ __('Name') }}
                            </th>
                            <th scope="col" class="py-2 px-3 cursor-pointer" x-on:click.capture="orderBy('contract_id')">
                                <span x-text="getArrowText('contract_id')"></span>
                                {{ __('Contract') }}
                            </th>
                            <th scope="col" class="py-2 px-3 cursor-pointer" x-on:click.capture="orderBy('email')">
                                <span x-text="getArrowText('email')"></span>
                                {{ __('E-mail') }}
                            </th>
                            <th scope="col" class="py-2 px-3 cursor-pointer" x-on:click.capture="orderBy('can_open_tickets')">
                                <span x-text="getArrowText('can_open_tickets')"></span>
                                {{ __('Can open tickets') }}
                            </th>
                            <th scope="col" class="py-2 px-3 cursor-pointer" x-on:click.capture="orderBy('tickets_count')">
                                <span x-text="getArrowText('tickets_count')"></span>
                                {{ __('Total of tickets') }}
                            </th>
                            <th scope="col" class="py-2 px-3 cursor-pointer" x-on:click.capture="orderBy('updated_at')">
                                <span x-text="getArrowText('updated_at')"></span>
                                {{ __('Last update') }}
                            </th>
                            <th scope="col" class="py-2 px-3">
                                {{ __('Actions') }}
                            </th>
                        </tr>
                    </thead>
                    <tbody
                        x-data="{
                            rowOnOut(event) {
                                let element = event?.target;

                                if (!element) {
                                    return;
                                }

                                let tr = element.tagName?.toLowerCase() === 'tr' ? element : element.closest('.animate-pulse');

                                if (!tr || !tr?.classList?.contains('animate-pulse')) {
                                    return;
                                }

                                {{-- setTimeout(
                                    () => tr?.classList?.remove(
                                        'animate-pulse', 'bg-green-300', 'dark:bg-green-600', 'dark:text-green-950',
                                        'dark:hover:text-green-500',
                                    ),
                                    2500
                                ); --}}
                            },
                        }"
                    >
                        @foreach ($records as $record)
                        @php
                            $successOnThis = session()?->get('success_message_on') === sprintf('[data-record-id="%s"]', $record?->id);
                        @endphp
                        <tr
                            @class([
                                'border-b dark:border-gray-700',
                                // 'bg-white dark:bg-gray-800 hover:bg-gray-50 dark:hover:bg-gray-600' => !$successOnThis,
                                'bg-white dark:bg-gray-800 hover:bg-gray-50 dark:hover:bg-gray-800/75' => !$successOnThis,
                                'animate-pulse bg-green-600/50 text-green-700 dark:text-green-500' => $successOnThis,
                            ])
                            x-on:mouseout.capture="rowOnOut"
                        >
                            <td class="w-4 p-4">
                                <div class="flex items-center">
                                    {{-- <input id="checkbox-table-search-2" type="checkbox" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 dark:focus:ring-offset-gray-800 focus:ring-0 dark:bg-gray-700 dark:border-gray-600">
                                    <label for="checkbox-table-search-2" class="sr-only">checkbox</label> --}}
                                </div>
                            </td>
                            <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                {{ $record?->id }}
                            </th>
                            <td class="px-6 py-4">
                                {{ $record?->name }}
                            </td>
                            <td class="px-6 py-4">
                                {{ $record?->contract?->name }}
                            </td>
                            <td class="px-6 py-4">
                                {{ $record?->email }}
                            </td>
                            <td
                                class="px-6 py-4"
                                x-data="{
                                    can_open_tickets: {{ $record?->can_open_tickets ? 'true' : 'false' }},
                                }"
                            >
                                <form method="POST" action="{{ route('customers.users.update', $record?->id) }}">
                                    @csrf
                                    @method('PUT')
                                    <div class="hidden">
                                        <input
                                            type="hidden"
                                            name="can_open_tickets"
                                            x-model="can_open_tickets"
                                        >
                                    </div>
                                    <label
                                        class="inline-flex items-center me-5 cursor-pointer"
                                    >
                                        <span class="me-3 text-sm font-medium text-gray-900 dark:text-gray-300">@lang('No')</span>
                                        <input
                                            type="checkbox"
                                            class="sr-only peer"
                                            x-model="can_open_tickets"
                                            {{ $record?->can_open_tickets ? 'checked' : '' }}
                                            x-on:change="(event) => {
                                                can_open_tickets = !can_open_tickets;
                                                {{-- event.preventDefault(); --}}
                                                event.target?.closest('form')?.submit();
                                            }"
                                        >
                                        <div class="relative w-11 h-6 bg-gray-200 rounded-full peer peer-focus:ring-0 peer-focus:ring-red-300 dark:peer-focus:ring-red-800 dark:bg-gray-700 peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-0.5 after:start-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-600 peer-checked:bg-red-600"></div>
                                        <span class="ms-3 text-sm font-medium text-gray-900 dark:text-gray-300">@lang('Yes')</span>
                                    </label>
                                </form>

                            </td>
                            <td class="px-6 py-4">
                                {{ $record?->tickets_count }}
                            </td>
                            <td class="px-6 py-4">
                                {{ $record?->updated_at?->diffForHumans()}}
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex gap-4">
                                    {{--
                                    <button
                                        type="button"
                                        @class([
                                            'text-white',
                                            'border',
                                            'border-red-700',
                                            'bg-red-700',
                                            'hover:bg-transparent',
                                            'hover:text-red-700',
                                            'focus:ring-1',
                                            'focus:outline-none',
                                            'focus:ring-red-300',
                                            'font-medium',
                                            'rounded-lg',
                                            'text-sm',
                                            'p-1',
                                            'text-center',
                                            'inline-flex',
                                            'items-center',
                                            'dark:border-red-500',
                                            'dark:text-white',
                                            'dark:hover:text-white',
                                            'dark:focus:ring-red-800',
                                            'dark:bg-red-500',
                                            'dark:hover:bg-red-500/[.6]',
                                        ])
                                    >
                                        <svg class="w-5 h-5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill="currentColor" d="M9.12856092,0 L11.102803,0.00487381102 C11.8809966,0.0985789507 12.5627342,0.464975115 13.1253642,1.0831551 C13.583679,1.58672038 13.8246919,2.17271137 13.8394381,2.81137259 L19.3143116,2.81154887 C19.6930068,2.81154887 20,3.12136299 20,3.50353807 C20,3.88571315 19.6930068,4.19552726 19.3143116,4.19552726 L17.478,4.195 L17.4783037,15.8224356 C17.4783037,18.3654005 16.529181,20 14.4365642,20 L5.41874994,20 C3.32701954,20 2.39315828,18.3737591 2.39315828,15.8224356 L2.393,4.195 L0.685688428,4.19552726 C0.306993166,4.19552726 0,3.88571315 0,3.50353807 C0,3.12136299 0.306993166,2.81154887 0.685688428,2.81154887 L6.15581653,2.81128823 C6.17048394,2.29774844 6.36057711,1.7771773 6.7098201,1.26219866 C7.23012695,0.494976667 8.04206594,0.0738475069 9.12856092,0 Z M16.106,4.195 L3.764,4.195 L3.76453514,15.8224356 C3.76453514,17.7103418 4.28461756,18.6160216 5.41874994,18.6160216 L14.4365642,18.6160216 C15.5759705,18.6160216 16.1069268,17.7015972 16.1069268,15.8224356 L16.106,4.195 Z M6.71521035,6.34011422 C7.09390561,6.34011422 7.40089877,6.64992834 7.40089877,7.03210342 L7.40089877,15.0820969 C7.40089877,15.464272 7.09390561,15.7740861 6.71521035,15.7740861 C6.33651508,15.7740861 6.02952192,15.464272 6.02952192,15.0820969 L6.02952192,7.03210342 C6.02952192,6.64992834 6.33651508,6.34011422 6.71521035,6.34011422 Z M9.44248307,6.34011422 C9.82117833,6.34011422 10.1281715,6.64992834 10.1281715,7.03210342 L10.1281715,15.0820969 C10.1281715,15.464272 9.82117833,15.7740861 9.44248307,15.7740861 C9.06378781,15.7740861 8.75679464,15.464272 8.75679464,15.0820969 L8.75679464,7.03210342 C8.75679464,6.64992834 9.06378781,6.34011422 9.44248307,6.34011422 Z M12.1697558,6.34011422 C12.5484511,6.34011422 12.8554442,6.64992834 12.8554442,7.03210342 L12.8554442,15.0820969 C12.8554442,15.464272 12.5484511,15.7740861 12.1697558,15.7740861 C11.7910605,15.7740861 11.4840674,15.464272 11.4840674,15.0820969 L11.4840674,7.03210342 C11.4840674,6.64992834 11.7910605,6.34011422 12.1697558,6.34011422 Z M9.17565461,1.38234438 C8.53434679,1.42689992 8.11102741,1.64646338 7.84152662,2.04385759 C7.6437582,2.33547837 7.5448762,2.58744977 7.52918786,2.81194335 L12.4673768,2.81085985 C12.4530266,2.51959531 12.3382454,2.26423777 12.1153724,2.01935991 C11.7693001,1.63911901 11.3851686,1.43266964 11.0215648,1.38397839 L9.17565461,1.38234438 Z"></path>
                                        </svg>
                                        <span class="sr-only">Edit</span>
                                    </button>
                                    --}}

                                    <a
                                        href="{{ route('customers.users.edit', $record?->id) }}"
                                        @class([
                                            'text-white',
                                            'border',
                                            'border-blue-700',
                                            'bg-blue-700',
                                            'hover:bg-transparent',
                                            'hover:text-blue-700',
                                            'focus:ring-1',
                                            'focus:outline-none',
                                            'focus:ring-blue-300',
                                            'font-medium',
                                            'rounded-lg',
                                            'text-sm',
                                            'p-1',
                                            'text-center',
                                            'inline-flex',
                                            'items-center',
                                            'dark:border-blue-500',
                                            'dark:text-white',
                                            'dark:hover:text-white',
                                            'dark:focus:ring-blue-800',
                                            'dark:bg-blue-500',
                                            'dark:hover:bg-blue-500/[.6]',
                                        ])
                                    >
                                        <svg class="w-5 h-5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 24 24">
                                            <path d="M20.71,7.04C21.1,6.65 21.1,6 20.71,5.63L18.37,3.29C18,2.9 17.35,2.9 16.96,3.29L15.12,5.12L18.87,8.87M3,17.25V21H6.75L17.81,9.93L14.06,6.18L3,17.25Z"></path>
                                        </svg>
                                        <span class="sr-only">{{ __('Edit') }}</span>
                                    </a>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            @if ($records->hasPages())
                <div class="mt-4">
                    {{ $records->links('pagination::tailwind') }}
                </div>
            @endif

            {{--
                <nav class="flex items-center flex-column flex-wrap md:flex-row justify-between pt-4" aria-label="Table navigation">
                <span class="text-sm font-normal text-gray-500 dark:text-gray-400 mb-4 md:mb-0 block w-full md:inline md:w-auto">Showing <span class="font-semibold text-gray-900 dark:text-white">1-10</span> of <span class="font-semibold text-gray-900 dark:text-white">1000</span></span>
                <ul class="inline-flex -space-x-px rtl:space-x-reverse text-sm h-8">
                    <li>
                        <a href="#" class="flex items-center justify-center px-3 h-8 ms-0 leading-tight text-gray-500 bg-white border border-gray-300 rounded-s-lg hover:bg-gray-100 hover:text-gray-700 dark:bg-gray-800 dark:border-gray-700 dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-white">Previous</a>
                    </li>
                    <li>
                        <a href="#" class="flex items-center justify-center px-3 h-8 leading-tight text-gray-500 bg-white border border-gray-300 hover:bg-gray-100 hover:text-gray-700 dark:bg-gray-800 dark:border-gray-700 dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-white">1</a>
                    </li>
                    <li>
                        <a href="#" class="flex items-center justify-center px-3 h-8 leading-tight text-gray-500 bg-white border border-gray-300 hover:bg-gray-100 hover:text-gray-700 dark:bg-gray-800 dark:border-gray-700 dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-white">2</a>
                    </li>
                    <li>
                        <a href="#" aria-current="page" class="flex items-center justify-center px-3 h-8 text-blue-600 border border-gray-300 bg-blue-50 hover:bg-blue-100 hover:text-blue-700 dark:border-gray-700 dark:bg-gray-700 dark:text-white">3</a>
                    </li>
                    <li>
                        <a href="#" class="flex items-center justify-center px-3 h-8 leading-tight text-gray-500 bg-white border border-gray-300 hover:bg-gray-100 hover:text-gray-700 dark:bg-gray-800 dark:border-gray-700 dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-white">4</a>
                    </li>
                    <li>
                        <a href="#" class="flex items-center justify-center px-3 h-8 leading-tight text-gray-500 bg-white border border-gray-300 hover:bg-gray-100 hover:text-gray-700 dark:bg-gray-800 dark:border-gray-700 dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-white">5</a>
                    </li>
                    <li>
                        <a href="#" class="flex items-center justify-center px-3 h-8 leading-tight text-gray-500 bg-white border border-gray-300 rounded-e-lg hover:bg-gray-100 hover:text-gray-700 dark:bg-gray-800 dark:border-gray-700 dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-white">Next</a>
                    </li>
                </ul>
                </nav>
            --}}
        </div>
    </div>
</x-app-layout>
