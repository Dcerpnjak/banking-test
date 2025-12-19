<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Withdraw</title>
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
</head>
<body>
    <div class="container form">
        <div style="margin-bottom: 20px;">
            <a href="{{ route('home') }}" style="color: #666; text-decoration: none;">← Home</a>
        </div>
        <h1>Withdraw</h1>

        @if(session('error'))
            <div class="alert alert-error">
                {{ session('error') }}
            </div>
        @endif

        <form action="{{ route('transactions.withdraw.store') }}" method="POST">
            @csrf
            <div class="form-group">
                <label for="account_id">Account</label>
                <select id="account_id" name="account_id" required>
                    <option value="">Select an account</option>
                    @foreach($accounts as $account)
                        <option value="{{ $account->id }}" {{ old('account_id') == $account->id ? 'selected' : '' }}>
                            Account ID: {{ $account->id }} - {{ $account->customer->name }} (Balance: {{ number_format($account->balance, 2) }} {{ $account->currency }})
                        </option>
                    @endforeach
                </select>
                @error('account_id')
                    <div class="error">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label for="amount">Amount</label>
                <input type="number" id="amount" name="amount" step="0.01" min="0.01" required value="{{ old('amount') }}">
                @error('amount')
                    <div class="error">{{ $message }}</div>
                @enderror
            </div>

            <div>
                <a href="{{ route('transactions.index') }}" class="btn btn-secondary">Cancel</a>
                <button type="submit" class="btn">Withdraw</button>
            </div>
        </form>
    </div>
</body>
</html>

