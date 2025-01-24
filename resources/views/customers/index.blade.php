@extends('layout')

@section('content')
<div class="page-content">
    <div class="container-fluid">
        <h2>Customer Management</h2>

        <!-- Add User Button -->
        <div class="mb-3">
            <button id="addUserBtn" class="btn btn-primary">
                <i class="ri-add-line align-bottom me-1"></i> Add Customer
            </button>
        </div>

        <!-- DataTable -->
        <div>
            <table id="customersTable" class="table table-striped table-bordered">
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
    </div>
</div>

<script>
    $(document).ready(function() {
        // Initialize DataTable
        var table = $('#customersTable').DataTable({
            processing: true,
            serverSide: true,
            ajax: "{{ route('customers.data') }}",
            columns: [{
                    data: null, // This column will display the auto-incrementing number
                    render: function(data, type, row, meta) {
                        return meta.row + 1; // Auto-incrementing number (row index + 1)
                    },
                    orderable: false,
                    searchable: false
                },
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
                    data: 'action',
                    orderable: false,
                    searchable: false
                }
            ],
            createdRow: function(row, data, dataIndex) {
                $(row).find('td:eq(4)').html(`
                        <button class="btn btn-primary update-status" data-id="${data.user_id}">Update Status</button>
                        <button class="btn btn-danger delete" data-id="${data.user_id}">Delete</button>
                    `);
            }
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
            $.ajax({
                url: "{{ route('customers.store') }}",
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: $(this).serialize(),
                success: function(response) {
                    alert(response.message);
                    table.ajax.reload(); // Reload table data
                    $('#userModal').modal('hide'); // Close modal
                },
                error: function(response) {
                    alert('Error adding customer');
                }
            });
        });

        // Edit customer
        $('#customersTable').on('click', '.edit', function() {
            var userId = $(this).data('id');
            $.ajax({
                url: '/customers/' + userId + '/edit',
                method: 'GET',
                success: function(response) {
                    $('#userId').val(response.user_id);
                    $('#name').val(response.name);
                    $('#email').val(response.email);
                    $('#modalTitle').text('Edit Customer');
                    $('#submitBtn').text('Update Customer');
                    $('#userModal').modal('show');
                }
            });
        });

        // Update status
        $('#customersTable').on('click', '.update-status', function(e) {
            e.preventDefault();
            var userId = $(this).data('id');
            $.ajax({
                url: '/customers/' + userId + '/update-status',
                method: 'PUT',
                data: {
                    _token: $('meta[name="csrf-token"]').attr('content'),
                },
                success: function(response) {
                    alert(response.message);
                    table.ajax.reload(); // Reload table data
                },
                error: function(response) {
                    alert('Error updating customer status');
                }
            });
        });

        // Delete customer
        $('#customersTable').on('click', '.delete', function(e) {
            e.preventDefault();
            var userId = $(this).data('id');
            $.ajax({
                url: '/customers/' + userId,
                method: 'DELETE',
                data: {
                    _token: $('meta[name="csrf-token"]').attr('content'),
                },
                success: function(response) {
                    alert(response.message);
                    table.ajax.reload(); // Reload table data
                },
                error: function(response) {
                    alert('Error deleting customer');
                }
            });
        });
    });
</script>

@endsection