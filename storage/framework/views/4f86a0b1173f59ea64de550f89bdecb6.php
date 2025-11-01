

<?php $__env->startSection('title', 'Katalog Produk'); ?>

<?php $__env->startSection('content'); ?>
<?php $__env->startComponent('components.breadcrumb'); ?>
    <?php $__env->slot('li_1'); ?> Shop <?php $__env->endSlot(); ?>
    <?php $__env->slot('title'); ?> Katalog Produk <?php $__env->endSlot(); ?>
<?php echo $__env->renderComponent(); ?>

<div class="row g-4">
    <?php $__empty_1 = true; $__currentLoopData = $products; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $product): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
        <div class="col-sm-6 col-md-4 col-lg-3">
            <div class="card shadow-sm border-0 h-100 hover-card">
                <div class="position-relative">
                    <img src="<?php echo e($product->image ? asset('storage/'.$product->image) : asset('build/images/default.jpg')); ?>"
                         class="card-img-top rounded-top" alt="<?php echo e($product->name); ?>"
                         style="height: 200px; object-fit: cover;">
                    <span class="badge bg-success position-absolute top-0 start-0 m-2">Stok: <?php echo e($product->stock); ?></span>
                </div>
                <div class="card-body d-flex flex-column">
                    <h5 class="card-title mb-2"><?php echo e($product->name); ?></h5>
                    <p class="card-text text-muted small flex-grow-1"><?php echo e(Str::limit($product->description, 70)); ?></p>
                    <div class="d-flex justify-content-between align-items-center mt-3">
                        <h5 class="text-danger mb-0">Rp <?php echo e(number_format($product->price, 0, ',', '.')); ?></h5>
                        <a href="<?php echo e(route('shop.product.show', $product->id)); ?>" 
                           class="btn btn-outline-primary btn-sm rounded-pill px-3">
                            <i class="ri-eye-line me-1"></i> Detail
                        </a>
                    </div>
                </div>
            </div>
        </div>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
        <div class="text-center py-5">
            <p class="text-muted">Belum ada produk tersedia.</p>
        </div>
    <?php endif; ?>
</div>

<?php $__env->stopSection(); ?>

<?php $__env->startSection('css'); ?>
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
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\corporate\resources\views/pages/shop/show.blade.php ENDPATH**/ ?>