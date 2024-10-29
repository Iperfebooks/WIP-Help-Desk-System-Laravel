<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\RedirectResponse;
use Illuminate\Validation\Rules\Password;
use App\Models\Contract;

class CustomerUserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->input('contract_id') === 'all') {
            $request->merge(['contract_id' => null]);
        }

        $validator = validator($request->all(), [
            'can_open_tickets_only' => 'nullable|in:' . implode(',', ['1', '0', 'true', 'false']),
            'contract_id' => 'nullable|integer',
            'name' => 'nullable|string',
            'date.from' => 'nullable|date',
            'date.to' => 'nullable|date|after:date.from',
        ]);

        if ($validator->fails()) {
            return redirect()->route('customers.users.index')->withErrors($validator->getMessageBag());
        }

        $searchByName = filter_var($request?->input('name'));
        $search = filter_var($request?->input('q', $request?->input('search')));
        $orderBy = filter_var($request?->input('orderBy'));
        $direction = filter_var($request?->input('direction'));

        $canOpenTicketsOnly = $request->input('can_open_tickets_only');
        $contractId = $request->input('contract_id');
        $dateFrom = $request->input('date.from');
        $dateTo = $request->input('date.to');

        $query = Customer::orderBy(
            in_array($orderBy, [
                'id',
                'name',
                'email',
                'can_open_tickets',
                'tickets_count',
                'contract_id',
                'updated_at',
            ]) ? $orderBy : 'id',
            in_array($direction, ['asc', 'desc']) ? $direction : 'asc'
        )
                //?->withCount('tickets')
                ?->with([
                    'contract' => fn ($query) => $query->select([
                        'id',
                        'name',
                    ])
                ])
                ?->when($search, fn ($query) => $query->whereRaw('LOWER(name) like ?', '%' . $search . '%'))
                ?->when($searchByName, fn ($query) => $query->whereRaw('LOWER(name) like ?', '%' . $searchByName . '%'))
                ?->when($dateFrom, fn ($query) => $query->where('updated_at', '>=', $dateFrom))
                ?->when($dateTo, fn ($query) => $query->where('updated_at', '<=', $dateTo))
                ?->when(
                    !is_null($canOpenTicketsOnly),
                    function ($query) use ($canOpenTicketsOnly) {
                        $value = filter_var($canOpenTicketsOnly, FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE);

                        return $query->where('can_open_tickets', $value);
                    },
                )
                ?->when($contractId, fn ($query) => $query->where('contract_id', $contractId));

        return view('customers.index', [
            'contracts' => Contract::selectIds(),
            'showAdvancedSearch' => $canOpenTicketsOnly || $contractId || $dateFrom || $dateTo,
            'records' => $query?->paginate(20)?->withQueryString(),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        return view('customers.create', [
            'contracts' => Contract::selectIds(),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validatedPassword = trim(
            filter_var(
                $request?->input('password'),
                FILTER_DEFAULT,
                FILTER_NULL_ON_FAILURE
            ) ?? ''
        ) ?: null;

        $randomPass = str()->random(8);
        $passwordAsStr = $validatedPassword ?: $randomPass;
        $confirmPassword = $validatedPassword ? $request?->input('password_confirmation') : $passwordAsStr;

        $request?->merge(
            array_filter([
                'can_open_tickets' => filter_var(
                    $request?->input('can_open_tickets'),
                    FILTER_VALIDATE_BOOLEAN,
                    FILTER_NULL_ON_FAILURE
                ) ?? false,
                'password' => $passwordAsStr,
                'password_confirmation' => $confirmPassword,
            ], fn ($item) => filled($item))
        );

        $validated = $request?->validate([
            'name' => 'required|string|min:3',
            'email' => 'required|email|unique:' . Customer::class . ',email',
            'contract_id' => 'nullable|integer|exists:' . Contract::class . ',id',
            'password' => ['required', Password::defaults(), 'confirmed'],
            'can_open_tickets' => 'required|boolean',
        ]);

        $customer = new Customer();

        $storeData = collect([
            'name' => $validated['name'] ?? null,
            'email' => $validated['email'] ?? null,
            'contract_id' => $validated['contract_id'] ?? null,
            'password' => Hash::make($passwordAsStr),
            'can_open_tickets' => $validated['can_open_tickets'] ?? false,
        ])?->filter(fn ($item) => filled($item));

        $storeData?->each(function ($value, $key) use (&$customer) {
            $customer->{$key} = $value;
        });

        $created = $customer->save();

        $to = redirect()?->route('customers.users.index', [
            'orderBy' => 'updated_at',
            'direction' => 'desc',
        ]);

        return $to->with($created ? [
            'success' => __('Record created successfully!'),
            'success_message_on' => sprintf('[data-record-id="%s"]', $customer?->id),
        ] : [
            'error' => __('Fail on store record!'),
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request, string $id)
    {
        $customer = Customer::where('id', $id)->first();

        if (!$customer) {
            return redirect()?->route('customers.users.index')
                    ?->with([
                        'error' => __('Not found :item', [
                            'item' => __('customer'),
                        ])
                    ]);
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Request $request, string $id)
    {
        $customer = Customer::where('id', $id)
                ?->with([
                    'contract' => fn ($query) => $query->select([
                        'id',
                        'name',
                    ])
                ])
            ->first();

        if (!$customer) {
            return redirect()?->route('customers.users.index')
                    ?->with([
                        'error' => __('Not found :item', [
                            'item' => __('customer'),
                        ])
                    ]);
        }

        return view('customers.edit', [
            'customer' => $customer,
            'user' => auth()->user(),
            'contracts' => Contract::selectIds(),
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request?->merge(
            array_filter([
                'can_open_tickets' => filter_var(
                    $request?->input('can_open_tickets'),
                    FILTER_VALIDATE_BOOLEAN,
                    FILTER_NULL_ON_FAILURE
                ),
            ], fn ($item) => filled($item))
        );

        $request?->validate([
            'name' => 'nullable|string|min:3',
            'contract_id' => 'nullable|integer|exists:' . Contract::class . ',id',
            'password' => ['nullable', Password::defaults(), 'confirmed'],
            'can_open_tickets' => 'nullable|boolean',
        ]);

        $customer = Customer::where('id', $id)->first();

        if (!$customer) {
            return redirect()?->route('customers.users.index')
                    ?->with([
                        'error' => __('Not found :item', [
                            'item' => __('customer'),
                        ])
                    ]);
        }

        $updateData = collect([
            'name' => $request?->input('name') ?: null,
            // 'email' => $request?->input('email'), // Cant update email (email is the customer ID)
            'password' => $request?->input('password') ? Hash::make($request?->input('password')) : null,
            'can_open_tickets' => $request?->boolean('can_open_tickets'),
        ])?->filter(fn ($item) => filled($item))
                ?->put('contract_id', $request?->input('contract_id') ?: null);

        $updateData?->each(function ($value, $key) use (&$customer) {
            $customer->{$key} = $value;
        });

        $updated = $customer->save();

        $to = back() ? back() : redirect()?->route('customers.users.index', [
            'orderBy' => 'updated_at',
            'direction' => 'desc',
        ]);

        return $to->with($updated ? [
            'success' => __('Record updated successfully!'),
            'status' => 'customer-updated',
            'success_message_on' => sprintf('[data-record-id="%s"]', $customer?->id),
        ] : [
            'error' => __('Fail on update record!'),
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, string $id)
    {
        $customer = Customer::where('id', $id)->first();

        if (!$customer) {
            return redirect()?->route('customers.users.index')
                    ?->with([
                        'error' => __('Not found :item', [
                            'item' => __('customer'),
                        ])
                    ]);
        }

        $deleted = $customer->delete();

        $to = back() ? back() : redirect()?->route('customers.users.index', [
            'orderBy' => 'updated_at',
            'direction' => 'desc',
        ]);

        return $to->with($deleted ? [
            'success' => __('Record deleted successfully!'),
            'success_message_on' => sprintf('[data-record-id="%s"]', $customer?->id),
        ] : [
            'error' => __('Fail on delete record!'),
        ]);
    }

    /**
     * Update the user's password.
     */
    public function updatePassword(Request $request, string $id): RedirectResponse
    {
        $customer = Customer::where('id', $id)->first();

        if (!$customer) {
            return redirect()?->route('customers.users.index')
                    ?->with([
                        'error' => __('Not found :item', [
                            'item' => __('customer'),
                        ])
                    ]);
        }

        $validated = $request->validateWithBag('updatePassword', [
            'password' => ['required', Password::defaults(), 'confirmed'],
        ]);

        $customer->update([
            'password' => Hash::make($validated['password']),
        ]);

        return back()->with('status', 'password-updated');
    }
}
