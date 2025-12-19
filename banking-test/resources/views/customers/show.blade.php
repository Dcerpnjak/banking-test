<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Customer Details</title>
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
        .customer-details {
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
        .customer-status {
            display: inline-block;
            padding: 4px 10px;
            border-radius: 12px;
            font-size: 12px;
        }
        .status-active { background: #d4edda; color: #155724; }
        .status-blocked { background: #f8d7da; color: #721c24; }
        .status-closed { background: #e2e3e5; color: #383d41; }
        .customer-actions {
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
        .accounts-section {
            margin-top: 30px;
        }
        .account-item {
            border: 1px solid #ddd;
            padding: 15px;
            margin-bottom: 10px;
            border-radius: 4px;
        }
        .account-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 10px;
        }
        .account-info {
            margin-bottom: 5px;
        }
        .account-status {
            display: inline-block;
            padding: 4px 10px;
            border-radius: 12px;
            font-size: 12px;
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
        <h1>Customer Details</h1>

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

        <div class="customer-details">
            <div class="detail-row">
                <span class="detail-label">Customer ID:</span>
                {{ $customer->id }}
            </div>
            <div class="detail-row">
                <span class="detail-label">Name:</span>
                {{ $customer->name }}
            </div>
            <div class="detail-row">
                <span class="detail-label">Status:</span>
                <span class="customer-status status-{{ $customer->status }}">
                    {{ ucfirst($customer->status) }}
                </span>
            </div>
        </div>

        <div class="customer-actions">
            <a href="{{ route('customers.index') }}" class="btn btn-secondary">Back to Customers</a>
            @if($customer->status === 'active')
                <form action="{{ route('customers.block', $customer) }}" method="POST" style="display: inline;">
                    @csrf
                    <button type="submit" class="btn btn-warning">Block Customer</button>
                </form>
            @endif
        </div>

        <div class="accounts-section">
            <h2>Accounts</h2>
            @if($customer->accounts->count() > 0)
                @foreach($customer->accounts as $account)
                    <div class="account-item">
                        <div class="account-header">
                            <div>
                                <strong>Account ID:</strong> <a href="{{ route('accounts.show', $account) }}" class="link">{{ $account->id }}</a>
                            </div>
                            <span class="account-status status-{{ $account->status }}">
                                {{ ucfirst($account->status) }}
                            </span>
                        </div>
                        <div class="account-info">
                            <strong>Type:</strong> {{ ucfirst($account->account_type) }}
                        </div>
                        <div class="account-info">
                            <strong>Currency:</strong> {{ $account->currency }}
                        </div>
                        <div class="account-info">
                            <strong>Balance:</strong> {{ number_format($account->balance, 2) }} {{ $account->currency }}
                        </div>
                    </div>
                @endforeach
            @else
                <div class="empty-state">
                    No accounts found for this customer.
                </div>
            @endif
        </div>
    </div>
</body>
</html>

