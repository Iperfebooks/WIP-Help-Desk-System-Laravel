<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('List of companies') }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
                <form
                    class="flex flex-column sm:flex-row flex-wrap space-y-4 sm:space-y-0 items-center justify-between pb-4"
                    x-data
                >
                    <div>
                        <label for="table-search" class="sr-only">Search</label>
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
                        </div>
                    </div>

                    <div>
                        <div class="flex items-center gap-4">
                            <a href="{{ route('contracts.create') }}" class="inline-flex items-center px-4 py-2 bg-gray-800 dark:bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-white dark:text-gray-800 uppercase tracking-widest hover:bg-gray-700 dark:hover:bg-white focus:bg-gray-700 dark:focus:bg-white active:bg-gray-900 dark:active:bg-gray-300 focus:outline-none focus:ring-0 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150">
                                @lang('Add new')
                            </a>
                        </div>
                    </div>
                </form>

                <table
                    class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400"
                    x-data="{
                        invertDirection(direction) {
                            return direction === 'desc' ? 'asc' : 'desc';
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

                            return sp.get('direction') === 'asc' ? `&#8593;` : `&#8595;`;
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
                            <th scope="col" class="py-2 px-3 cursor-pointer" x-on:click.capture="orderBy('document_type')">
                                <span x-text="getArrowText('document_type')"></span>
                                {{ __('Document type') }}
                            </th>
                            <th scope="col" class="py-2 px-3 cursor-pointer" x-on:click.capture="orderBy('document_value')">
                                <span x-text="getArrowText('document_value')"></span>
                                {{ __('Document value') }}
                            </th>
                            <th scope="col" class="py-2 px-3 cursor-pointer" x-on:click.capture="orderBy('email')">
                                <span x-text="getArrowText('email')"></span>
                                {{ __('E-mail') }}
                            </th>
                            <th scope="col" class="py-2 px-3 cursor-pointer" x-on:click.capture="orderBy('customers_count')">
                                <span x-text="getArrowText('customers_count')"></span>
                                {{ __('Users') }}
                            </th>
                            <th scope="col" class="py-2 px-3">
                                {{ __('Tickets') }}
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
                                {{ $record?->document_type?->name }}
                            </td>
                            <td class="px-6 py-4">
                                {{ $record?->document_value }}
                            </td>
                            <td class="px-6 py-4">
                                {{ $record?->email }}
                            </td>
                            <td class="px-6 py-4">
                                {{ $record?->customers_count }}
                            </td>
                            <td class="px-6 py-4">
                                {{ $record?->totalOfTickets }}
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
                                        href="{{ route('contracts.edit', $record?->id) }}"
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
