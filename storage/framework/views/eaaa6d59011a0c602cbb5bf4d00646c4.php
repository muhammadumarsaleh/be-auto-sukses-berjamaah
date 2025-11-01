

<?php $__env->startSection('title', 'Katalog Produk'); ?>

<?php $__env->startSection('css'); ?>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

<style>
.product-card {
    border: none;
    border-radius: 15px;
    overflow: hidden;
    box-shadow: 0 4px 10px rgba(0,0,0,0.08);
    transition: all 0.3s ease;
    background: #fff;
}
.product-card:hover {
    transform: translateY(-6px);
    box-shadow: 0 8px 20px rgba(0,0,0,0.12);
}
.product-img {
    width: 100%;
    height: 220px;
    object-fit: cover;
    border-bottom: 1px solid #eee;
}
.product-body {
    padding: 15px;
}
.product-name {
    font-size: 1rem;
    font-weight: 600;
    color: #333;
}
.product-price {
    color: #16a34a;
    font-weight: 700;
    font-size: 1.1rem;
}
.btn-add {
    background: #16a34a;
    color: white;
    font-weight: 600;
    border: none;
}
.btn-add:hover {
    background: #138c3d;
}
</style>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<?php $__env->startComponent('components.breadcrumb'); ?>
    <?php $__env->slot('li_1'); ?> Shop <?php $__env->endSlot(); ?>
    <?php $__env->slot('title'); ?> Katalog Produk <?php $__env->endSlot(); ?>
<?php echo $__env->renderComponent(); ?>

<div class="container py-4">
    <div class="row g-4">
        <?php $__empty_1 = true; $__currentLoopData = $products; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $product): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
            <div class="col-md-4 col-lg-3 col-sm-6">
                <div class="product-card h-100">
<img
  src="<?php echo e($product->images->first() ? asset('storage/'.$product->images->first()->image_path) : asset('images/no-image.png')); ?>"
  class="product-img"
  alt="<?php echo e($product->name); ?>">

                    <div class="product-body d-flex flex-column">
                        <h5 class="product-name mb-2"><?php echo e(Str::limit($product->name, 40)); ?></h5>
                        <p class="product-price mb-3">Rp <?php echo e(number_format($product->price, 0, ',', '.')); ?></p>

                        <div class="mt-auto d-flex justify-content-between align-items-center">
                            <a href="<?php echo e(route('shop.show', $product->id)); ?>" class="btn btn-outline-secondary btn-sm">
                                <i class="ri-eye-line"></i> Detail
                            </a>
                            <button class="btn btn-add btn-sm btnAddToCart" data-id="<?php echo e($product->id); ?>">
                                <i class="ri-shopping-cart-2-line"></i> Tambah
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
            <div class="text-center py-5">
                <h5 class="text-muted">Belum ada produk tersedia.</h5>
            </div>
        <?php endif; ?>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('script'); ?>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script>
$(function(){
    $('.btnAddToCart').on('click', function(){
        let id = $(this).data('id');
        $.ajax({
            url: '/cart/add/' + id,
            method: 'POST',
            data: {
                _token: '<?php echo e(csrf_token()); ?>',
                quantity: 1
            },
            success: res => {
                alert('Produk berhasil ditambahkan ke keranjang!');
            },
            error: err => {
                alert('Gagal menambahkan ke keranjang!');
                console.error(err);
            }
        });
    });
});
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\corporate\resources\views/pages/shop/index.blade.php ENDPATH**/ ?>