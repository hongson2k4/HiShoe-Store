<div class="product-add-to-cart mt-4">
    <form action="{{ route('cart.add') }}" method="POST">
        @csrf
        <input type="hidden" name="product_id" value="{{ $product->id }}">
        
        <div class="mb-3">
            <label for="size" class="form-label">Kích cỡ</label>
            <div class="d-flex flex-wrap gap-2">
                @foreach($sizes as $size)
                @php
                    $variantExists = $product->productVariants()
                        ->where('size_id', $size->id)
                        ->exists();
                @endphp
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="size_id" 
                        id="size-{{ $size->id }}" 
                        value="{{ $size->id }}" 
                        {{ $loop->first ? 'checked' : '' }}
                        {{ !$variantExists ? 'disabled' : '' }}>
                    <label class="form-check-label {{ !$variantExists ? 'text-muted' : '' }}" for="size-{{ $size->id }}">
                        {{ $size->name }}
                    </label>
                </div>
                @endforeach
            </div>
        </div>
        
        <div class="mb-3">
            <label for="color" class="form-label">Màu sắc</label>
            <div class="d-flex flex-wrap gap-2">
                @foreach($colors as $color)
                @php
                    $variantExists = $product->productVariants()
                        ->where('color_id', $color->id)
                        ->exists();
                @endphp
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="color_id" 
                        id="color-{{ $color->id }}" 
                        value="{{ $color->id }}" 
                        {{ $loop->first ? 'checked' : '' }}
                        {{ !$variantExists ? 'disabled' : '' }}>
                    <label class="form-check-label {{ !$variantExists ? 'text-muted' : '' }}" for="color-{{ $color->id }}">
                        {{ $color->name }}
                    </label>
                </div>
                @endforeach
            </div>
        </div>
        
        <div class="mb-4">
            <label for="quantity" class="form-label">Số lượng</label>
            <div class="input-group" style="width: 150px;">
                <button type="button" class="btn btn-outline-secondary quantity-btn" data-action="decrease">-</button>
                <input type="number" name="quantity" id="quantity" class="form-control text-center" value="1" min="1" max="99">
                <button type="button" class="btn btn-outline-secondary quantity-btn" data-action="increase">+</button>
            </div>
        </div>
        
        <div class="d-flex gap-2">
            <button type="submit" class="btn btn-primary">
                <i class="fa fa-shopping-cart me-2"></i>Thêm vào giỏ hàng
            </button>
            <button type="button" class="btn btn-outline-primary add-to-wishlist" data-product-id="{{ $product->id }}">
                <i class="fa fa-heart"></i>
            </button>
        </div>
    </form>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const quantityInput = document.getElementById('quantity');
        const quantityButtons = document.querySelectorAll('.quantity-btn');
        
        quantityButtons.forEach(button => {
            button.addEventListener('click', function() {
                const action = this.getAttribute('data-action');
                let currentValue = parseInt(quantityInput.value);
                
                if (action === 'increase') {
                    if (currentValue < 99) {
                        quantityInput.value = currentValue + 1;
                    }
                } else {
                    if (currentValue > 1) {
                        quantityInput.value = currentValue - 1;
                    }
                }
            });
        });
    });
</script>