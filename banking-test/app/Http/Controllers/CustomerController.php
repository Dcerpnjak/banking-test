<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCustomerRequest;
use App\Models\Customer;

class CustomerController extends Controller
{
    /**
     * Display a listing of customers.
     */
    public function index()
    {
        $customers = Customer::with('accounts')->get();
        return view('customers.index', compact('customers'));
    }

    /**
     * Display the specified customer.
     */
    public function show(Customer $customer)
    {
        $customer->load('accounts');
        return view('customers.show', compact('customer'));
    }

    /**
     * Store a newly created customer.
     */
    public function store(StoreCustomerRequest $request)
    {
        $customer = Customer::create([
            'name' => $request->name,
            'status' => $request->status ?? 'active',
        ]);

        return redirect()->route('customers.index')->with('success', 'Customer created successfully!');
    }

    /**
     * Block a customer and all associated accounts.
     */
    public function block(Customer $customer)
    {
        if ($customer->status === 'closed') {
            return redirect()->back()->with('error', 'Cannot block a closed customer.');
        }

        // Block customer
        $customer->update(['status' => 'blocked']);

        // Block all associated accounts
        $customer->accounts()->where('status', '!=', 'closed')->update(['status' => 'blocked']);

        return redirect()->back()->with('success', 'Customer and all associated accounts blocked successfully!');
    }

    /**
     * Close a customer (only if all accounts are closed).
     */
    public function close(Customer $customer)
    {
        if ($customer->status === 'closed') {
            return redirect()->back()->with('error', 'Customer is already closed.');
        }

        // Check if customer has any accounts that are not closed
        $hasNonClosedAccounts = $customer->accounts()->where('status', '!=', 'closed')->exists();

        if ($hasNonClosedAccounts) {
            return redirect()->back()->with('error', 'Cannot close customer. All accounts must be closed first.');
        }

        // Close customer
        $customer->update(['status' => 'closed']);

        return redirect()->back()->with('success', 'Customer closed successfully!');
    }
}
