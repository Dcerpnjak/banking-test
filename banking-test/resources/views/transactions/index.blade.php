<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bank System - Transactions</title>
    <style>
        body {
            font-family: sans-serif;
            background: #f5f5f5;
            padding: 20px;
        }
        .container {
            max-width: 1200px;
            margin: 0 auto;
            background: white;
            padding: 30px;
            border-radius: 8px;
        }
        h1 {
            margin-bottom: 20px;
        }
        .header-actions {
            margin-bottom: 20px;
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
        .transaction-item {
            border: 1px solid #ddd;
            padding: 15px;
            margin-bottom: 10px;
            border-radius: 4px;
        }
        .transaction-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 10px;
        }
        .transaction-type {
            font-weight: bold;
            font-size: 16px;
        }
        .transaction-status {
            display: inline-block;
            padding: 4px 10px;
            border-radius: 12px;
            font-size: 12px;
        }
        .status-success {
            background: #d4edda;
            color: #155724;
        }
        .status-rejected {
            background: #f8d7da;
            color: #721c24;
        }
        .transaction-info {
            margin-bottom: 5px;
            font-size: 14px;
        }
        .transaction-amount {
            font-size: 18px;
            font-weight: bold;
            color: #4CAF50;
        }
        .transaction-amount.rejected {
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
    </style>
</head>
<body>
    <div class="container">
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
                                <strong>To Account:</strong> {{ $transaction->targetAccount->id }} ({{ $transaction->targetAccount->customer->name }})
                            </div>
                        @elseif($transaction->type === 'withdrawal' && $transaction->sourceAccount)
                            <div class="transaction-info">
                                <strong>From Account:</strong> {{ $transaction->sourceAccount->id }} ({{ $transaction->sourceAccount->customer->name }})
                            </div>
                        @elseif($transaction->type === 'transfer')
                            <div class="transaction-info">
                                <strong>From Account:</strong> {{ $transaction->sourceAccount->id }} ({{ $transaction->sourceAccount->customer->name }})
                            </div>
                            <div class="transaction-info">
                                <strong>To Account:</strong> {{ $transaction->targetAccount->id }} ({{ $transaction->targetAccount->customer->name }})
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

