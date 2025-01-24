@extends('layout')

@section('content')
<div class="page-content">
    <div class="container-fluid">
        <h2>User Management</h2>

        <!-- Add User Button -->
        <div class="mb-3">
            <button id="addUserBtn" class="btn btn-primary">
                <i class="ri-add-line align-bottom me-1"></i> Add User
            </button>
        </div>

        <!-- DataTable -->
        <div >
            <table id="usersTable" class="table table-striped table-bordered">
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
                    alert(response.success);
                    $('#userModal').modal('hide');
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
            if (confirm('Are you sure you want to delete this user?')) {
                $.ajax({
                    url: "{{ route('users.destroy', ':id') }}".replace(':id', userId),
                    method: 'DELETE',
                    data: {
                        _token: "{{ csrf_token() }}"
                    },
                    success: function(response) {
                        alert(response.success);
                        table.ajax.reload();
                    },
                    error: function(xhr) {
                        alert('Error: ' + xhr.responseJSON.message);
                    }
                });
            }
        });
    });
</script>

@endsection