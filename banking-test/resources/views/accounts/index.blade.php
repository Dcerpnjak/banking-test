<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bank System - Accounts</title>
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
</head>
<body>
    <div class="container">
        
        <div style="margin-bottom: 20px;">
            <a href="{{ route('home') }}" style="color: #666; text-decoration: none;">← Home</a>
        </div>

        <h1>Bank System - Account Management</h1>

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
            <a href="{{ route('accounts.create') }}" class="btn">Open New Account</a>
        </div>

        <div class="accounts-list">
            @if($accounts->count() > 0)
                @foreach($accounts as $account)
                    <div class="account-item">
                        <div class="account-header">
                            <div class="account-id">Account ID: <a href="{{ route('accounts.show', $account) }}" class="link">{{ $account->id }}</a></div>
                            <span class="account-status status-{{ $account->status }}">
                                {{ ucfirst($account->status) }}
                            </span>
                        </div>
                        <div class="account-info">
                            <strong>Customer:</strong> <a href="{{ route('customers.show', $account->customer) }}" class="link">{{ $account->customer->name }}</a> (ID: <a href="{{ route('customers.show', $account->customer) }}" class="link">{{ $account->customer->id }}</a>)
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
                        <div class="account-actions">
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
                    </div>
                @endforeach
            @else
                <div class="empty-state">
                    No accounts found.
                </div>
            @endif
        </div>
    </div>
</body>
</html>

