@extends('layouts.admin')

@section('title', 'Update User Notifications')
@section('content-header', 'Update User Notifications')
@section('content-actions')
    <a href="{{route('notifications.index')}}" class="btn btn-success"><i class="fas fa-back"></i>Back To User notifications</a>
@endsection
@section('content')

    <div class="card">
        <div class="card-body">

            <form action="{{ route('notifications.update', $notifications) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')


                <div class="form-group">
                    <label for="user_id">User ID</label>
                    <input type="number" name="user_id" class="form-control @error('user_id') is-invalid @enderror"
                           id="user_id"
                           placeholder="User ID" value="{{ old('user_id', $notifications->user_id) }}">
                    @error('user_id')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                    @enderror
                </div>

                <button type="button" class="btn btn-primary" onclick="toggleUserListModal()">Select User</button>


                <div class="form-group"><br>
                    <label for="notify_user_id">Notify User ID</label>
                    <input type="number" name="notify_user_id" class="form-control @error('notify_user_id') is-invalid @enderror"
                           id="notify_user_id"
                           placeholder="Notify User ID" value="{{ old('notify_user_id', $notifications->notify_user_id) }}">
                    @error('notify_user_id')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                    @enderror
                </div>

                <button type="button" class="btn btn-primary" onclick="toggleNotifyUserListModal()">Select Notify User</button>




                <div class="form-group"><br>
                    <label for="message">Message</label>
                    <textarea name="message" class="form-control @error('message') is-invalid @enderror"
                            id="message" rows="3" placeholder="Message">{{ old('message', $notifications->message) }}</textarea>
                    @error('message')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                    @enderror
                </div>


                <button class="btn btn-success btn-block btn-lg" type="submit">Save Changes</button>
            </form>
        </div>
    </div>
    <div id="userListModal" class="modal">
    <!-- Modal content -->
    <div class="modal-content">
        <span class="close" onclick="toggleUserListModal()">&times;</span>
        <h2>User List</h2>
        <!-- Search input -->
        <input type="text" id="searchInput" oninput="searchUsers()" placeholder="Search...">
        <div class="table-responsive">
            <table class="table table-bordered" id="userTable">
                <thead>
                    <tr>
                        <th>Select</th>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Mobile</th>
                        <th>Email</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($users as $user)
                    <tr>
                        <td>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="selected_user_id" value="{{ $user->id }}" onclick="selectUser(this)" {{ $user->id == $notifications->user_id ? 'checked' : '' }}>
                            </div>
                        </td>
                        <td>{{ $user->id }}</td>
                        <td>{{ $user->name }}</td>
                        <td>{{ $user->mobile }}</td>
                        <td>{{ $user->email }}</td>
                    </tr>
                    @endforeach
                </tbody>
              
                </table>
            </div>
           <!-- Pagination -->
<nav aria-label="User List Pagination">
    <ul class="pagination justify-content-center">
        <!-- Previous button -->
        <li class="page-item">
            <button class="page-link" onclick="prevPage()" aria-label="Previous">
                <span aria-hidden="true">&laquo;</span>
                <span class="sr-only">Previous</span>
            </button>
        </li>
        
        <!-- Next button -->
        <li class="page-item">
            <button class="page-link" onclick="nextPage()" aria-label="Next">
                <span aria-hidden="true">&raquo;</span>
                <span class="sr-only">Next</span>
            </button>
        </li>
    </ul>
</nav>

        </div>
    </div>
</div>

    <div id="notifyUserListModal" class="modal">
    <!-- Modal content -->
    <div class="modal-content">
        <span class="close" onclick="toggleNotifyUserListModal()">&times;</span>
        <h2>Notify User List</h2>
        <!-- Search input -->
        <input type="text" id="searchNotifyInput" oninput="searchNotifyUsers()" placeholder="Search...">
        <div class="table-responsive">
            <table class="table table-bordered" id="notifyUserTable">
                <thead>
                    <tr>
                        <th>Select</th>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Mobile</th>
                        <th>Email</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($users as $user)
                    <tr>
                        <td>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="selected_notify_user_id" value="{{ $user->id }}" onclick="selectNotifyUser(this)" {{ $user->id == $notifications->notify_user_id ? 'checked' : '' }}>
                            </div>
                        </td>
                        <td>{{ $user->id }}</td>
                        <td>{{ $user->name }}</td>
                        <td>{{ $user->mobile }}</td>
                        <td>{{ $user->email }}</td>
                    </tr>
                    @endforeach
                </tbody>
              
                </table>
            </div>
           <!-- Pagination -->
