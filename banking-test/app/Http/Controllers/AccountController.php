<?php

namespace App\Http\Controllers;

use App\Http\Requests\OpenAccountRequest;
use App\Models\Account;
use App\Models\Customer;
use Illuminate\Http\Request;

class AccountController extends Controller
{
    /**
     * Display a listing of accounts.
     */
    public function index()
    {
        $accounts = Account::with('customer')->get();
        return view('accounts.index', compact('accounts'));
    }

    /**
     * Show the form for creating a new account.
     */
    public function create()
    {
        $customers = Customer::where('status', 'active')->get();
        return view('accounts.create', compact('customers'));
    }

    /**
     * Store a newly created account.
     */
    public function store(OpenAccountRequest $request)
    {
        $account = Account::create([
            'customer_id' => $request->customer_id,
            'account_type' => $request->account_type,
            'currency' => $request->currency ?? 'EUR',
            'balance' => 0,
            'status' => 'active',
        ]);

        return redirect()->route('accounts.index')->with('success', 'Account opened successfully!');
    }

    /**
     * Display the specified account.
     */
    public function show(Account $account)
    {
        $account->load('customer');
        $transactions = \App\Models\Transaction::with(['sourceAccount', 'targetAccount'])
            ->where(function($query) use ($account) {
                $query->where('source_account_id', $account->id)
                      ->orWhere('target_account_id', $account->id);
            })
            ->orderBy('created_at', 'desc')
            ->get();
        
        return view('accounts.show', compact('account', 'transactions'));
    }

    /**
     * Block an account.
     */
    public function block(Account $account)
    {
        if ($account->status === 'closed') {
            return redirect()->back()->with('error', 'Cannot block a closed account.');
        }

        $account->update(['status' => 'blocked']);
        return redirect()->back()->with('success', 'Account blocked successfully!');
    }

    /**
     * Close an account.
     */
    public function close(Account $account)
    {
        if ($account->balance != 0) {
            return redirect()->back()->with('error', 'Cannot close account with non-zero balance.');
        }

        if ($account->status === 'closed') {
            return redirect()->back()->with('error', 'Account is already closed.');
        }

        $account->update(['status' => 'closed']);
        return redirect()->back()->with('success', 'Account closed successfully!');
    }
}
