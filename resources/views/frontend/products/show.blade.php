@extends('layouts.frontend')

@section('content')
<div class="container py-5" dir="rtl">
    <nav aria-label="breadcrumb" class="mb-4 text-right">
        <ol class="breadcrumb bg-transparent p-0">
            <li class="breadcrumb-item"><a href="{{ route('shop.index') }}" class="text-decoration-none">المتجر</a></li>
            <li class="breadcrumb-item">
                <a href="#" class="text-decoration-none">{{ $product->category->name ?? 'قسم عام' }}</a>
            </li>
            <li class="breadcrumb-item active" aria-current="page">{{ $product->name }}</li>
        </ol>
    </nav>

    <div class="row g-5 text-right">
        <div class="col-md-6">
            <div class="card border-0 shadow-sm rounded-4 overflow-hidden sticky-top" style="top: 20px;">
                <img src="{{ asset('storage/' . $product->image) }}"
                     class="img-fluid"
                     alt="{{ $product->name }}"
                     style="width: 100%; height: 600px; object-fit: cover;">
            </div>
        </div>

        <div class="col-md-6">
            <div class="product-details p-2">
                <h1 class="fw-bold text-dark mb-3">{{ $product->name }}</h1>

                <div class="mb-4">
                    <span class="text-primary fs-2 fw-bold">{{ number_format($product->price, 2) }} ر.س</span>
                    @if($product->stock <= 5 && $product->stock > 0)
                        <span class="badge bg-danger ms-2">بقي {{ $product->stock }} قطع فقط!</span>
                    @endif
                </div>

                <hr class="my-4 opacity-50">

                <div class="mb-4">
                    <h5 class="fw-bold mb-3"><i class="fas fa-align-left ms-2 text-muted"></i> وصف المنتج</h5>
                    <p class="text-secondary lh-lg">
                        {{ $product->description }}
                    </p>
                </div>

                <form action="{{ route('cart.add') }}" method="POST">
                    @csrf
                    <input type="hidden" name="product_id" value="{{ $product->id }}">

                    @if(!empty($product->sizes) && count($product->sizes) > 0)
                    <div class="mb-4">
                        <h5 class="fw-bold mb-3"><i class="fas fa-ruler-combined ms-2 text-muted"></i> اختر المقاس</h5>
                        <div class="d-flex flex-wrap gap-2 mt-2">
                            @foreach($product->sizes as $size)
                                <input type="radio" class="btn-check" name="size" id="size-{{ $size }}" value="{{ $size }}" required @if($loop->first) checked @endif>
                                <label class="btn btn-outline-dark px-4 py-2 rounded-pill" for="size-{{ $size }}">{{ $size }}</label>
                            @endforeach
                        </div>
                    </div>
                    @endif

                    <div class="mb-4 w-50">
                        <h5 class="fw-bold mb-3"><i class="fas fa-sort-numeric-up ms-2 text-muted"></i> الكمية</h5>
                        <input type="number" name="quantity" value="1" min="1" max="{{ $product->stock }}" class="form-control form-control-lg rounded-pill shadow-sm text-center" required>
                    </div>

                    @if($product->return_policy)
                    <div class="mb-4 p-3 bg-light rounded-3 border-start border-primary border-4 shadow-sm">
                        <h6 class="fw-bold mb-2 small"><i class="fas fa-undo ms-2"></i> سياسة الإرجاع</h6>
                        <p class="text-muted small mb-0">{{ $product->return_policy }}</p>
                    </div>
                    @endif

                    <div class="d-grid mt-5">
                        @if($product->stock > 0)
                            <button type="submit" class="btn btn-primary btn-lg rounded-pill shadow-lg py-3 transition-transform">
                                <i class="fas fa-cart-plus ms-2"></i> إضافة إلى السلة
                            </button>
                        @else
                            <button class="btn btn-secondary btn-lg rounded-pill disabled py-3">
                                نفدت الكمية حالياً
                            </button>
                        @endif
                    </div>
                </form>

            </div>
        </div>
    </div>
</div>

<style>
    .transition-transform:hover {
        transform: translateY(-3px);
    }
    .btn-check:checked + .btn-outline-dark {
        background-color: #0d6efd !important;
        border-color: #0d6efd !important;
        color: #fff !important;
    }
</style>
@endsection
