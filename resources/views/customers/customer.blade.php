<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Master Customer</title>
    <!-- Include Yajra DataTables CSS and JS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
</head>

<body>
    <h1>Master Customer</h1>
    <form id="addCustomerForm">
        @csrf
        <label for="name">Name:</label>
        <input type="text" id="name" name="name" required>
        <label for="email">Email:</label>
        <input type="email" id="email" name="email" required>
        <button type="submit">Add Customer</button>
    </form>

    <table id="customersTable" class="display">
        <thead>
            <tr>
                <th>User ID</th>
                <th>Name</th>
                <th>Email</th>
                <th>Status</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody></tbody>
    </table>

    <!-- Modal for Edit -->
    <div id="editModal" style="display:none;">
        <h2>Edit Customer</h2>
        <form id="editCustomerForm">
            @csrf
            <input type="hidden" id="edit_user_id" name="user_id">
            <label for="edit_name">Name:</label>
            <input type="text" id="edit_name" name="name" required>
            <label for="edit_email">Email:</label>
            <input type="email" id="edit_email" name="email" required>
            <button type="submit">Save</button>
        </form>
    </div>

    <script>
        $(document).ready(function() {
            // Initialize DataTable
            var table = $('#customersTable').DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ route('customers.data') }}",
                columns: [{
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
                // Add "Update Status" button dynamically
                createdRow: function(row, data, dataIndex) {
                    $(row).find('td:eq(4)').html(`
                        <button class="btn btn-primary update-status" data-id="${data.user_id}">Update Status</button>
                        <button class="btn btn-danger delete" data-id="${data.user_id}">Delete</button>
                    `);
                }
            });

            // Add new customer
            $('#addCustomerForm').on('submit', function(e) {
                e.preventDefault();
                $.ajax({
                    url: "{{ route('customers.store') }}",
                    method: 'POST',
                    data: $(this).serialize(),
                    success: function(response) {
                        alert(response.message);
                        table.ajax.reload(); // Reload table data
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
                        // Populate edit form
                        $('#edit_user_id').val(response.user_id);
                        $('#edit_name').val(response.name);
                        $('#edit_email').val(response.email);
                        $('#editModal').show();
                    }
                });
            });

            // Save changes
            $('#editCustomerForm').on('submit', function(e) {
                e.preventDefault();
                $.ajax({
                    url: '/customers/' + $('#edit_user_id').val(),
                    method: 'PUT',
                    data: $(this).serialize(),
                    success: function(response) {
                        alert(response.message);
                        $('#editModal').hide();
                        table.ajax.reload(); // Reload table data
                    },
                    error: function(response) {
                        alert('Error updating customer');
                    }
                });
            });

            // Update status
            $('#customersTable').on('click', '.update-status', function(e) {
                e.preventDefault();
                var userId = $(this).data('id');

                // Send PUT request to update status
                $.ajax({
                    url: '/customers/' + userId + '/update-status', // Ensure this route is defined in routes/web.php
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

                // Send DELETE request to server
                $.ajax({
                    url: '/customers/' + userId,
                    method: 'DELETE',
                    data: {
                        _token: $('meta[name="csrf-token"]').attr('content'),
                    },
                    success: function(response) {
                        alert(response.message);
                        // Reload table data
                        table.ajax.reload();
                    },
                    error: function(response) {
                        alert('Error deleting customer');
                    }
                });
            });
        });
    </script>
</body>

</html>