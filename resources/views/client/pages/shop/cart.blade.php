@extends('client.layout.main')

@section('content')
<div class="container">
    <h1>Your Cart</h1>
    @if($cartItems->isEmpty())
        <p>Your cart is empty.</p>
    @else
        <table class="table">
            <thead>
                <tr>
                    <th>Product Name</th>
                    <th>Color</th>
                    <th>Size</th>
                    <th>Quantity</th>
                    <th>Price</th>
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
                            <form action="{{ route('cart.update', $item->id) }}" method="POST">
                                @csrf
                                @method('PATCH')
                                <div class="input-group">
                                    <button type="submit" name="action" value="decrease" class="btn btn-secondary">-</button>
                                    <input type="text" name="quantity" value="{{ $item->quantity }}" class="form-control text-center" readonly>
                                    <button type="submit" name="action" value="increase" class="btn btn-secondary">+</button>
                                </div>
                            </form>
                        </td>
                        <td>{{ $item->variant->price * $item->quantity }}</td>
                        <td>
                            <a href="" class="btn btn-primary">Checkout</a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif
</div>
@endsection