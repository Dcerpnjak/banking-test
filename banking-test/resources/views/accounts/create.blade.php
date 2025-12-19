<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Open New Account</title>
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
</head>
<body>
    <div class="container form">
        <div style="margin-bottom: 20px;">
            <a href="{{ route('home') }}" style="color: #666; text-decoration: none;">← Home</a>
        </div>
        <h1>Open New Account</h1>

        <form action="{{ route('accounts.store') }}" method="POST">
            @csrf
            <div class="form-group">
                <label for="customer_id">Customer</label>
                <select id="customer_id" name="customer_id" required>
                    <option value="">Select a customer</option>
                    @foreach($customers as $customer)
                        <option value="{{ $customer->id }}" {{ old('customer_id') == $customer->id ? 'selected' : '' }}>
                            {{ $customer->name }} (ID: {{ $customer->id }}) - {{ ucfirst($customer->status) }}
                        </option>
                    @endforeach
                </select>
                @error('customer_id')
                    <div class="error">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label for="account_type">Account Type</label>
                <select id="account_type" name="account_type" required>
                    <option value="">Select account type</option>
                    <option value="personal" {{ old('account_type') == 'personal' ? 'selected' : '' }}>Personal</option>
                    <option value="savings" {{ old('account_type') == 'savings' ? 'selected' : '' }}>Savings</option>
                    <option value="business" {{ old('account_type') == 'business' ? 'selected' : '' }}>Business</option>
                </select>
                @error('account_type')
                    <div class="error">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label for="currency">Currency</label>
                <input type="text" id="currency" name="currency" value="{{ old('currency', 'EUR') }}" maxlength="3">
                @error('currency')
                    <div class="error">{{ $message }}</div>
                @enderror
            </div>

            <div>
                <a href="{{ route('accounts.index') }}" class="btn btn-secondary">Cancel</a>
                <button type="submit" class="btn">Open Account</button>
            </div>
        </form>
    </div>
</body>
</html>

