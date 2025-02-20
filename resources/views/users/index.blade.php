@extends('layout')

@section('content')
<div class="page-content">
    <div class="container-fluid">

        <div class="row">
            <div class="col-lg-12">
                <div class="card mt-n4 mx-n4">
                    <div class="bg-soft-warning">
                        <div class="card-body pb-0 px-4">
                            <div class="row mb-3">
                                <div class="col-md">
                                    <div class="row align-items-center g-3">

                                        <div class="col-md">
                                            <div>
                                                <h4 class="fw-bold">Velzon - Management User</h4>
                                                <div class="hstack gap-3 flex-wrap">
                                                    <div><i class="ri-building-line align-bottom me-1"></i></div>
                                                    <div class="vr"></div>
                                                    <div>manage your users!</span></div>

                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>
                        <!-- end card body -->
                    </div>
                </div>
                <!-- end card -->
            </div>
            <!-- end col -->
        </div>
        <!-- Add User Button -->
        <div class="mb-3">
            <button id="addUserBtn" class="btn btn-primary">
                <i class="ri-add-line align-bottom me-1"></i> Add User
            </button>
        </div>

        <!-- DataTable -->
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    User Table
                </h5>
            </div>
            <div class="card-body">
                <div>
                    <table id="usersTable" class="table table-bordered dt-responsive nowrap table-striped align-middle">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Username</th>
                                <th>Created At</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <!-- DataTables will automatically populate this -->
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- User Modal -->
        <div id="userModal" class="modal fade" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="modalTitle">Add User</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form id="userForm">
                        <div class="modal-body">
                            <input type="hidden" id="userId">
                            <div class="mb-3">
                                <label for="username" class="form-label">Username</label>
                                <input type="text" id="username" name="username" class="form-control" required>
                            </div>
                            <div class="mb-3" id="passwordField">
                                <label for="password" class="form-label">Password</label>
                                <input type="password" id="password" name="password" class="form-control">
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                            <button type="submit" id="submitBtn" class="btn btn-success">Save</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>



<script>
    $(document).ready(function() {
        // Initialize DataTable
        var table = $('#usersTable').DataTable({
            processing: true,
            serverSide: true,
            responsive: false, // Matikan responsif default agar scrollX bekerja
            scrollX: true, // Aktifkan scroll horizontal jika tabel terlalu besar
            autoWidth: false, // Hindari perubahan lebar otomatis
            ajax: "{{ route('users.data') }}",
            columns: [{
                    data: 'id',
                    name: 'id'
                },
                {
                    data: 'username',
                    name: 'username'
                },
                {
                    data: 'created_at',
                    name: 'created_at'
                },
                {
                    data: 'action',
                    name: 'action',
                    orderable: false,
                    searchable: false
                }
            ],
            columnDefs: [{
                    targets: 0,
                    width: "5%",
                    className: "text-center text-nowrap"
                }, // ID kecil & di tengah
                {
                    targets: 1,
                    width: "25%",
                    className: "text-nowrap"
                }, // Username tidak patah
                {
                    targets: 2,
                    width: "20%",
                    className: "text-nowrap"
                }, // Tanggal tidak patah
                {
                    targets: 3,
                    width: "15%",
                    className: "text-center"
                } // Action di tengah
            ]
        });


        // Add User Button Click
        $('#addUserBtn').on('click', function() {
            $('#userModal').modal('show');
            $('#modalTitle').text('Add User');
            $('#userForm')[0].reset();
            $('#userId').val('');
            $('#passwordField').show();
            $('#submitBtn').text('Add User');
        });

        // Form Submit (Add/Edit User)
        $('#userForm').on('submit', function(e) {
            e.preventDefault();
            var userId = $('#userId').val();
            var url = userId ? "{{ route('users.update', ':id') }}".replace(':id', userId) : "{{ route('users.store') }}";
            var method = userId ? 'PUT' : 'POST';

            $.ajax({
                url: url,
                method: method,
                data: {
                    _token: "{{ csrf_token() }}",
                    username: $('#username').val(),
                    password: $('#password').val()
                },
                success: function(response) {
                    $('#userModal').modal('hide');
                    Swal.fire({
                        title: 'Success!',
                        html: '<div class="mt-3"><lord-icon src="https://cdn.lordicon.com/lupuorrc.json" trigger="loop" colors="primary:#0ab39c,secondary:#405189" style="width:120px;height:120px"></lord-icon><div class="mt-4 pt-2 fs-15"><p class="text-muted mx-4 mb-0">' + response.success + '</p></div></div>',
                        icon: 'success',
                        confirmButtonText: 'OK',
                        backdrop: true,
                        allowOutsideClick: false
                    });
                    // alert(response.success);
                    table.ajax.reload();
                },
                error: function(xhr) {
                    alert('Error: ' + xhr.responseJSON.message);
                }
            });
        });

        // Edit Button Click
        $(document).on('click', '.edit', function() {
            var userId = $(this).data('id');
            $.ajax({
                url: "{{ route('users.edit', ':id') }}".replace(':id', userId),
                method: 'GET',
                success: function(user) {
                    $('#userModal').modal('show');
                    $('#modalTitle').text('Edit User');
                    $('#username').val(user.username);
                    $('#passwordField').hide(); // Optional: Don't show password for editing
                    $('#userId').val(user.id);
                    $('#submitBtn').text('Update User');
                }
            });
        });

        // Delete Button Click
        $(document).on('click', '.delete', function() {
            var userId = $(this).data('id');

            Swal.fire({
                title: 'Are you sure?',
                text: "You won't be able to revert this!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: "{{ route('users.destroy', ':id') }}".replace(':id', userId),
                        method: 'DELETE',
                        data: {
                            _token: "{{ csrf_token() }}"
                        },
                        success: function(response) {
                            Swal.fire({
                                title: 'Deleted!',
                                text: response.success,
                                icon: 'success',
                                confirmButtonText: 'OK'
                            });
                            table.ajax.reload();
                        },
                        error: function(xhr) {
                            Swal.fire({
                                title: 'Error!',
                                text: xhr.responseJSON.message,
                                icon: 'error',
                                confirmButtonText: 'OK'
                            });
                        }
                    });
                }
            });
        });

    });
</script>

@endsection