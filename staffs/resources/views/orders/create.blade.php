@extends('layouts.admin')

@section('title', 'Create Orders')
@section('content-header', 'Create Orders')
@section('content-actions')
    <a href="{{route('orders.index')}}" class="btn btn-success"><i class="fas fa-back"></i>Back To Orders</a>
@endsection
@section('content')

    <div class="card">
        <div class="card-body">

            <form action="{{ route('orders.store') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <div class="form-group">
                    <label for="user_id">User ID</label>
                    <input type="text" name="user_id" class="form-control @error('user_id') is-invalid @enderror"
                           id="user_id" placeholder="User ID" value="{{ old('user_id') }}">
                    @error('user_id')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                    @enderror
                </div>

                <button type="button" class="btn btn-primary" onclick="toggleUserListModal()">Select User</button>

                <div class="form-group">
    <label for="address_id">Address ID</label>
    <select name="address_id" class="form-control @error('address_id') is-invalid @enderror" id="address_id">
        <option value=''>--select--</option>
        <!-- Options will be populated based on the selected user -->
    </select>
    @error('address_id')
    <span class="invalid-feedback" role="alert">
        <strong>{{ $message }}</strong>
    </span>
    @enderror
</div>


                <div class="form-group">
                    <label for="product_id">Product ID</label>
                    <select name="product_id" class="form-control @error('product_id') is-invalid @enderror" id="product_id">
                        <option value=''>--select--</option>
                        @foreach($products as $product)
                            <option value="{{ $product->id }}" {{ old('product_id') == $product->id ? 'selected' : '' }}>
                                {{ $product->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('product_id')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                    @enderror
                </div>

                 <div class="form-group">
                    <label for="price">Price</label>
                    <input type="number" name="price" class="form-control @error('price') is-invalid @enderror" id="price"
                           placeholder="price" value="{{ old('price') }}">
                    @error('price')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                    @enderror
                 </div>

                 <div class="form-group">
                    <label for="delivery_charges">Delivery Charges</label>
                    <input type="number" name="delivery_charges" class="form-control @error('delivery_charges') is-invalid @enderror" id="delivery_charges"
                           placeholder="Delivery Charges" value="{{ old('delivery_charges') }}">
                    @error('delivery_charges')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                    @enderror
                 </div>

                 <div class="form-group">
                    <label for="payment_mode">Payment Mode</label>
                    <select name="payment_mode" class="form-control @error('payment_mode') is-invalid @enderror" id="payment_mode">
                        <option value="prepaid" {{ old('payment_mode') == 'prepaid' ? 'selected' : '' }}>prepaid</option>
                        <option value="cod" {{ old('payment_mode') == 'cod' ? 'selected' : '' }}>cod</option>
                    </select>
                    @error('payment_mode')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                    @enderror
                </div>

                <button class="btn btn-success btn-block btn-lg" type="submit">Submit</button>
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
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($users as $user)
                        <tr>
                             <td>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="selected_user_id" value="{{ $user->id }}" onclick="selectUser(this)">
                                </div>
                            </td>
                            <td>{{ $user->id }}</td>
                            <td>{{ $user->name }}</td>
                            <td>{{ $user->mobile }}</td>
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

        function selectUser(checkbox) {
        // Deselect all checkboxes
        $('input[name="selected_user_id"]').prop('checked', false);
        // Select only the clicked checkbox
        $(checkbox).prop('checked', true);
        // Set its value to the user_id input field
        $('#user_id').val(checkbox.value);

        // Fetch the addresses for the selected user
        fetchUserAddresses(checkbox.value);
    }
    function fetchUserAddresses(userId) {
    $.ajax({
        url: '{{ url("/user-addresses") }}/' + userId,
        method: 'GET',
        success: function(data) {
            var addressSelect = $('#address_id');
            addressSelect.empty(); // Clear existing options
            addressSelect.append('<option value="">--select--</option>'); // Add default option

            data.forEach(function(address) {
                addressSelect.append('<option value="' + address.id + '">' + address.name + '</option>');
            });
        },
        error: function(xhr) {
            console.error('Error fetching addresses:', xhr);
        }
    });
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
        $(document).ready(function () {
            bsCustomFileInput.init();
        });

        function updateProfileLabel(input) {
            var fileName = input.files[0].name;
            var label = $(input).siblings('.custom-file-label');
            label.text(fileName);
        }
    </script>
@endsection
