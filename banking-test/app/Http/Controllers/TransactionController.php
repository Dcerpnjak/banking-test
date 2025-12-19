<?php

namespace App\Http\Controllers;

use App\Http\Requests\DepositRequest;
use App\Http\Requests\TransferRequest;
use App\Http\Requests\WithdrawRequest;
use App\Models\Account;
use App\Models\Transaction;
use Illuminate\Support\Facades\DB;

class TransactionController extends Controller
{
    /**
     * Display a listing of transactions.
     */
    public function index()
    {
        $transactions = Transaction::with(['sourceAccount', 'targetAccount'])
            ->orderBy('created_at', 'desc')
            ->get();
        return view('transactions.index', compact('transactions'));
    }

    /**
     * Show the form for creating a deposit.
     */
    public function createDeposit()
    {
        $accounts = Account::where('status', 'active')->get();
        return view('transactions.deposit', compact('accounts'));
    }

    /**
     * Show the form for creating a withdrawal.
     */
    public function createWithdrawal()
    {
        $accounts = Account::where('status', 'active')->get();
        return view('transactions.withdraw', compact('accounts'));
    }

    /**
     * Show the form for creating a transfer.
     */
    public function createTransfer()
    {
        $accounts = Account::where('status', 'active')->get();
        return view('transactions.transfer', compact('accounts'));
    }

    /**
     * Store a deposit transaction.
     */
    public function deposit(DepositRequest $request)
    {
        return DB::transaction(function () use ($request) {
            $account = Account::with('customer')->findOrFail($request->account_id);

            // Validate customer is not blocked
            if ($account->customer->status === 'blocked') {
                $transaction = Transaction::create([
                    'type' => 'deposit',
                    'amount' => $request->amount,
                    'target_account_id' => $account->id,
                    'status' => 'rejected',
                    'rejection_reason' => 'Customer is blocked',
                ]);
                return redirect()->back()->with('error', 'Customer is blocked. Transaction rejected.');
            }

            // Validate account is active
            if ($account->status !== 'active') {
                $transaction = Transaction::create([
                    'type' => 'deposit',
                    'amount' => $request->amount,
                    'target_account_id' => $account->id,
                    'status' => 'rejected',
                    'rejection_reason' => 'Account is not active',
                ]);
                return redirect()->back()->with('error', 'Account is not active. Transaction rejected.');
            }

            // Validate amount is greater than zero
            if ($request->amount <= 0) {
                $transaction = Transaction::create([
                    'type' => 'deposit',
                    'amount' => $request->amount,
                    'target_account_id' => $account->id,
                    'status' => 'rejected',
                    'rejection_reason' => 'Amount must be greater than zero',
                ]);
                return redirect()->back()->with('error', 'Amount must be greater than zero.');
            }

            // Create transaction
            $transaction = Transaction::create([
                'type' => 'deposit',
                'amount' => $request->amount,
                'target_account_id' => $account->id,
                'status' => 'success',
            ]);

            // Update account balance
            $account->increment('balance', $request->amount);

            return redirect()->route('transactions.index')->with('success', 'Deposit successful!');
        });
    }

    /**
     * Store a withdrawal transaction.
     */
    public function withdraw(WithdrawRequest $request)
    {
        return DB::transaction(function () use ($request) {
            $account = Account::with('customer')->findOrFail($request->account_id);

            // Validate customer is not blocked
            if ($account->customer->status === 'blocked') {
                $transaction = Transaction::create([
                    'type' => 'withdrawal',
                    'amount' => $request->amount,
                    'source_account_id' => $account->id,
                    'status' => 'rejected',
                    'rejection_reason' => 'Customer is blocked',
                ]);
                return redirect()->back()->with('error', 'Customer is blocked. Transaction rejected.');
            }

            // Validate account is active
            if ($account->status !== 'active') {
                $transaction = Transaction::create([
                    'type' => 'withdrawal',
                    'amount' => $request->amount,
                    'source_account_id' => $account->id,
                    'status' => 'rejected',
                    'rejection_reason' => 'Account is not active',
                ]);
                return redirect()->back()->with('error', 'Account is not active. Transaction rejected.');
            }

            // Validate amount is greater than zero
            if ($request->amount <= 0) {
                $transaction = Transaction::create([
                    'type' => 'withdrawal',
                    'amount' => $request->amount,
                    'source_account_id' => $account->id,
                    'status' => 'rejected',
                    'rejection_reason' => 'Amount must be greater than zero',
                ]);
                return redirect()->back()->with('error', 'Amount must be greater than zero.');
            }

            // Validate sufficient balance
            if ($account->balance < $request->amount) {
                $transaction = Transaction::create([
                    'type' => 'withdrawal',
                    'amount' => $request->amount,
                    'source_account_id' => $account->id,
                    'status' => 'rejected',
                    'rejection_reason' => 'Insufficient balance',
                ]);
                return redirect()->back()->with('error', 'Insufficient balance. Transaction rejected.');
            }

            // Create transaction
            $transaction = Transaction::create([
                'type' => 'withdrawal',
                'amount' => $request->amount,
                'source_account_id' => $account->id,
                'status' => 'success',
            ]);

            // Update account balance
            $account->decrement('balance', $request->amount);

            return redirect()->route('transactions.index')->with('success', 'Withdrawal successful!');
        });
    }

