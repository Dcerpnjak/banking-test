<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bank System - Home</title>
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
</head>
<body>
    <div class="container home">
        <h1 class="home">Bank System</h1>
        
        <div class="menu">
            <a href="{{ route('customers.index') }}" class="menu-item">
                Customers
            </a>
            <a href="{{ route('accounts.index') }}" class="menu-item accounts">
                Accounts
            </a>
            <a href="{{ route('transactions.index') }}" class="menu-item transactions">
                Transactions
            </a>
        </div>
    </div>
</body>
</html>

