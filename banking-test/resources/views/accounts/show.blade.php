<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Account Details</title>
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
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

