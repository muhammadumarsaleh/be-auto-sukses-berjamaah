@extends('layouts.master')

@section('title', 'User Management')

@section('css')
    <!-- DataTables CSS -->
    <link href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap5.min.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/responsive/2.2.9/css/responsive.bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/buttons/2.2.2/css/buttons.dataTables.min.css" rel="stylesheet">
@endsection

@section('content')
    @component('components.breadcrumb')
        @slot('li_1') Tables @endslot
        @slot('title') User List @endslot
    @endcomponent

    <div class="row">
        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">User Table</h5>
                    <button class="btn btn-success btn-sm" id="btnAdd">
                        <i class="ri-add-line"></i> Add User
                    </button>
                </div>

                <div class="card-body">
                    <div class="table-responsive">
                        <table id="userTable" class="table table-striped table-bordered align-middle w-100">
                            <thead class="table-light">
                                <tr>
                                    <th>No</th>
                                    <th>Avatar</th>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>Role</th>
                                    <th>Points</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($users as $i => $u)
                                    <tr data-id="{{ $u->id }}">
                                        <td>{{ $i + 1 }}</td>
                                        <td>
                                            @if($u->avatar)
                                                <img src="{{ asset('storage/'.$u->avatar) }}" alt="{{ $u->name }}" class="rounded-circle" width="55" height="55">
                                            @else
                                                <span class="text-muted">No image</span>
                                            @endif
                                        </td>
                                        <td>{{ $u->name }}</td>
                                        <td>{{ $u->email }}</td>
                                        <td><span class="badge bg-info">{{ $u->role }}</span></td>
                                        <td>{{ $u->points }}</td>
                                        <td>
                                            <div class="btn-group" role="group">
                                                <button class="btn btn-info btn-sm btnShow" data-id="{{ $u->id }}" title="View">
                                                    <i class="ri-eye-line"></i>
                                                </button>
                                                <button class="btn btn-warning btn-sm btnEdit" data-id="{{ $u->id }}" title="Edit">
                                                    <i class="ri-pencil-line"></i>
                                                </button>
                                                <button class="btn btn-danger btn-sm btnDelete" data-id="{{ $u->id }}" title="Delete">
                                                    <i class="ri-delete-bin-line"></i>
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

    <!-- User Modal -->
    <div class="modal fade" id="userModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <form id="userForm" enctype="multipart/form-data">
                @csrf
                <input type="hidden" id="user_id">
                <div class="modal-content">
                    <div class="modal-header bg-light">
                        <h5 id="modalTitle" class="modal-title">Add User</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body row g-3">
                        <div class="col-md-6">
                            <label>Name</label>
                            <input type="text" class="form-control" id="name" name="name">
                        </div>
                        <div class="col-md-6">
                            <label>Email</label>
                            <input type="email" class="form-control" id="email" name="email">
                        </div>
                        <div class="col-md-6">
                            <label>Password</label>
                            <input type="password" class="form-control" id="password" name="password" placeholder="Leave empty if not changing">
                        </div>
                        <div class="col-md-6">
                            <label>Role</label>
                            <select class="form-select" id="role" name="role">
                                <option value="reseller">Reseller</option>
                                <option value="stokis">Stokis</option>
                                <option value="master_stokis">Master Stokis</option>
                                <option value="super_admin">Super Admin</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label>Points</label>
                            <input type="number" class="form-control" id="points" name="points" value="0">
                        </div>
                        <div class="col-md-6">
                            <label>Avatar</label>
                            <input type="file" class="form-control" id="avatar" name="avatar" accept="image/*">
                            <div id="preview" class="mt-2 d-flex flex-wrap gap-2"></div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" id="btnSave" class="btn btn-success">Save</button>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Notif Modal -->
    <div class="modal fade" id="notifModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content glass-card text-center p-4 border-0 shadow-lg position-relative">
                <div id="notifIcon" class="notif-icon mb-3"></div>
                <h5 id="notifTitle" class="fw-bold mb-2"></h5>
                <p id="notifMessage" class="text-muted mb-3"></p>
                <div class="progress-container mx-auto">
                    <div class="progress-bar"></div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.11.5/js/dataTables.bootstrap5.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.2.9/js/dataTables.responsive.min.js"></script>

