<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bank System - Home</title>
    <style>
        body {
            font-family: sans-serif;
            background: #f5f5f5;
            padding: 20px;
        }
        .container {
            max-width: 800px;
            margin: 0 auto;
            background: white;
            padding: 40px;
            border-radius: 8px;
            text-align: center;
        }
        h1 {
            margin-bottom: 10px;
            color: #333;
        }
        .subtitle {
            color: #666;
            margin-bottom: 40px;
        }
        .menu {
            display: flex;
            gap: 20px;
            justify-content: center;
            flex-wrap: wrap;
        }
        .btn, button.btn, .menu-item {
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
        .btn:hover, button.btn:hover, .menu-item:hover {
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
        .menu-item {
            padding: 30px 40px;
            font-size: 18px;
            font-weight: bold;
            min-width: 200px;
        }
        .menu-item.accounts {
            background: #2196F3;
        }
        .menu-item.accounts:hover {
            background: #1976D2;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Bank System</h1>
        <p class="subtitle">Welcome to the Banking Management System</p>
        
        <div class="menu">
            <a href="{{ route('customers.index') }}" class="menu-item">
                Customers
            </a>
            <a href="{{ route('accounts.index') }}" class="menu-item accounts">
                Accounts
            </a>
        </div>
    </div>
</body>
</html>

