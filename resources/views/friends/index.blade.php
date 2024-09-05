@extends('layouts.admin')

@section('title', 'Friends Management')
@section('content-header', 'Friends Management')
@section('content-actions')
@endsection
@section('css')
    <link rel="stylesheet" href="{{ asset('plugins/sweetalert2/sweetalert2.min.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/lightbox2/2.11.3/css/lightbox.min.css">
@endsection
@section('content')
<div class="card">
    <div class="card-body">
    <div class="row mb-4">
        <div class="col-md-8"></div> <!-- Create a placeholder column to push the search box to the right -->
        <div class="col-md-4">
            <!-- Search Form -->
            <form action="{{ route('friends.index') }}" method="GET">
                <div class="input-group">
                    <input type="text" name="search" class="form-control" placeholder="Search by....">
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
                    <th>Profile <i class="fas fa-sort"></i></th>
                    <th>User Name <i class="fas fa-sort"></i></th>
                    <th>Friend User Id <i class="fas fa-sort"></i></th>
                    <th>Status <i class="fas fa-sort"></i></th>
                    <th>DateTime <i class="fas fa-sort"></i></th>
                </tr>
            </thead>
            <tbody>
                @foreach ($friends as $friend)
                <tr>
                    <td>
                        <button class="btn btn-danger btn-delete" data-url="{{route('friends.destroy', $friend)}}"><i class="fas fa-trash"></i></button>
                    </td>
                    <td>{{$friend->id}}</td>
                    <td>
                        <a href="{{ asset('storage/app/public/users/' . $friend->user->profile) }}" data-lightbox="profile-{{ $friend->id }}">
                            <img class="customer-img img-thumbnail img-fluid rounded-circle" src="{{ asset('storage/app/public/users/' . $friend->user->profile) }}" alt="" style="max-width: 100px; max-height: 100px;">
                        </a>
                    </td>
                    <td>{{ optional($friend->user)->name }}</td> <!-- Display user name safely -->
                    <td>{{$friend->friend_user_id}}</td>
                    <td>{{ $friend->status == 1 ? 'Interested' : 'Not Interested' }}</td>
                    <td>{{$friend->datetime}}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    {{ $friends->render() }}
    </div>
</div>
@endsection

@section('js')
    <script src="{{ asset('plugins/sweetalert2/sweetalert2.min.js') }}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/lightbox2/2.11.3/js/lightbox.min.js"></script>
    <script>
        $(document).ready(function () {
            // Submit the form when user selection changes
            $('#user-filter').change(function () {
                if ($(this).val() !== '') {
                    $('#user-filter-form').submit();
                } else {
                    window.location.href = "{{ route('friends.index') }}";
                }
            });
        });

        $(document).ready(function () {
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
                    text: "Do you really want to delete this customer?",
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
            })
        });

        $(document).ready(function() {
            $('.table th').click(function() {
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
                return function(a, b) {
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
