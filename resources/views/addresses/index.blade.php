@extends('layouts.admin')

@section('title', 'Addresses Management')
@section('content-header', 'Addresses Management')
@section('content-actions')
    <a href="{{ route('addresses.create') }}" class="btn btn-success"><i class="fas fa-plus"></i> Add New Addresses</a>
@endsection

@section('css')
    <link rel="stylesheet" href="{{ asset('plugins/sweetalert2/sweetalert2.min.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/lightbox2/2.11.3/css/lightbox.min.css">
@endsection

@section('content')
<div class="card">
    <div class="card-body">
    <div class="row mb-4">
        <div class="ml-auto">
        <form id="search-form" action="{{ route('addresses.index') }}" method="GET">
                    <div class="input-group">
                        <input type="text" id="search-input" name="search" class="form-control" placeholder="Search by..." autocomplete="off" value="{{ request()->input('search') }}">
                        <div class="input-group-append">
                            <button class="btn btn-primary" type="submit" style="display: none;">Search</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        <div class="table-responsive">
            <table class="table table-bordered table-hover">
                <thead class="thead-dark">
                    <tr>
                    <th>Actions</th>
                    <th>ID <i class="fas fa-sort"></i></th>
                    <th>User Name</th>
                    <th>Fisrt Name</th>
                    <th>Last Name</th>
                    <th>Mobile</th>
                    <th>Alternate Mobile</th>
                    <th>Door No</th>
                    <th>Street Name</th>
                    <th>State</th>
                    <th>Pincode</th>
                    <th>City</th>
                    <th>Landmark</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($addresses as $addres)
                    <tr>
                    <td>
                            <a href="{{ route('addresses.edit', $addres) }}" class="btn btn-primary"><i class="fas fa-edit"></i></a>
                            <button class="btn btn-danger btn-delete" data-url="{{route('addresses.destroy', $addres)}}"><i class="fas fa-trash"></i></button>
                        </td>
                        <td>{{$addres->id}}</td>
                        <td>{{ optional($addres->users)->name }}</td>
                        <td>{{$addres->first_name}}</td>
                        <td>{{$addres->last_name}}</td>
                        <td>{{$addres->mobile}}</td>
                        <td>{{$addres->alternate_mobile}}</td>
                        <td>{{$addres->door_no}}</td>
                        <td>{{$addres->street_name}}</td>
                        <td>{{$addres->state}}</td>
                        <td>{{$addres->pincode}}</td>
                        <td>{{$addres->city}}</td>
                        <td>{{$addres->landmark}}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="d-flex align-items-center">
    <!-- Total addresses count -->
    <p class="mb-0 mr-2">
        Showing {{ $addresses->firstItem() }} to {{ $addresses->lastItem() }} of {{ $addresses->total() }} entries
    </p>

    <!-- Dropdown for selecting number of items per page -->
    <form method="GET" action="{{ url()->current() }}" class="d-inline mb-0">
        <div class="form-group mb-0">
            <label for="perPage" class="sr-only">Show</label>
            <select name="perPage" id="perPage" class="form-control form-control-sm" onchange="this.form.submit()" style="width: 60px;">
                @foreach([5, 10, 20, 50, 100, 200] as $size)
                    <option value="{{ $size }}" {{ $size == $perPage ? 'selected' : '' }}>
                        {{ $size }}
                    </option>
                @endforeach
            </select>
        </div>
        <!-- Preserve search and other filters -->
        <input type="hidden" name="search" value="{{ request('search') }}">
    </form>
</div>

<!-- Pagination links -->
<div class="pagination mt-3">
    {{ $addresses->appends(request()->query())->links() }}
</div>


@endsection

@section('js')
    <script src="{{ asset('plugins/sweetalert2/sweetalert2.min.js') }}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/lightbox2/2.11.3/js/lightbox.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
   $(document).ready(function () {
    // Function to get URL parameters
    function getQueryParams() {
        const params = {};
        window.location.search.substring(1).split("&").forEach(function (pair) {
            const [key, value] = pair.split("=");
            params[key] = decodeURIComponent(value);
        });
        return params;
    }

    // Load initial parameters
    const queryParams = getQueryParams();
    $('#search-input').val(queryParams.search || '');

    // Handle search input with debounce
    let debounceTimeout;
    $('#search-input').on('input', function () {
        clearTimeout(debounceTimeout);
        debounceTimeout = setTimeout(function () {
            filterUsers();
        }, 300); // Adjust delay as needed
    });

    let debounceTimer;

function filterUsers() {
    clearTimeout(debounceTimer);

    debounceTimer = setTimeout(function() {
        let search = $('#search-input').val();

        window.location.search = `search=${encodeURIComponent(search)}`;
    }, 500); // Adjust the delay (in milliseconds) as needed
}

$('#search-input').on('input', filterUsers);
        // Handle delete button click
        $(document).on('click', '.btn-delete', function () {
            $this = $(this);
            const swalWithBootstrapButtons = Swal.mixin({
                customClass: {
                    confirmButton: 'btn btn-success',
                    cancelButton: 'btn btn-danger'
                },
                buttonsStyling: false
            })

            swalWithBootstrapButtons.fire({
                title: 'Are you sure?',
                text: "Do you really want to delete this user?",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Yes, delete it!',
                cancelButtonText: 'No',
                reverseButtons: true
            }).then((result) => {
                if (result.value) {
                    $.post($this.data('url'), {_method: 'DELETE', _token: '{{csrf_token()}}'}, function (res) {
                        $this.closest('tr').fadeOut(500, function () {
                            $(this).remove();
                        })
                    })
                }
            })
        });

        // Handle table sorting
        $('.table th').click(function () {
            var table = $(this).parents('table').eq(0);
            var index = $(this).index();
            var rows = table.find('tr:gt(0)').toArray().sort(comparer(index));
            this.asc = !this.asc;
            if (!this.asc) {
                rows = rows.reverse();
            }
            for (var i = 0; i < rows.length; i++) {
                table.append(rows[i]);
            }
            // Update arrows
            updateArrows(table, index, this.asc);
        });

        function comparer(index) {
            return function (a, b) {
                var valA = getCellValue(a, index),
                    valB = getCellValue(b, index);
                return $.isNumeric(valA) && $.isNumeric(valB) ? valA - valB : valA.localeCompare(valB);
            };
        }

        function getCellValue(row, index) {
            return $(row).children('td').eq(index).text();
        }

        function updateArrows(table, index, asc) {
            table.find('.arrow').remove();
            var arrow = asc ? '<i class="fas fa-arrow-up arrow"></i>' : '<i class="fas fa-arrow-down arrow"></i>';
            table.find('th').eq(index).append(arrow);
        }
    });
    </script>
@endsection
