@extends('layouts.master')

@section('title', 'Katalog Produk')

@section('content')
@component('components.breadcrumb')
    @slot('li_1') Shop @endslot
    @slot('title') Katalog Produk @endslot
@endcomponent

<div class="row g-4">
    @forelse($products as $product)
        <div class="col-sm-6 col-md-4 col-lg-3">
            <div class="card shadow-sm border-0 h-100 hover-card">
                <div class="position-relative">
                    <img src="{{ $product->image ? asset('storage/'.$product->image) : asset('build/images/default.jpg') }}"
                         class="card-img-top rounded-top" alt="{{ $product->name }}"
                         style="height: 200px; object-fit: cover;">
                    <span class="badge bg-success position-absolute top-0 start-0 m-2">Stok: {{ $product->stock }}</span>
                </div>
                <div class="card-body d-flex flex-column">
                    <h5 class="card-title mb-2">{{ $product->name }}</h5>
                    <p class="card-text text-muted small flex-grow-1">{{ Str::limit($product->description, 70) }}</p>
                    <div class="d-flex justify-content-between align-items-center mt-3">
                        <h5 class="text-danger mb-0">Rp {{ number_format($product->price, 0, ',', '.') }}</h5>
                        <a href="{{ route('shop.product.show', $product->id) }}" 
                           class="btn btn-outline-primary btn-sm rounded-pill px-3">
                            <i class="ri-eye-line me-1"></i> Detail
                        </a>
                    </div>
                </div>
            </div>
        </div>
    @empty
        <div class="text-center py-5">
            <p class="text-muted">Belum ada produk tersedia.</p>
        </div>
    @endforelse
</div>

@endsection

@section('css')
<style>
.hover-card {
    transition: all 0.3s ease-in-out;
}
.hover-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 8px 20px rgba(0,0,0,0.1);
}
.card-body h5 {
    font-weight: 600;
}
</style>
@endsection
