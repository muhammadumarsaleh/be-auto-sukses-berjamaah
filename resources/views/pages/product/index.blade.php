@extends('layouts.master')

@section('title', 'Product Management')

@section('css')
    <!-- DataTables CSS -->
    <link href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap5.min.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/responsive/2.2.9/css/responsive.bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/buttons/2.2.2/css/buttons.dataTables.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/toastify-js/src/toastify.min.css">


    <style>
        .table img { width: 55px; height: 55px; object-fit: cover; border-radius: 6px; margin: 2px; }
        .dataTables_wrapper .dataTables_filter { float: right; }
        .modal-body label { font-weight: 600; }
        .img-thumb { position: relative; display: inline-block; margin: 5px; }
        .img-thumb img { width: 70px; height: 70px; object-fit: cover; border-radius: 6px; border: 2px solid #ddd; }
        .btn-remove-image {
            position: absolute; top: -6px; right: -6px;
            background: rgba(255,0,0,0.85); color:#fff; border: none;
            border-radius: 50%; width: 20px; height: 20px; font-size: 12px; cursor:pointer;
        }
    </style>
@endsection

@section('content')
    @component('components.breadcrumb')
        @slot('li_1') Tables @endslot
        @slot('title') Product List @endslot
    @endcomponent

    <div class="row">
        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Product Table</h5>
                    <button class="btn btn-success btn-sm" id="btnAdd">
                        <i class="ri-add-line"></i> Add Product
                    </button>
                </div>

                <div class="card-body">
                    <div class="table-responsive">
                        <table id="productTable" class="table table-striped table-bordered align-middle w-100">
                            <thead class="table-light">
                                <tr>
                                    <th>No</th>
                                    <th>Image</th>
                                    <th>Name</th>
                                    <th>Description</th>
                                    <th>Price</th>
                                    <th>Stock</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($products as $i => $p)
                                    <tr data-id="{{ $p->id }}">
                                        <td>{{ $i + 1 }}</td>
                                        <td>
                                            @if($p->images && count($p->images))
                                                <img src="{{ asset('storage/'.$p->images[0]->image_path) }}" alt="{{ $p->name }}">
                                            @else
                                                <span class="text-muted">No image</span>
                                            @endif
                                        </td>
                                        <td>{{ $p->name }}</td>
                                        <td>{{ Str::limit($p->description, 50) }}</td>
                                        <td>Rp {{ number_format($p->price, 0, ',', '.') }}</td>
                                        <td>{{ $p->stock }}</td>
                                        <td>
                                            <div class="btn-group" role="group">
                                                <button class="btn btn-info btn-sm btnShow" data-id="{{ $p->id }}" title="View">
                                                    <i class="ri-eye-line"></i>
                                                </button>
                                                <button class="btn btn-warning btn-sm btnEdit" data-id="{{ $p->id }}" title="Edit">
                                                    <i class="ri-pencil-line"></i>
                                                </button>
                                                <button class="btn btn-danger btn-sm btnDelete" data-id="{{ $p->id }}" title="Delete">
                                                    <i class="ri-delete-bin-line"></i>
                                                </button>
                                                <button class="btn btn-secondary btn-sm btnAddImage" data-id="{{ $p->id }}" title="Add Image">
                                                    <i class="ri-image-add-line"></i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Product Modal (Create/Edit/View) -->
    <div class="modal fade" id="productModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <form id="productForm" enctype="multipart/form-data">
                @csrf
                <input type="hidden" id="product_id">
                <div class="modal-content">
                    <div class="modal-header bg-light">
                        <h5 id="modalTitle" class="modal-title">Add Product</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body row g-3">
                        <div class="col-md-6">
                            <label>Name</label>
                            <input type="text" class="form-control" id="name" name="name">
                        </div>
                        <div class="col-md-6">
                            <label>Price</label>
                            <input type="number" class="form-control" id="price" name="price">
                        </div>
                        <div class="col-md-12">
                            <label>Description</label>
                            <textarea class="form-control" id="description" name="description" rows="3"></textarea>
                        </div>
                        <div class="col-md-6">
                            <label>Stock</label>
                            <input type="number" class="form-control" id="stock" name="stock">
                        </div>
                        <div class="col-md-6">
                            <label>Images (multiple)</label>
                            <input type="file" class="form-control" id="images" name="images[]" multiple accept="image/*">
                            <div id="preview" class="mt-2 d-flex flex-wrap gap-2"></div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" id="btnSave" class="btn btn-success">Save</button>
                        <button type="button" class="btn btn-secondary" id="btnCloseModal" data-bs-dismiss="modal">Close</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal Add Image -->
    <div class="modal fade" id="addImageModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <form id="addImageForm" enctype="multipart/form-data">
                @csrf
                <input type="hidden" id="add_image_product_id">
                <div class="modal-content">
                    <div class="modal-header bg-light">
                        <h5 class="modal-title">Add Images to Product</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <label>Upload Additional Images</label>
                        <input type="file" name="images[]" class="form-control" multiple accept="image/*">
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-success">Upload</button>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection

@section('script')
    <!-- jQuery & DataTables -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/dataTables.bootstrap5.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.2.9/js/dataTables.responsive.min.js"></script>

    <!-- Toastify -->
    <script src="https://cdn.jsdelivr.net/npm/toastify-js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/toastify-js/src/toastify.min.css">

    <script>
    $(function () {
        const storageUrl = "{{ asset('storage') }}";
        const csrfToken = '{{ csrf_token() }}';

        let table = $('#productTable').DataTable({
            responsive: true,
            autoWidth: false
        });

        // 🔔 Helper: tampilkan notifikasi toast
        function showToast(message, type = "success") {
            Toastify({
                text: message,
                duration: 3000,
                close: true,
                gravity: "top",
                position: "right",
                backgroundColor: type === "success" 
                    ? "linear-gradient(to right, #00b09b, #96c93d)"
                    : "linear-gradient(to right, #ff5f6d, #ffc371)",
                stopOnFocus: true,
            }).showToast();
        }

        // Reset modal ke keadaan awal
        function resetProductModal() {
            $('#productForm')[0].reset();
            $('#product_id').val('');
            $('#preview').html('');
            $('#images').show().prop('disabled', false);
            $('#btnSave').show();
            $('#name, #price, #description, #stock').prop('disabled', false);
        }

        // Preview multiple images (client-side)
        $(document).on('change', '#images', function () {
            $('#preview').html('');
            const files = [...this.files];
            files.forEach(file => {
                const reader = new FileReader();
                reader.onload = e => {
                    $('#preview').append(`<div class="img-thumb new"><img src="${e.target.result}"></div>`);
                };
                reader.readAsDataURL(file);
            });
        });

        // Add product button
        $('#btnAdd').on('click', function () {
            resetProductModal();
            $('#modalTitle').text('Add Product');
            $('#productModal').modal('show');
        });

        // Save / Update product
        $('#productForm').on('submit', function (e) {
            e.preventDefault();
            let id = $('#product_id').val();
            let url = id ? `/products/${id}` : `/products`;
            let formData = new FormData(this);
            if (id) formData.append('_method', 'PUT');

            $.ajax({
                url: url,
                method: 'POST',
                data: formData,
                headers: { 'X-CSRF-TOKEN': csrfToken },
                contentType: false,
                processData: false,
                success: res => {
                    $('#productModal').modal('hide');
                    showToast(id ? 'Produk berhasil diperbarui!' : 'Produk berhasil ditambahkan!');
                    setTimeout(() => location.reload(), 800);
                },
                error: err => {
                    console.error(err);
                    showToast('Terjadi kesalahan saat menyimpan produk.', 'error');
                }
            });
        });

        // Show product
        $(document).on('click', '.btnShow', function () {
            resetProductModal();
            let id = $(this).data('id');
            $.get(`/products/${id}`, function (data) {
                $('#modalTitle').text('View Product');
                $('#product_id').val(id);
                $('#name').val(data.name).prop('disabled', true);
                $('#price').val(data.price).prop('disabled', true);
                $('#description').val(data.description).prop('disabled', true);
                $('#stock').val(data.stock).prop('disabled', true);
                $('#images').hide();
                $('#btnSave').hide();

                $('#preview').html('');
                if (data.images && data.images.length) {
                    data.images.forEach(img => {
                        let imgUrl = `${storageUrl}/${img.image_path}`;
                        $('#preview').append(`<div class="img-thumb"><img src="${imgUrl}" alt=""></div>`);
                    });
                } else {
                    $('#preview').html('<div class="text-muted">No images</div>');
                }
                $('#productModal').modal('show');
            }).fail(err => {
                console.error(err);
                showToast('Gagal memuat produk.', 'error');
            });
        });

        // Edit product
        $(document).on('click', '.btnEdit', function () {
            resetProductModal();
            let id = $(this).data('id');
            $.get(`/products/${id}`, function (data) {
                $('#modalTitle').text('Edit Product');
                $('#product_id').val(id);
                $('#name').val(data.name);
                $('#price').val(data.price);
                $('#description').val(data.description);
                $('#stock').val(data.stock);
                $('#images').show();
                $('#btnSave').show();

                $('#preview').html('');
                if (data.images && data.images.length) {
                    data.images.forEach(img => {
                        let imgUrl = `${storageUrl}/${img.image_path}`;
                        $('#preview').append(`
                            <div class="img-thumb" data-image-id="${img.id}">
                                <img src="${imgUrl}" alt="">
                                <button class="btn-remove-image" title="Hapus gambar">&times;</button>
                            </div>
                        `);
                    });
                }
                $('#productModal').modal('show');
            }).fail(err => {
                console.error(err);
                showToast('Gagal memuat produk untuk diedit.', 'error');
            });
        });

        // Delete single image
        $(document).on('click', '.btn-remove-image', function (e) {
            e.preventDefault();
            if (!confirm('Hapus gambar ini?')) return;

            const container = $(this).closest('.img-thumb');
            const imageId = container.data('image-id');

            $.ajax({
                url: `/products/image/${imageId}`,
                type: 'DELETE',
                data: { _token: csrfToken },
                success: function (res) {
                    if (res.success) {
                        container.remove();
                        showToast('Gambar berhasil dihapus!');
                    } else {
                        showToast('Gagal menghapus gambar.', 'error');
                    }
                },
                error: function (err) {
                    console.error(err);
                    showToast('Gagal menghapus gambar.', 'error');
                }
            });
        });

        // Delete product
        $(document).on('click', '.btnDelete', function () {
            if (!confirm('Yakin hapus produk ini?')) return;
            let id = $(this).data('id');
            $.ajax({
                url: `/products/${id}`,
                type: 'POST',
                data: { _method: 'DELETE', _token: csrfToken },
                success: () => {
                    showToast('Produk berhasil dihapus!');
                    setTimeout(() => location.reload(), 800);
                },
                error: err => {
                    console.error(err);
                    showToast('Gagal menghapus produk.', 'error');
                }
            });
        });

        // Add Image (modal)
        $(document).on('click', '.btnAddImage', function () {
            $('#add_image_product_id').val($(this).data('id'));
            $('#addImageModal').modal('show');
        });

        $('#addImageForm').on('submit', function (e) {
            e.preventDefault();
            let id = $('#add_image_product_id').val();
            let formData = new FormData(this);

            $.ajax({
                url: `/products/${id}/add-image`,
                method: 'POST',
                data: formData,
                headers: { 'X-CSRF-TOKEN': csrfToken },
                contentType: false,
                processData: false,
                success: () => {
                    $('#addImageModal').modal('hide');
                    showToast('Gambar berhasil ditambahkan!');
                    setTimeout(() => location.reload(), 800);
                },
                error: err => {
                    console.error(err);
                    showToast('Upload gambar gagal.', 'error');
                }
            });
        });

        // Re-enable form input when modal closed
        $('#productModal').on('hidden.bs.modal', function () {
            $('#name, #price, #description, #stock').prop('disabled', false);
            $('#images').show().prop('disabled', false);
            $('#btnSave').show();
        });

    });
    </script>

    <style>
        .toastify {
            border-radius: 10px;
            font-weight: 500;
            font-size: 14px;
            padding: 10px 18px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.15);
        }
    </style>
@endsection