<nav aria-label="Notify User List Pagination">
    <ul class="pagination justify-content-center">
        <!-- Previous button -->
        <li class="page-item">
            <button class="page-link" onclick="prevNotifyPage()" aria-label="Previous">
                <span aria-hidden="true">&laquo;</span>
                <span class="sr-only">Previous</span>
            </button>
        </li>
        
        <!-- Next button -->
        <li class="page-item">
            <button class="page-link" onclick="nextNotifyPage()" aria-label="Next">
                <span aria-hidden="true">&raquo;</span>
                <span class="sr-only">Next</span>
            </button>
        </li>
    </ul>
</nav>

        </div>
    </div>
</div>

@endsection
@section('js')
    <!-- Include any additional JavaScript if needed -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        // Define variables for pagination
        var currentPage = 1;
        var itemsPerPage = 10; // Change this value as needed
        var userListRows = $('#userTable tbody tr');
        var notifyUserListRows = $('#notifyUserTable tbody tr');

        // Function to toggle the user list modal
        function toggleUserListModal() {
    $('#userListModal').toggle(); // Toggle the user list modal
}

        // Function to toggle the notify user list modal
        function toggleNotifyUserListModal() {
    $('#notifyUserListModal').toggle(); // Toggle the notify user list modal
}
        // Function to filter user list based on search input
        function searchUsers() {
            var searchText = $('#searchInput').val().toLowerCase();
            $('#userTable tbody tr').each(function() {
                var id = $(this).find('td:eq(1)').text().toLowerCase();
                var name = $(this).find('td:eq(2)').text().toLowerCase();
                var mobile = $(this).find('td:eq(3)').text().toLowerCase();
                var email = $(this).find('td:eq(4)').text().toLowerCase();
                if (id.includes(searchText) || name.includes(searchText) || mobile.includes(searchText) || email.includes(searchText)) {
                    $(this).show();
                } else {
                    $(this).hide();
                }
            });
        }

        // Function to filter notify user list based on search input
        function searchNotifyUsers() {
            var searchText = $('#searchNotifyInput').val().toLowerCase();
            $('#notifyUserTable tbody tr').each(function() {
                var id = $(this).find('td:eq(1)').text().toLowerCase();
                var name = $(this).find('td:eq(2)').text().toLowerCase();
                var mobile = $(this).find('td:eq(3)').text().toLowerCase();
                var email = $(this).find('td:eq(4)').text().toLowerCase();
                if (id.includes(searchText) || name.includes(searchText) || mobile.includes(searchText) || email.includes(searchText)) {
                    $(this).show();
                } else {
                    $(this).hide();
                }
            });
        }

        // Function to handle checkbox click and update user_id input
        function selectUser(checkbox) {
            // Deselect all checkboxes
            $('input[name="selected_user_id"]').prop('checked', false);
            // Select only the clicked checkbox
            $(checkbox).prop('checked', true);
            // Set its value to the user_id input field
            $('#user_id').val(checkbox.value);
        }

        // Function to handle checkbox click and update notify_user_id input
        function selectNotifyUser(checkbox) {
            // Deselect all checkboxes
            $('input[name="selected_notify_user_id"]').prop('checked', false);
            // Select only the clicked checkbox
            $(checkbox).prop('checked', true);
            // Set its value to the notify_user_id input field
            $('#notify_user_id').val(checkbox.value);
        }

        // Function to show the specified page of users
        function showPage(page) {
            var startIndex = (page - 1) * itemsPerPage;
            var endIndex = startIndex + itemsPerPage;
            userListRows.hide().slice(startIndex, endIndex).show();
        }

        // Function to show the specified page of notify users
        function showNotifyPage(page) {
            var startIndex = (page - 1) * itemsPerPage;
            var endIndex = startIndex + itemsPerPage;
            notifyUserListRows.hide().slice(startIndex, endIndex).show();
        }

        // Function to go to the previous page for users
        function prevPage() {
            if (currentPage > 1) {
                currentPage--;
                showPage(currentPage);
            }
        }

        // Function to go to the next page for users
        function nextPage() {
            if (currentPage < Math.ceil(userListRows.length / itemsPerPage)) {
                currentPage++;
                showPage(currentPage);
            }
        }

        // Function to go to the previous page for notify users
        function prevNotifyPage() {
            if (currentPage > 1) {
                currentPage--;
                showNotifyPage(currentPage);
            }
        }

        // Function to go to the next page for notify users
        function nextNotifyPage() {
            if (currentPage < Math.ceil(notifyUserListRows.length / itemsPerPage)) {
                currentPage++;
                showNotifyPage(currentPage);
            }
        }

        // Show the first page initially for users
        showPage(currentPage);

        // Show the first page initially for notify users
        showNotifyPage(currentPage);
    </script>
@endsection
