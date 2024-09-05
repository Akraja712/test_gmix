@extends('layouts.admin')

@section('title', 'Chats Management')
@section('content-header', 'Chats Management')
@section('content-actions')
    <a href="{{route('chats.create')}}" class="btn btn-success"><i class="fas fa-plus"></i> Add New Chats</a>
@endsection
@section('css')
    <link rel="stylesheet" href="{{ asset('plugins/sweetalert2/sweetalert2.min.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/lightbox2/2.11.3/css/lightbox.min.css">
@endsection
@section('content')
<div class="card">
    <div class="card-body">
    <div class="row mb-4">
    <div class="col-md-8">
        <!-- User Filter Dropdown -->
        <form id="user-filter-form" action="{{ route('chats.index') }}" method="GET" class="form-inline">
            <div class="form-group">
                <label for="user-filter" class="mr-2">Filter by Users:</label>
                <select name="user_id" id="user-filter" class="form-control">
                    <option value="">All Users</option>
                    @foreach ($users as $user)
                        <option value="{{ $user->id }}" @if($user->id == request()->input('user_id')) selected @endif>{{ $user->name }}</option>
                    @endforeach
                </select>
            </div>
        </form>
    </div>

    <div class="col-md-4 text-right">
        <!-- Search Form -->
        <form action="{{ route('chats.index') }}" method="GET">
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
                        <th>Latest Message <i class="fas fa-sort"></i></th>
                        <th>DateTime<i class="fas fa-sort"></i></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($chats as $chat)
                    <tr>
                        <td>
                            <a href="{{ route('chats.edit', $chat) }}" class="btn btn-primary"><i class="fas fa-edit"></i></a>
                            <button class="btn btn-danger btn-delete" data-url="{{route('chats.destroy', $chat)}}"><i class="fas fa-trash"></i></button>
                        </td>
                        <td>{{$chat->id}}</td>
                        <td>
                            @if($chat->user && $chat->user->profile)
                                <a href="{{ asset('storage/app/public/users/' . $chat->user->profile) }}" data-lightbox="profile-{{ $chat->id }}">
                                    <img class="customer-img img-thumbnail img-fluid rounded-circle" src="{{ asset('storage/app/public/users/' . $chat->user->profile) }}" alt=""
                                    style="max-width: 100px; max-height: 100px;">
                                </a>
                            @else
                                <img class="customer-img img-thumbnail img-fluid rounded-circle" src="{{ asset('path/to/default/profile.png') }}" alt=""
                                style="max-width: 100px; max-height: 100px;">
                            @endif
                        </td>
                        <td>{{ optional($chat->user)->name }}</td> <!-- Display user name safely -->
                        <td>{{$chat->latest_message}}</td>
                        <td>{{$chat->datetime}}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        {{ $chats->render() }}
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
                    window.location.href = "{{ route('chats.index') }}";
                }
            });

            $(document).on('click', '.btn-delete', function () {
                $this = $(this);
                const swalWithBootstrapButtons = Swal.mixin({
                    customClass: {
                        confirmButton: 'btn btn-success',
                        cancelButton: 'btn btn-danger'
                    },
                    buttonsStyling: false
                });

                swalWithBootstrapButtons.fire({
                    title: 'Are you sure?',
                    text: "Do you really want to delete this chat?",
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
                            });
                        });
                    }
                });
            });

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
