@extends('client.layout.main')

@section('content')
<div class="container my-5">
    <h1 class="text-center mb-4">Your Cart</h1>
    @if($cartItems->isEmpty())
        <div class="alert alert-warning text-center" role="alert">
            Your cart is empty. <a href="{{ route('shop') }}" class="alert-link">Start shopping now!</a>
        </div>
    @else
        <div class="table-responsive">
            <table class="table table-bordered table-hover">
                <thead class="thead-dark">
                    <tr>
                        <th>Product Name</th>
                        <th>Color</th>
                        <th>Size</th>
                        <th>Quantity</th>
                        <th>Price</th>
                        <th>Total</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($cartItems as $item)
                        <tr>
                            <td>{{ $item->product->name }}</td>
                            <td>{{ $item->variant->color->name }}</td>
                            <td>{{ $item->variant->size->name }}</td>
                            <td>
                                <form action="{{ route('cart.update', $item->id) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('PATCH')
                                    <div class="input-group">
                                        <button type="submit" name="action" value="decrease" class="btn btn-outline-secondary btn-sm">-</button>
                                        <input type="text" name="quantity" value="{{ $item->quantity }}" class="form-control text-center" readonly style="max-width: 50px;">
                                        <button type="submit" name="action" value="increase" class="btn btn-outline-secondary btn-sm">+</button>
                                    </div>
                                </form>
                            </td>
                            <td>${{ number_format($item->variant->price, 2) }}</td>
                            <td>${{ number_format($item->variant->price * $item->quantity, 2) }}</td>
                            <td>
                                <form action="{{ route('cart.delete', $item->id) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm">Remove</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="d-flex justify-content-between align-items-center mt-4">
            <a href="{{ route('shop') }}" class="btn btn-outline-primary">Continue Shopping</a>
            
        </div>
    @endif
</div>
@endsection