

<?php $__env->startSection('title', 'Katalog Produk'); ?>

<?php $__env->startSection('content'); ?>
<div class="container py-4">
    <h4 class="fw-bold mb-4">🛍️ Katalog Produk</h4>

    <?php if($products->count() > 0): ?>
        <div class="row g-4">
            <?php $__currentLoopData = $products; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $product): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <div class="col-sm-6 col-md-4 col-lg-3">
                    <div class="card h-100 shadow-sm border-0 rounded-4">
                        
                        <?php
                            $image = $product->images->first()
                                ? asset('storage/'.$product->images->first()->image_path)
                                : asset('images/no-image.png');
                        ?>
                        <img src="<?php echo e($image); ?>" alt="<?php echo e($product->name); ?>"
                             class="card-img-top object-fit-cover"
                             style="height: 180px; border-top-left-radius: 1rem; border-top-right-radius: 1rem;">

                        <div class="card-body d-flex flex-column justify-content-between">
                            <div>
                                <h6 class="fw-semibold mb-1"><?php echo e($product->name); ?></h6>
                                <p class="text-success fw-bold mb-2">
                                    Rp <?php echo e(number_format($product->price, 0, ',', '.')); ?>

                                </p>
                                <small class="text-muted"><?php echo e(Str::limit($product->description, 60)); ?></small>
                            </div>

                            <div class="mt-3 d-flex justify-content-between align-items-center">
                                <a href="<?php echo e(route('shop.product.show', $product->id)); ?>"
                                   class="btn btn-outline-danger btn-sm rounded-pill px-3">
                                    <i class="ri-eye-line"></i> Detail
                                </a>
                                <button class="btn btn-primary btn-sm rounded-pill px-3 btnAddToCart"
                                        data-id="<?php echo e($product->id); ?>">
                                    <i class="ri-shopping-cart-line"></i> Tambah
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>
    <?php else: ?>
        <div class="text-center text-muted py-5">
            <i class="ri-inbox-line display-6"></i>
            <p class="mt-3">Belum ada produk tersedia.</p>
        </div>
    <?php endif; ?>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('script'); ?>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
$(function(){
    $('.btnAddToCart').on('click', function(){
        let id = $(this).data('id');

        // AJAX simulasi (nanti bisa dikaitkan ke cart route)
        Swal.fire({
            icon: 'success',
            title: 'Berhasil!',
            text: 'Produk berhasil ditambahkan ke keranjang.',
            showConfirmButton: false,
            timer: 1500
        });
    });
});
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\corporate\resources\views/pages/shop/catalog.blade.php ENDPATH**/ ?>