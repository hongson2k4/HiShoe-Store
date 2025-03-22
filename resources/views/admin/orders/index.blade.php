@extends('admin.layout.main')

@section('content')
<div class="container-fluid mt-4">
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h2 class="mb-0">Order Management</h2>
        </div>
        <div class="card-body">

            {{-- Search and Filter Form --}}
            <form action="{{ route('orders.index') }}" method="GET" class="mb-4">
                <div class="row g-3">
                    <div class="col-md-4">
                        <input type="text"
                            name="search"
                            class="form-control"
                            placeholder="Search by customer name"
                            value="{{ request('search') }}">
                    </div>
                    <div class="col-md-3">
                        <select name="status" class="form-select">
                            <option value="">All Statuses</option>
                            @foreach(\App\Models\Order::getStatusList() as $key => $value)
                            <option value="{{ $key }}" {{ request('status') === $key ? 'selected' : '' }}>
                                {{ $value }}
                            </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2">
                        <button type="submit" class="btn btn-outline-primary">
                            <i class="fas fa-filter me-1"></i> Apply Filter
                        </button>
                    </div>
                    <div class="col-md-3 text-end">
                        <a href="{{ route('orders.index') }}" class="btn btn-secondary">
                            <i class="fas fa-refresh me-1"></i> Reset Filters
                        </a>
                    </div>
                </div>
            </form>

            {{-- Brands Table --}}
            <div class="table-responsive">
                <table class="table table-striped table-hover">
                    <thead class="table-dark">
                        <tr>
                            <th>Order ID</th>
                            <th>Customer</th>
                            <th>Total Price</th>
                            <th>Status</th>
                            <th>Created At</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($orders as $order)
                        <tr>
                            <td>{{ $order->id }}</td>
                            <td>{{ $order->user->full_name }}</td>
                            <td>{{ number_format($order->total_price) }} VND</td>
                            <td>
                                <div class="order-status-badge">
                                    <span class="badge text-light {{ $order->getStatusBadgeClass() }}">
                                        {{ $order->status_text }}
                                    </span>
                                </div>
                            </td>
                            <td>{{ $order->created_at->format('d-m-Y H:i') }}</td>
                            <td>
                                <a href="{{ route('orders.show', $order->id) }}" class="btn btn-info btn-sm">
                                    View Details
                                </a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center">
                                <div class="alert alert-info">
                                    <i class="fas fa-info-circle me-2"></i>
                                    No order found!
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            {{-- Pagination --}}
            <div class="d-flex justify-content-between align-items-center">
                <div class="text-muted">
                    Showing {{ $orders->firstItem() }} to {{ $orders->lastItem() }}
                    of {{ $orders->total() }} entries
                </div>
                <div>
                    {{ $orders->appends(request()->query())->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection