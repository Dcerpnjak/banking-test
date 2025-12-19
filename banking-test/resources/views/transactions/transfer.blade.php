<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Transfer</title>
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
</head>
<body>
    <div class="container form">
        <div style="margin-bottom: 20px;">
            <a href="{{ route('home') }}" style="color: #666; text-decoration: none;">← Home</a>
        </div>
        <h1>Transfer</h1>

        @if(session('error'))
            <div class="alert alert-error">
                {{ session('error') }}
            </div>
        @endif

        <form action="{{ route('transactions.transfer.store') }}" method="POST">
            @csrf
            <div class="form-group">
                <label for="source_account_id">From Account</label>
                <select id="source_account_id" name="source_account_id" required>
                    <option value="">Select source account</option>
                    @foreach($accounts as $account)
                        <option value="{{ $account->id }}" {{ old('source_account_id') == $account->id ? 'selected' : '' }}>
                            Account ID: {{ $account->id }} - {{ $account->customer->name }} (Balance: {{ number_format($account->balance, 2, ',', '.') }} {{ $account->currency }})
                        </option>
                    @endforeach
                </select>
                @error('source_account_id')
                    <div class="error">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label for="target_account_id">To Account</label>
                <select id="target_account_id" name="target_account_id" required>
                    <option value="">Select target account</option>
                    @foreach($accounts as $account)
                        <option value="{{ $account->id }}" {{ old('target_account_id') == $account->id ? 'selected' : '' }}>
                            Account ID: {{ $account->id }} - {{ $account->customer->name }} ({{ $account->currency }})
                        </option>
                    @endforeach
                </select>
                @error('target_account_id')
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
                <button type="submit" class="btn">Transfer</button>
            </div>
        </form>
    </div>
</body>
</html>

