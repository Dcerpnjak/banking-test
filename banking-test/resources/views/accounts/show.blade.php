<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Account Details</title>
    <style>
        body {
            font-family: sans-serif;
            background: #f5f5f5;
            padding: 20px;
        }
        .container {
            max-width: 1000px;
            margin: 0 auto;
            background: white;
            padding: 30px;
            border-radius: 8px;
        }
        h1 {
            margin-bottom: 20px;
        }
        .account-details {
            background: #f9f9f9;
            padding: 20px;
            border-radius: 6px;
            margin-bottom: 30px;
        }
        .detail-row {
            margin-bottom: 10px;
        }
        .detail-label {
            font-weight: bold;
            display: inline-block;
            width: 150px;
        }
        .account-status {
            display: inline-block;
            padding: 4px 10px;
            border-radius: 12px;
            font-size: 12px;
        }
        .status-active { background: #d4edda; color: #155724; }
        .status-blocked { background: #f8d7da; color: #721c24; }
        .status-closed { background: #e2e3e5; color: #383d41; }
        .balance {
            font-size: 24px;
            font-weight: bold;
            color: #4CAF50;
            margin: 20px 0;
        }
        .account-actions {
            margin-top: 20px;
        }
        .btn, button.btn {
            background: #4CAF50;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            text-decoration: none;
            display: inline-block;
            font-size: 14px;
            transition: background 0.3s;
            margin-right: 10px;
        }
        .btn:hover, button.btn:hover {
            background: #45a049;
        }
        .btn-secondary {
            background: #6c757d;
        }
        .btn-secondary:hover {
            background: #5a6268;
        }
        .btn-warning {
            background: #ffc107;
            color: #000;
        }
        .btn-warning:hover {
            background: #e0a800;
        }
        .btn-danger {
            background: #dc3545;
        }
        .btn-danger:hover {
            background: #c82333;
        }
        .transactions-section {
            margin-top: 30px;
        }
        .transaction-item {
            border: 1px solid #ddd;
            padding: 15px;
            margin-bottom: 10px;
            border-radius: 4px;
        }
        .transaction-header {
            display: flex;
            justify-content: space-between;
            margin-bottom: 10px;
        }
        .transaction-type {
            font-weight: bold;
        }
        .transaction-amount {
            font-size: 18px;
            font-weight: bold;
        }
        .transaction-success {
            color: #155724;
        }
        .transaction-rejected {
            color: #721c24;
        }
        .alert {
            padding: 12px;
            margin-bottom: 20px;
            border-radius: 4px;
        }
        .alert-success {
            background: #d4edda;
            color: #155724;
        }
        .alert-error {
            background: #f8d7da;
            color: #721c24;
        }
        .empty-state {
            text-align: center;
            padding: 40px;
            color: #999;
        }
        a.link {
            color: #2196F3;
            text-decoration: underline;
        }
        a.link:hover {
            color: #1976D2;
        }
    </style>
</head>
<body>
    <div class="container">
        <div style="margin-bottom: 20px;">
            <a href="{{ route('home') }}" style="color: #666; text-decoration: none;">← Home</a>
        </div>
        <h1>Account Details</h1>

        @if(session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-error">
                {{ session('error') }}
            </div>
        @endif

        <div class="account-details">
            <div class="detail-row">
                <span class="detail-label">Account ID:</span>
                {{ $account->id }}
            </div>
            <div class="detail-row">
                <span class="detail-label">Customer:</span>
                <a href="{{ route('customers.show', $account->customer) }}" class="link">{{ $account->customer->name }}</a> (ID: <a href="{{ route('customers.show', $account->customer) }}" class="link">{{ $account->customer->id }}</a>)
            </div>
            <div class="detail-row">
                <span class="detail-label">Account Type:</span>
                {{ ucfirst($account->account_type) }}
            </div>
            <div class="detail-row">
                <span class="detail-label">Currency:</span>
                {{ $account->currency }}
            </div>
            <div class="detail-row">
                <span class="detail-label">Status:</span>
                <span class="account-status status-{{ $account->status }}">
                    {{ ucfirst($account->status) }}
                </span>
            </div>
            <div class="balance">
                Balance: {{ number_format($account->balance, 2) }} {{ $account->currency }}
            </div>
        </div>

        <div class="account-actions">
            <a href="{{ route('accounts.index') }}" class="btn btn-secondary">Back to Accounts</a>
            @if($account->status === 'active')
                <form action="{{ route('accounts.block', $account) }}" method="POST" style="display: inline;">
                    @csrf
                    <button type="submit" class="btn btn-warning">Block Account</button>
                </form>
            @endif
            @if($account->status !== 'closed' && $account->balance == 0)
                <form action="{{ route('accounts.close', $account) }}" method="POST" style="display: inline;">
                    @csrf
                    <button type="submit" class="btn btn-danger">Close Account</button>
                </form>
            @endif
        </div>

        <div class="transactions-section">
            <h2>Transaction History</h2>
            @if($transactions->count() > 0)
                @foreach($transactions as $transaction)
                    <div class="transaction-item">
                        <div class="transaction-header">
                            <div>
                                <span class="transaction-type">{{ ucfirst($transaction->type) }}</span>
                                <span class="transaction-amount {{ $transaction->status === 'success' ? 'transaction-success' : 'transaction-rejected' }}">
                                    {{ $transaction->status === 'success' ? '+' : '' }}{{ number_format($transaction->amount, 2) }} {{ $account->currency }}
                                </span>
                            </div>
                            <div>
                                <span class="account-status status-{{ $transaction->status }}">
                                    {{ ucfirst($transaction->status) }}
                                </span>
                            </div>
                        </div>
                        <div style="font-size: 12px; color: #666; margin-top: 5px;">
                            Date: {{ $transaction->created_at->format('Y-m-d H:i:s') }}
                        </div>
                        @if($transaction->rejection_reason)
                            <div style="color: #721c24; margin-top: 5px; font-size: 12px;">
                                Reason: {{ $transaction->rejection_reason }}
                            </div>
                        @endif
                        @if($transaction->type === 'transfer')
                            <div style="font-size: 12px; color: #666; margin-top: 5px;">
                                @if($transaction->source_account_id == $account->id && $transaction->targetAccount)
                                    To Account: <a href="{{ route('accounts.show', $transaction->targetAccount) }}" class="link">{{ $transaction->targetAccount->id }}</a>
                                @elseif($transaction->target_account_id == $account->id && $transaction->sourceAccount)
                                    From Account: <a href="{{ route('accounts.show', $transaction->sourceAccount) }}" class="link">{{ $transaction->sourceAccount->id }}</a>
                                @endif
                            </div>
                        @endif
                    </div>
                @endforeach
            @else
                <div class="empty-state">
                    No transactions found for this account.
                </div>
            @endif
        </div>
    </div>
</body>
</html>

