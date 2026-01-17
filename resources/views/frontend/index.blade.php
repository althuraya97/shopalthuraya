<div class="mt-3">
    @auth
       <form action="{{ route('cart.add') }}" method="POST">
    @csrf
    <input type="hidden" name="product_id" value="{{ $product->id }}">
    <input type="hidden" name="quantity" value="1"> {{-- كمية افتراضية --}}

    <div class="row g-2">
        @if(isset($is_detail_page))
        <div class="col-4">
            <select name="size" class="form-select rounded-pill">
                @foreach($product->sizes as $size) {{-- تأكد أن الحقل مصفوفة في الموديل --}}
                    <option value="{{ $size }}">{{ $size }}</option>
                @endforeach
            </select>
        </div>
        @endif
        <div class="col">
            <button type="submit" class="btn btn-primary rounded-pill w-100 shadow-sm">
                <i class="fas fa-shopping-basket me-1"></i> إضافة للسلة
            </button>
        </div>
    </div>
</form>
    @else
        <a href="{{ route('customer.login') }}" class="btn btn-outline-primary rounded-pill w-100">
            <i class="fas fa-sign-in-alt me-1"></i> سجل دخول للإضافة للسلة
        </a>
    @endauth
</div>
