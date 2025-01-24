

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>User Management</title>
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
</head>

<body>
    <h1>User Management</h1>
    <table id="usersTable" class="display">
        <thead>
            <tr>
                <th>Username</th>
                <th>Created At</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <!-- DataTables data will be injected here -->
        </tbody>
    </table>

    <button id="addUserBtn">Add User</button>

    <!-- Modal untuk form Add/Edit User -->
    <div id="userModal" style="display:none;">
        <h2 id="modalTitle">Add User</h2>
        <form id="userForm">
            <input type="hidden" id="userId">
            <label for="username">Username</label>
            <input type="text" id="username" name="username" required>
            <label for="password">Password</label>
            <input type="password" id="password" name="password">
            <button type="submit" id="submitBtn">Save</button>
        </form>
    </div>

    <script>
        $(document).ready(function() {
            var table = $('#usersTable').DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ route('users.data') }}",
                columns: [{
                        data: 'username'
                    },
                    {
                        data: 'created_at'
                    },
                    {
                        data: 'action',
                        orderable: false,
                        searchable: false
                    }
                ]
            });

            // Add User button click
            $('#addUserBtn').on('click', function() {
                $('#userModal').show();
                $('#modalTitle').text('Add User');
                $('#userForm')[0].reset();
                $('#userId').val('');
                $('#submitBtn').text('Add User');
            });

            // Edit button click
            $(document).on('click', '.edit', function() {
                var userId = $(this).data('id');
                $.ajax({
                    url: '/users/' + userId + '/edit',
                    method: 'GET',
                    success: function(response) {
                        $('#userModal').show();
                        $('#modalTitle').text('Edit User');
                        $('#username').val(response.username);
                        $('#password').val('');
                        $('#userId').val(response.id);
                        $('#submitBtn').text('Update User');
                    }
                });
            });

            // Form submit (Add/Edit)
            $('#userForm').on('submit', function(e) {
                e.preventDefault();
                var userId = $('#userId').val();
                var url = userId ? '/users/' + userId : '/users';
                var method = userId ? 'PUT' : 'POST';
                $.ajax({
                    url: url,
                    method: method,
                    data: {
                        _token: $('meta[name="csrf-token"]').attr('content'),
                        username: $('#username').val(),
                        password: $('#password').val()
                    },
                    success: function(response) {
                        alert(response.success);
                        $('#userModal').hide();
                        table.ajax.reload();
                    },
                    error: function() {
                        alert('Terjadi kesalahan');
                    }
                });
            });

            // Delete button click
            $(document).on('click', '.delete', function() {
                var userId = $(this).data('id');
                if (confirm('Are you sure you want to delete this user?')) {
                    $.ajax({
                        url: '/users/' + userId,
                        method: 'DELETE',
                        data: {
                            _token: $('meta[name="csrf-token"]').attr('content')
                        },
                        success: function(response) {
                            alert(response.success);
                            table.ajax.reload();
                        },
                        error: function() {
                            alert('Error deleting user');
                        }
                    });
                }
            });
        });
    </script>
</body>

</html>