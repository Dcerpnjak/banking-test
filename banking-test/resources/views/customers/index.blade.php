<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bank System - Customers</title>
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
</head>
<body>
    <div class="container">
        <div style="margin-bottom: 20px;">
            <a href="{{ route('home') }}" style="color: #666; text-decoration: none;">← Home</a>
        </div>
        <h1>Bank System - Customer Management</h1>

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

        <div class="form-section">
            <h2>Add New Customer</h2>
            <form action="{{ route('customers.store') }}" method="POST">
                @csrf
                <div class="form-group">
                    <label for="name">Customer Name</label>
                    <input type="text" id="name" name="name" required value="{{ old('name') }}">
                    @error('name')
                        <div style="color: red; font-size: 12px; margin-top: 5px;">{{ $message }}</div>
                    @enderror
                </div>
                <div class="form-group">
                    <label for="status">Status</label>
                    <select id="status" name="status">
                        <option value="active" {{ old('status') == 'active' ? 'selected' : '' }}>Active</option>
                        <option value="blocked" {{ old('status') == 'blocked' ? 'selected' : '' }}>Blocked</option>
                        <option value="closed" {{ old('status') == 'closed' ? 'selected' : '' }}>Closed</option>
                    </select>
                </div>
                <button type="submit" class="btn">Add Customer</button>
            </form>
        </div>

        <div class="customers-section">
            <h2>Customers List</h2>
            <div class="customers-list">
                @if($customers->count() > 0)
                    @foreach($customers as $customer)
                        <div class="customer-item">
                            <div class="customer-info">
                                <div class="customer-name">Customer ID: <a href="{{ route('customers.show', $customer) }}" class="link">{{ $customer->id }}</a> | Customer Name: <a href="{{ route('customers.show', $customer) }}" class="link">{{ $customer->name }}</a></div>
                                <span class="customer-status status-{{ $customer->status }}">
                                    Customer status: {{ ucfirst($customer->status) }}
                                </span>
                            </div>
                            <div class="customer-actions">
                                @if($customer->status === 'active')
                                    <form action="{{ route('customers.block', $customer) }}" method="POST" style="display: inline;">
                                        @csrf
                                        <button type="submit" class="btn btn-warning">Block Customer</button>
                                    </form>
                                @endif
                            </div>
                        </div>
                    @endforeach
                @else
                    <div class="empty-state">
                        No customers found. Add your first customer above.
                    </div>
                @endif
            </div>
        </div>
    </div>
</body>
</html>

