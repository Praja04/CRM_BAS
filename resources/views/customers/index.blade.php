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
                                                <h4 class="fw-bold">Velzon - Management Customer</h4>
                                                <div class="hstack gap-3 flex-wrap">
                                                    <div><i class="ri-building-line align-bottom me-1"></i></div>
                                                    <div class="vr"></div>
                                                    <div>manage your customers!</span></div>

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
                <i class="ri-add-line align-bottom me-1"></i> Add Customer
            </button>
        </div>

        <!-- DataTable -->
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">
                            Customer Table
                        </h5>
                    </div>
                    <div class="card-body">
                        <div>
                            <table id="customersTable" class="table table-bordered dt-responsive nowrap table-striped align-middle">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>User ID</th>
                                        <th>Name</th>
                                        <th>Email</th>
                                        <th>Status</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody></tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Customer Modal (for adding/editing) -->
        <div id="userModal" class="modal fade" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="modalTitle">Add New Customer</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form id="addCustomerForm">
                        <div class="modal-body">
                            <input type="hidden" id="userId">
                            <div class="mb-3">
                                <label for="name" class="form-label">Name</label>
                                <input type="text" id="name" name="name" class="form-control" required>
                            </div>
                            <div class="mb-3" id="passwordField">
                                <label for="email" class="form-label">Email</label>
                                <input type="email" id="email" name="email" class="form-control">
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                            <button type="submit" id="submitBtn" class="btn btn-success">Add Customer</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- edit -->
        <div id="editModal" class="modal fade" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="modalTitleedit">Edit Customer</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form id="editCustomerForm">
                        <div class="modal-body">
                            <input type="hidden" id="userId_edit">
                            <div class="mb-3">
                                <label for="name_edit" class="form-label">Name</label>
                                <input type="text" id="name_edit" name="name_edit" class="form-control" required>
                            </div>
                            <div class="mb-3" id="passwordField_edit">
                                <label for="email" class="form-label">Email</label>
                                <input type="email" id="email_edit" name="email_edit" class="form-control">
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                            <button type="submit" id="submitBtn_edit" class="btn btn-success">Edit Data Customer</button>
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
        var table = $('#customersTable').DataTable({
            processing: true,
            serverSide: true,
            responsive: false, // Matikan responsif default DataTables
            scrollX: true, // Aktifkan scroll horizontal jika tabel terlalu lebar
            autoWidth: false, // Hindari perubahan lebar otomatis
            ajax: "{{ route('customers.data') }}",
            columns: [{
                    data: null
                }, // Nomor urut otomatis
                {
                    data: 'user_id'
                },
                {
                    data: 'name'
                },
                {
                    data: 'email'
                },
                {
                    data: 'status'
                },
                {
                    data: 'action'
                }
            ],
            columnDefs: [{
                    targets: 0,
                    width: "5%",
                    orderable: false,
                    searchable: false,
                    render: function(data, type, row, meta) {
                        return meta.row + 1;
                    }
                },
                {
                    targets: 1,
                    width: "15%",
                    className: "text-nowrap"
                }, // User ID tidak boleh patah
                {
                    targets: 2,
                    width: "20%"
                }, // Nama
                {
                    targets: 3,
                    width: "25%",
                    className: "text-nowrap"
                }, // Email tetap dalam 1 baris
                {
                    targets: 4,
                    width: "15%",
                    render: function(data, type, row) {
                        return `<p>${data}</p><button class="btn btn-primary update-status" data-id="${row.user_id}">Update Status</button>`;
                    }
                },
                {
                    targets: 5,
                    width: "20%",
                    orderable: false,
                    searchable: false
                }
            ]
        });



        $('#addUserBtn').on('click', function() {
            $('#userModal').modal('show'); // Show the modal
            $('#modalTitle').text('Add New Customer'); // Change modal title to Add
            $('#submitBtn').text('Add Customer'); // Change button text to Add
            $('#addCustomerForm')[0].reset(); // Reset form fields
        });

        // Add new customer
        $('#addCustomerForm').on('submit', function(e) {
            e.preventDefault();

            // Tampilkan SweetAlert loading
            Swal.fire({
                title: 'Processing...',
                text: 'Please wait while we add the customer.',
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading(); // Menampilkan animasi loading
                }
            });

            $.ajax({
                url: "{{ route('customers.store') }}",
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: $(this).serialize(),
                success: function(response) {
                    // Tutup loading dan tampilkan pesan sukses
                    Swal.fire({
                        title: 'Success!',
                        text: response.message,
                        icon: 'success',
                        confirmButtonText: 'OK'
                    }).then(() => {
                        table.ajax.reload(); // Reload tabel data
                        $('#userModal').modal('hide'); // Tutup modal
                        $('#addCustomerForm')[0].reset(); // Reset form
                    });
                },
                error: function(xhr) {
                    // Tutup loading dan tampilkan pesan error
                    Swal.fire({
                        title: 'Error!',
                        text: xhr.responseJSON ? xhr.responseJSON.message : 'Error adding customer',
                        icon: 'error',
                        confirmButtonText: 'OK'
                    });
                }
            });
        });



        //edit customer
        $('#editCustomerForm').on('submit', function(e) {
            e.preventDefault();

            var userId = $('#userId_edit').val();

            // Tampilkan SweetAlert loading
            Swal.fire({
                title: 'Updating...',
                text: 'Please wait while we update the customer data.',
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading(); // Menampilkan animasi loading
                }
            });

            $.ajax({
                url: '/customers/' + userId + '/update',
                method: 'PUT',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: $(this).serialize(),
                success: function(response) {
                    // Tutup loading dan tampilkan pesan sukses
                    Swal.fire({
                        title: 'Success!',
                        text: response.message,
                        icon: 'success',
                        confirmButtonText: 'OK'
                    }).then(() => {
                        table.ajax.reload(); // Reload tabel data
                        $('#editModal').modal('hide'); // Tutup modal
                        $('#editCustomerForm')[0].reset(); // Reset form
                    });
                },
                error: function(xhr) {
                    // Tutup loading dan tampilkan pesan error
                    Swal.fire({
                        title: 'Error!',
                        text: xhr.responseJSON ? xhr.responseJSON.message : 'Error updating customer',
                        icon: 'error',
                        confirmButtonText: 'OK'
                    });
                }
            });
        });



        // Edit customer
        $('#customersTable').on('click', '.edit', function() {
            var userId = $(this).data('id');
            $.ajax({
                url: '/customers/' + userId + '/edit',
                method: 'GET',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') // Menambahkan CSRF token di header
                },
                success: function(response) {
                    $('#userId_edit').val(response.user_id);
                    $('#name_edit').val(response.name);
                    $('#email_edit').val(response.email);
                    // $('#modalTitle').text('Edit Customer');
                    // $('#submitBtn').text('Update Customer');
                    $('#editModal').modal('show');
                }
            });
        });

        // Update status
        $('#customersTable').on('click', '.update-status', function(e) {
            e.preventDefault();
            var userId = $(this).data('id');

            // Tampilkan konfirmasi sebelum update
            Swal.fire({
                title: 'Are you sure?',
                text: 'Do you want to update this customer\'s status?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Yes, update it!',
                cancelButtonText: 'Cancel',
                allowOutsideClick: false
            }).then((result) => {
                if (result.isConfirmed) {
                    // Tampilkan SweetAlert loading
                    Swal.fire({
                        title: 'Updating...',
                        text: 'Please wait while updating the status.',
                        allowOutsideClick: false,
                        didOpen: () => {
                            Swal.showLoading(); // Menampilkan animasi loading
                        }
                    });

                    $.ajax({
                        url: '/customers/' + userId + '/update-status',
                        method: 'PUT',
                        data: {
                            _token: $('meta[name="csrf-token"]').attr('content'),
                        },
                        success: function(response) {
                            // Tutup loading dan tampilkan pesan sukses
                            Swal.fire({
                                title: 'Success!',
                                text: response.message,
                                icon: 'success',
                                confirmButtonText: 'OK'
                            }).then(() => {
                                table.ajax.reload(); // Reload tabel data
                            });
                        },
                        error: function(xhr) {
                            // Tutup loading dan tampilkan pesan error
                            Swal.fire({
                                title: 'Error!',
                                text: xhr.responseJSON ? xhr.responseJSON.message : 'Error updating customer status',
                                icon: 'error',
                                confirmButtonText: 'OK'
                            });
                        }
                    });
                }
            });
        });


        // Delete customer
        $('#customersTable').on('click', '.delete', function(e) {
            e.preventDefault();
            var userId = $(this).data('id');

            // Tampilkan konfirmasi sebelum menghapus
            Swal.fire({
                title: 'Are you sure?',
                text: 'This customer will be permanently deleted!',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Yes, delete it!',
                cancelButtonText: 'Cancel',
                allowOutsideClick: false
            }).then((result) => {
                if (result.isConfirmed) {
                    // Tampilkan SweetAlert loading
                    Swal.fire({
                        title: 'Deleting...',
                        text: 'Please wait while deleting the customer.',
                        allowOutsideClick: false,
                        didOpen: () => {
                            Swal.showLoading(); // Menampilkan animasi loading
                        }
                    });

                    $.ajax({
                        url: '/customers/' + userId,
                        method: 'DELETE',
                        data: {
                            _token: $('meta[name="csrf-token"]').attr('content'),
                        },
                        success: function(response) {
                            // Tutup loading dan tampilkan pesan sukses
                            Swal.fire({
                                title: 'Deleted!',
                                text: response.message,
                                icon: 'success',
                                confirmButtonText: 'OK'
                            }).then(() => {
                                table.ajax.reload(); // Reload tabel data
                            });
                        },
                        error: function(xhr) {
                            // Tutup loading dan tampilkan pesan error
                            Swal.fire({
                                title: 'Error!',
                                text: xhr.responseJSON ? xhr.responseJSON.message : 'Error deleting customer',
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