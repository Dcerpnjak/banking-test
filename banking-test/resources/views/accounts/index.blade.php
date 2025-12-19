<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bank System - Accounts</title>
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
        .account-id {
            font-weight: bold;
            color: #666;
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
        .status-active { background: #d4edda; color: #155724; }
        .status-blocked { background: #f8d7da; color: #721c24; }
        .status-closed { background: #e2e3e5; color: #383d41; }
        .account-actions {
            margin-top: 10px;
        }
        .account-actions a, .account-actions form {
            display: inline-block;
            margin-right: 10px;
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

        <div style="margin-bottom: 20px;">
            <a href="{{ route('home') }}" style="color: #666; text-decoration: none;">← Home</a>
        </div>
        <div class="header-actions">
            <a href="{{ route('accounts.create') }}" class="btn">Open New Account</a>
        </div>

        <div class="accounts-list">
            @if($accounts->count() > 0)
                @foreach($accounts as $account)
                    <div class="account-item">
                        <div class="account-header">
                            <div class="account-id">Account ID: {{ $account->id }}</div>
                            <span class="account-status status-{{ $account->status }}">
                                {{ ucfirst($account->status) }}
                            </span>
                        </div>
                        <div class="account-info">
                            <strong>Customer:</strong> {{ $account->customer->name }} (ID: {{ $account->customer->id }})
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

