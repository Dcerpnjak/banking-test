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
        $customers = Customer::all();
        return view('customers.index', compact('customers'));
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
}
