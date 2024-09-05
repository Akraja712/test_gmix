@extends('layouts.admin')

@section('title', 'Update Trips')
@section('content-header', 'Update Trips')
@section('content-actions')
    <a href="{{ route('trips.index') }}?search=&trip_status=0"  class="btn btn-success"><i class="fas fa-back"></i>Back To Trip</a>
@endsection
@section('content')

    <div class="card">
        <div class="card-body">

            <form action="{{ route('trips.update', $trips) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')


                <div class="form-group">
                    <label for="user_id">User ID</label>
                    <input type="number" name="user_id" class="form-control @error('user_id') is-invalid @enderror"
                           id="user_id"
                           placeholder="User ID" value="{{ old('user_id', $trips->user_id) }}">
                    @error('user_id')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                    @enderror
                </div>

                <button type="button" class="btn btn-primary" onclick="toggleUserListModal()">Select User</button>

                <div class="form-group">
                    <br>
                        <label for="trip_type">Trip Type</label>
                        <select name="trip_type" class="form-control @error('trip_type') is-invalid @enderror" id="profession">
                            <option value=''>--select--</option>
                            <option value='Road Trip' {{ old('trip_type', $trips->trip_type) == 'Road Trip' ? 'selected' : '' }}>Road Trip</option>
                            <option value='Adventure Trip' {{ old('trip_type', $trips->trip_type) == 'Adventure Trip' ? 'selected' : '' }}>Adventure Trip</option>
                            <option value='Explore Cities' {{ old('trip_type', $trips->trip_type) == 'Explore Cities' ? 'selected' : '' }}>Explore Cities</option>
                            <option value='Airport Flyover' {{ old('trip_type', $trips->trip_type) == 'Airport Flyover' ? 'selected' : '' }}>Airport Flyover</option>
                        </select>
                        @error('trip_type')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror
                    </div>


                <div class="form-group">
                    <label for="location">Location</label>
                    <input type="text" name="location" class="form-control @error('location') is-invalid @enderror"
                           id="location"
                           placeholder="From Location" value="{{ old('location', $trips->location) }}">
                    @error('location')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                    @enderror
                </div>
               
                <div class="form-group">
                    <label for="date">From Date</label>
                    <input type="date" name="from_date" class="form-control @error('from_date') is-invalid @enderror" id="mobile"
                           placeholder="From Date" value="{{ old('from_date', $trips->from_date) }}">
                    @error('from_date')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="date">To Date</label>
                    <input type="date" name="to_date" class="form-control @error('to_date') is-invalid @enderror" id="mobile"
                           placeholder="To Date" value="{{ old('to_date', $trips->to_date) }}">
                    @error('to_date')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="trip_title">Trip Title</label>
                    <input type="text" name="trip_title" class="form-control @error('trip_title') is-invalid @enderror"
                           id="trip_title"
                           placeholder="Trip Title" value="{{ old('trip_title', $trips->trip_title) }}">
                    @error('trip_title')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                    @enderror
                </div>

                <div class="form-group">
    <label for="trip_description">Trip Description</label>
    <textarea name="trip_description" class="form-control @error('trip_description') is-invalid @enderror"
              id="trip_description" rows="3" placeholder="Trip Description">{{ old('trip_description', $trips->trip_description) }}</textarea>
    @error('trip_description')
    <span class="invalid-feedback" role="alert">
        <strong>{{ $message }}</strong>
    </span>
    @enderror
</div>
<div class="form-group">
    <span>Current Trip Image:</span>
    <img src="{{ asset('storage/app/public/trips/' . $trips->trip_image) }}" alt="{{ $trips->name }}" style="max-width: 100px; max-height: 100px;">
    <br>
    <label for="trip_image">New Trip Image</label>
    <div class="custom-file">
        <input type="file" class="custom-file-input" name="trip_image" id="trip_image">
        <label class="custom-file-label" for="trip_image">Choose file</label>
        @if($trips->trip_image)
            <input type="hidden" name="existing_trip_image" value="{{ $trips->trip_image }}">
        @endif
    </div>
    @error('trip_image')
    <span class="invalid-feedback" role="alert">
        <strong>{{ $message }}</strong>
    </span>
    @enderror
</div>


                <div class="form-group">
    <label for="trip_status">Trip Status</label>
    <div class="btn-group btn-group-toggle d-block" data-toggle="buttons">
        <label class="btn btn-outline-success {{ old('trip_status', $trips->trip_status) === 1 ? 'active' : '' }}">
            <input type="radio" name="trip_status" id="status_activated" value="1" {{ old('trip_status', $trips->trip_status) === 1 ? 'checked' : '' }}> Approved
        </label>
        <label class="btn btn-outline-primary {{ old('trip_status', $trips->trip_status) === 0 ? 'active' : '' }}">
            <input type="radio" name="trip_status" id="status_pending" value="0" {{ old('trip_status', $trips->trip_status) === 0 ? 'checked' : '' }}> Pending
        </label>
        <label class="btn btn-outline-danger {{ old('trip_status', $trips->trip_status) === 2 ? 'active' : '' }}">
            <input type="radio" name="trip_status" id="status_cancelled" value="2" {{ old('trip_status', $trips->trip_status) === 2 ? 'checked' : '' }}> Cancelled
        </label>
    </div>
    @error('trip_status')
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
                                <input class="form-check-input" type="checkbox" name="selected_user_id" value="{{ $user->id }}" onclick="selectUser(this)" {{ $user->id == $trips->user_id ? 'checked' : '' }}>
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

@endsection
@section('js')
    <!-- Include any additional JavaScript if needed -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        // Define variables for pagination
        var currentPage = 1;
        var itemsPerPage = 10; // Change this value as needed
        var userListRows = $('#userTable tbody tr');

        // Function to toggle the user list modal
        function toggleUserListModal() {
            $('.modal').toggle(); // Toggle the modal
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

        // Function to handle checkbox click and update user_id input
        function selectUser(checkbox) {
    // Deselect all checkboxes
    $('input[name="selected_user_id"]').prop('checked', false);
    // Select only the clicked checkbox
    $(checkbox).prop('checked', true);
    // Set its value to the user_id input field
    $('#user_id').val(checkbox.value);
}
        // Function to show the specified page of users
        function showPage(page) {
            var startIndex = (page - 1) * itemsPerPage;
            var endIndex = startIndex + itemsPerPage;
            userListRows.hide().slice(startIndex, endIndex).show();
        }

        // Function to go to the previous page
        function prevPage() {
            if (currentPage > 1) {
                currentPage--;
                showPage(currentPage);
            }
        }

        // Function to go to the next page
        function nextPage() {
            if (currentPage < Math.ceil(userListRows.length / itemsPerPage)) {
                currentPage++;
                showPage(currentPage);
            }
        }

        // Show the first page initially
        showPage(currentPage);
    </script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
    var btns = document.querySelectorAll('.btn-group-toggle .btn');
    btns.forEach(function (btn) {
        btn.addEventListener('click', function () {
            btns.forEach(function (btn) {
                btn.classList.remove('active');
            });
            this.classList.add('active');
        });
    });
});

    </script>
      <script>
document.addEventListener('DOMContentLoaded', function () {
    const fileInput = document.getElementById('trip_image');
    const fileInputLabel = fileInput.nextElementSibling;

    fileInput.addEventListener('change', function () {
        const fileName = this.files[0].name;
        fileInputLabel.textContent = fileName;
    });
});
</script>
    @endsection