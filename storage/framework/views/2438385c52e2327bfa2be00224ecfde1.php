

<?php $__env->startSection('title', 'Katalog Produk'); ?>

<?php $__env->startSection('content'); ?>
<div class="container py-4">
    <h4 class="fw-bold mb-4">🛍️ Katalog Produk</h4>

    <div class="row g-4">
        <?php $__currentLoopData = $products; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $product): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <?php
                $image = $product->images->first()
                    ? asset('storage/'.$product->images->first()->image_path)
                    : asset('images/no-image.png');
            ?>

            <div class="col-sm-6 col-md-4 col-xl-3">
                <div class="card shadow-sm border-0 h-100 rounded-4 hover-card">
                    <!-- Gambar -->
                    <div class="position-relative">
                        <img class="card-img-top img-fluid rounded-top-4 object-fit-cover"
                             src="<?php echo e($image); ?>" alt="<?php echo e($product->name); ?>"
                             style="height: 200px; width: 100%; object-fit: cover;">
                        <span class="position-absolute top-0 end-0 m-2 badge bg-success bg-opacity-75">
                            Stok: <?php echo e($product->stock); ?>

                        </span>
                    </div>

                    <!-- Isi Card -->
                    <div class="card-body d-flex flex-column">
                        <h5 class="card-title mb-2 text-truncate"><?php echo e($product->name); ?></h5>
                        <h6 class="text-success fw-semibold mb-2">
                            Rp <?php echo e(number_format($product->price, 0, ',', '.')); ?>

                        </h6>
                        <p class="card-text small text-muted mb-3" style="min-height: 45px;">
                            <?php echo e(Str::limit($product->description, 60)); ?>

                        </p>

                        <div class="mt-auto text-end">
                            <a href="<?php echo e(route('shop.product.show', $product->id)); ?>" 
                               class="btn btn-soft-danger btn-sm rounded-pill px-3 me-2">
                                <i class="ri-eye-line me-1"></i> Detail
                            </a>
                            <button class="btn btn-primary btn-sm rounded-pill px-3 btnAddToCart"
                                    data-id="<?php echo e($product->id); ?>">
                                <i class="ri-shopping-cart-line me-1"></i> Tambah
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('style'); ?>
<style>
.hover-card {
    transition: all 0.3s ease-in-out;
}
.hover-card:hover {
    transform: translateY(-6px);
    box-shadow: 0 6px 18px rgba(0, 0, 0, 0.08);
}
.btn-soft-danger {
    background-color: rgba(255, 76, 76, 0.1);
    color: #ff4c4c;
    border: none;
}
.btn-soft-danger:hover {
    background-color: #ff4c4c;
    color: #fff;
}
</style>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('script'); ?>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
$(function(){
    $('.btnAddToCart').on('click', function(){
        let id = $(this).data('id');

        // Nanti bisa dihubungkan ke route cart/add
        Swal.fire({
            icon: 'success',
            title: 'Ditambahkan!',
            text: 'Produk berhasil dimasukkan ke keranjang.',
            showConfirmButton: false,
            timer: 1500
        });
    });
});
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\corporate\resources\views/pages/mitra/shop/catalog.blade.php ENDPATH**/ ?>