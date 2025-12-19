<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Customer Details</title>
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
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
            @if($customer->status !== 'closed' && $customer->accounts->where('status', '!=', 'closed')->count() === 0)
                <form action="{{ route('customers.close', $customer) }}" method="POST" style="display: inline;">
                    @csrf
                    <button type="submit" class="btn btn-danger">Close Customer</button>
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
                            <strong>Balance:</strong> {{ number_format($account->balance, 2, ',', '.') }} {{ $account->currency }}
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