<script>
$(function() {
    const storageUrl = "{{ asset('storage') }}";
    const csrfToken = '{{ csrf_token() }}';
    const notifModal = new bootstrap.Modal('#notifModal');

    function showNotif(title, message, type = "success") {
        const icon = $("#notifIcon");
        const titleEl = $("#notifTitle");
        const msgEl = $("#notifMessage");
        const card = $(".glass-card");

        let color, emoji;
        switch (type) {
            case "success": color = "#16a34a"; emoji = "✅"; break;
            case "error": color = "#dc2626"; emoji = "❌"; break;
            default: color = "#2563eb"; emoji = "ℹ️";
        }

        icon.html(`<div class="icon-circle" style="border-color:${color}; color:${color}">${emoji}</div>`);
        titleEl.text(title);
        msgEl.text(message);
        card.css("border-top", `5px solid ${color}`);

        $(".progress-bar").css({ width: "0%", background: color });
        setTimeout(() => $(".progress-bar").css("width", "100%"), 100);

        notifModal.show();
        setTimeout(() => notifModal.hide(), 2500);
    }

    let table = $('#userTable').DataTable({ responsive: true, autoWidth: false });

    function resetModal() {
        $('#userForm')[0].reset();
        $('#user_id').val('');
        $('#preview').html('');
        $('#password').attr('placeholder', 'Leave empty if not changing');
    }

    // Preview avatar
    $(document).on('change', '#avatar', function() {
        $('#preview').html('');
        const file = this.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = e => $('#preview').html(`<img src="${e.target.result}" class="rounded-circle" width="70" height="70">`);
            reader.readAsDataURL(file);
        }
    });

    // Add User
    $('#btnAdd').on('click', function() {
        resetModal();
        $('#modalTitle').text('Add User');
        $('#userModal').modal('show');
    });

    // Save or Update
    $('#userForm').on('submit', function(e) {
        e.preventDefault();
        const id = $('#user_id').val();
        const url = id ? `/users/${id}` : `/users`;
        const formData = new FormData(this);
        if (id) formData.append('_method', 'PUT');

        $.ajax({
            url, method: 'POST', data: formData,
            headers: { 'X-CSRF-TOKEN': csrfToken },
            contentType: false, processData: false,
            success: res => {
                $('#userModal').modal('hide');
                showNotif('Berhasil', id ? 'User berhasil diperbarui!' : 'User berhasil ditambahkan!', 'success');
                setTimeout(() => location.reload(), 1000);
            },
            error: err => {
                console.error(err);
                showNotif('Error', 'Gagal menyimpan user.', 'error');
            }
        });
    });

    // Show User
    $(document).on('click', '.btnShow', function() {
        resetModal();
        let id = $(this).data('id');
        $.get(`/users/${id}`, function(data) {
            $('#modalTitle').text('View User');
            $('#user_id').val(id);
            $('#name').val(data.name).prop('disabled', true);
            $('#email').val(data.email).prop('disabled', true);
            $('#password').prop('disabled', true);
            $('#role').val(data.role).prop('disabled', true);
            $('#points').val(data.points).prop('disabled', true);
            $('#avatar').hide();
            $('#btnSave').hide();

            if (data.avatar) {
                $('#preview').html(`<img src="${storageUrl}/${data.avatar}" class="rounded-circle" width="80" height="80">`);
            } else {
                $('#preview').html('<span class="text-muted">No image</span>');
            }

            $('#userModal').modal('show');
        }).fail(() => showNotif('Error', 'Gagal memuat data user.', 'error'));
    });

    // Edit User
    $(document).on('click', '.btnEdit', function() {
        resetModal();
        let id = $(this).data('id');
        $.get(`/users/${id}`, function(data) {
            $('#modalTitle').text('Edit User');
            $('#user_id').val(id);
            $('#name').val(data.name);
            $('#email').val(data.email);
            $('#role').val(data.role);
            $('#points').val(data.points);
            if (data.avatar) {
                $('#preview').html(`<img src="${storageUrl}/${data.avatar}" class="rounded-circle" width="70" height="70">`);
            }
            $('#userModal').modal('show');
        }).fail(() => showNotif('Error', 'Gagal memuat data user.', 'error'));
    });

    // Delete User
    $(document).on('click', '.btnDelete', function() {
        if (!confirm('Yakin hapus user ini?')) return;
        const id = $(this).data('id');

        $.ajax({
            url: `/users/${id}`,
            type: 'POST',
            data: { _method: 'DELETE', _token: csrfToken },
            success: () => {
                showNotif('Berhasil', 'User berhasil dihapus!', 'success');
                setTimeout(() => location.reload(), 1000);
            },
            error: () => showNotif('Error', 'Gagal menghapus user.', 'error')
        });
    });

    $('#userModal').on('hidden.bs.modal', function() {
        $('#name, #email, #password, #role, #points').prop('disabled', false);
        $('#avatar').show();
        $('#btnSave').show();
    });
});
</script>

<style>
.glass-card {
    background: rgba(255, 255, 255, 0.95);
    backdrop-filter: blur(10px);
    border-radius: 1rem;
    animation: fadeInUp 0.4s ease;
}
@keyframes fadeInUp {
    from { opacity: 0; transform: translateY(20px); }
    to { opacity: 1; transform: translateY(0); }
}
.icon-circle {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    border: 3px solid;
    border-radius: 50%;
    width: 65px;
    height: 65px;
    font-size: 2rem;
    background: white;
    box-shadow: 0 3px 6px rgba(0,0,0,0.1);
    animation: pop 0.3s ease-out;
}
@keyframes pop {
    from { transform: scale(0.7); opacity: 0; }
    to { transform: scale(1); opacity: 1; }
}
.progress-container {
    height: 4px;
    width: 80%;
    background: #f1f1f1;
    border-radius: 2px;
    overflow: hidden;
}
.progress-bar {
    height: 100%;
    width: 0%;
    transition: width 2.5s linear;
}
</style>
@endsection