    /**
     * Store a transfer transaction.
     */
    public function transfer(TransferRequest $request)
    {
        return DB::transaction(function () use ($request) {
            $sourceAccount = Account::with('customer')->findOrFail($request->source_account_id);
            $targetAccount = Account::with('customer')->findOrFail($request->target_account_id);

            // Validate source customer is not blocked
            if ($sourceAccount->customer->status === 'blocked') {
                $transaction = Transaction::create([
                    'type' => 'transfer',
                    'amount' => $request->amount,
                    'source_account_id' => $sourceAccount->id,
                    'target_account_id' => $targetAccount->id,
                    'status' => 'rejected',
                    'rejection_reason' => 'Source customer is blocked',
                ]);
                return redirect()->back()->with('error', 'Source customer is blocked. Transaction rejected.');
            }

            // Validate target customer is not blocked
            if ($targetAccount->customer->status === 'blocked') {
                $transaction = Transaction::create([
                    'type' => 'transfer',
                    'amount' => $request->amount,
                    'source_account_id' => $sourceAccount->id,
                    'target_account_id' => $targetAccount->id,
                    'status' => 'rejected',
                    'rejection_reason' => 'Target customer is blocked',
                ]);
                return redirect()->back()->with('error', 'Target customer is blocked. Transaction rejected.');
            }

            // Validate both accounts are active
            if ($sourceAccount->status !== 'active') {
                $transaction = Transaction::create([
                    'type' => 'transfer',
                    'amount' => $request->amount,
                    'source_account_id' => $sourceAccount->id,
                    'target_account_id' => $targetAccount->id,
                    'status' => 'rejected',
                    'rejection_reason' => 'Source account is not active',
                ]);
                return redirect()->back()->with('error', 'Source account is not active. Transaction rejected.');
            }

            if ($targetAccount->status !== 'active') {
                $transaction = Transaction::create([
                    'type' => 'transfer',
                    'amount' => $request->amount,
                    'source_account_id' => $sourceAccount->id,
                    'target_account_id' => $targetAccount->id,
                    'status' => 'rejected',
                    'rejection_reason' => 'Target account is not active',
                ]);
                return redirect()->back()->with('error', 'Target account is not active. Transaction rejected.');
            }

            // Validate same currency
            if ($sourceAccount->currency !== $targetAccount->currency) {
                $transaction = Transaction::create([
                    'type' => 'transfer',
                    'amount' => $request->amount,
                    'source_account_id' => $sourceAccount->id,
                    'target_account_id' => $targetAccount->id,
                    'status' => 'rejected',
                    'rejection_reason' => 'Accounts must use the same currency',
                ]);
                return redirect()->back()->with('error', 'Accounts must use the same currency. Transaction rejected.');
            }

            // Validate amount is greater than zero
            if ($request->amount <= 0) {
                $transaction = Transaction::create([
                    'type' => 'transfer',
                    'amount' => $request->amount,
                    'source_account_id' => $sourceAccount->id,
                    'target_account_id' => $targetAccount->id,
                    'status' => 'rejected',
                    'rejection_reason' => 'Amount must be greater than zero',
                ]);
                return redirect()->back()->with('error', 'Amount must be greater than zero.');
            }

            // Validate sufficient balance
            if ($sourceAccount->balance < $request->amount) {
                $transaction = Transaction::create([
                    'type' => 'transfer',
                    'amount' => $request->amount,
                    'source_account_id' => $sourceAccount->id,
                    'target_account_id' => $targetAccount->id,
                    'status' => 'rejected',
                    'rejection_reason' => 'Insufficient balance in source account',
                ]);
                return redirect()->back()->with('error', 'Insufficient balance. Transaction rejected.');
            }

            // Create transaction
            $transaction = Transaction::create([
                'type' => 'transfer',
                'amount' => $request->amount,
                'source_account_id' => $sourceAccount->id,
                'target_account_id' => $targetAccount->id,
                'status' => 'success',
            ]);

            // Update account balances atomically
            $sourceAccount->decrement('balance', $request->amount);
            $targetAccount->increment('balance', $request->amount);

            return redirect()->route('transactions.index')->with('success', 'Transfer successful!');
        });
    }
}
