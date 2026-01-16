<div class="mt-3">
    @auth
        <form action="{{ route('cart.add', $product->id) }}" method="POST">
            @csrf
            <div class="row g-2">
                @if(isset($is_detail_page)) {{-- يظهر فقط في صفحة التفاصيل --}}
                <div class="col-4">
                    <select name="size" class="form-select rounded-pill">
                        <option value="S">S</option>
                        <option value="M" selected>M</option>
                        <option value="L">L</option>
                        <option value="XL">XL</option>
                    </select>
                </div>
                @endif
                <div class="col">
                    <button type="submit" class="btn btn-primary rounded-pill w-100 shadow-sm">
                        <i class="fas fa-shopping-basket me-1"></i> إضافة إلى عربة التسوق
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
