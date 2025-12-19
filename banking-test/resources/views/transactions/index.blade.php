<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bank System - Transactions</title>
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
</head>
<body>
    <div class="container transactions">
        <div style="margin-bottom: 20px;">
            <a href="{{ route('home') }}" style="color: #666; text-decoration: none;">← Home</a>
        </div>
        <h1>Bank System - Transactions</h1>

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

        <div class="header-actions">
            <a href="{{ route('transactions.deposit') }}" class="btn">Deposit</a>
            <a href="{{ route('transactions.withdraw') }}" class="btn">Withdraw</a>
            <a href="{{ route('transactions.transfer') }}" class="btn">Transfer</a>
        </div>

        <div class="transactions-list">
            @if($transactions->count() > 0)
                @foreach($transactions as $transaction)
                    <div class="transaction-item">
                        <div class="transaction-header">
                            <div>
                                <span class="transaction-type">{{ ucfirst($transaction->type) }}</span>
                                <span class="transaction-amount {{ $transaction->status === 'rejected' ? 'rejected' : '' }}">
                                    {{ number_format($transaction->amount, 2) }}
                                    @if($transaction->targetAccount)
                                        {{ $transaction->targetAccount->currency }}
                                    @elseif($transaction->sourceAccount)
                                        {{ $transaction->sourceAccount->currency }}
                                    @endif
                                </span>
                            </div>
                            <span class="transaction-status status-{{ $transaction->status }}">
                                {{ ucfirst($transaction->status) }}
                            </span>
                        </div>
                        <div class="transaction-info">
                            <strong>Transaction ID:</strong> {{ $transaction->id }}
                        </div>
                        <div class="transaction-info">
                            <strong>Date:</strong> {{ $transaction->created_at->format('Y-m-d H:i:s') }}
                        </div>
                        @if($transaction->type === 'deposit' && $transaction->targetAccount)
                            <div class="transaction-info">
                                <strong>To Account:</strong> <a href="{{ route('accounts.show', $transaction->targetAccount) }}" class="link">{{ $transaction->targetAccount->id }}</a> (<a href="{{ route('customers.show', $transaction->targetAccount->customer) }}" class="link">{{ $transaction->targetAccount->customer->name }}</a>)
                            </div>
                        @elseif($transaction->type === 'withdrawal' && $transaction->sourceAccount)
                            <div class="transaction-info">
                                <strong>From Account:</strong> <a href="{{ route('accounts.show', $transaction->sourceAccount) }}" class="link">{{ $transaction->sourceAccount->id }}</a> (<a href="{{ route('customers.show', $transaction->sourceAccount->customer) }}" class="link">{{ $transaction->sourceAccount->customer->name }}</a>)
                            </div>
                        @elseif($transaction->type === 'transfer')
                            <div class="transaction-info">
                                <strong>From Account:</strong> <a href="{{ route('accounts.show', $transaction->sourceAccount) }}" class="link">{{ $transaction->sourceAccount->id }}</a> (<a href="{{ route('customers.show', $transaction->sourceAccount->customer) }}" class="link">{{ $transaction->sourceAccount->customer->name }}</a>)
                            </div>
                            <div class="transaction-info">
                                <strong>To Account:</strong> <a href="{{ route('accounts.show', $transaction->targetAccount) }}" class="link">{{ $transaction->targetAccount->id }}</a> (<a href="{{ route('customers.show', $transaction->targetAccount->customer) }}" class="link">{{ $transaction->targetAccount->customer->name }}</a>)
                            </div>
                        @endif
                        @if($transaction->rejection_reason)
                            <div class="transaction-info" style="color: #721c24; margin-top: 5px;">
                                <strong>Rejection Reason:</strong> {{ $transaction->rejection_reason }}
                            </div>
                        @endif
                    </div>
                @endforeach
            @else
                <div class="empty-state">
                    No transactions found. Create your first transaction above.
                </div>
            @endif
        </div>
    </div>
</body>
</html>

