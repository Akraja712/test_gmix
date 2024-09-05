@extends('layouts.admin')

@section('title', 'Fakechats Management')
@section('content-header', 'Fakechats Management')
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
            <div class="col-md-8 d-flex align-items-center">
                <!-- Checkbox for Select All -->
                <div class="form-check mr-3">
                    <input type="checkbox" class="form-check-input" id="checkAll">
                    <label class="form-check-label" for="checkAll">Select All</label>
                </div>
                
                <!-- Verify Button -->
                <button class="btn btn-danger mr-3" id="verifyButton">Add Fake</button>
                <button class="btn btn-primary mr-3" id="NotFakeButton">Add Not-Fake</button>
                <!-- Filter by Status -->
                <div class="form-group mb-0 d-flex align-items-center">
                    <label for="status-filter" class="mr-2 mb-0">Filter by status:</label>
                    <select name="status" id="status-filter" class="form-control">
                    <option value="0" {{ request()->input('status') === '0' ? 'selected' : '' }}>Not-Fake</option>
                        <option value="1" {{ request()->input('status') === '1' ? 'selected' : '' }}>Fake</option>
                    </select>
                </div>
            </div>
            
            <div class="col-md-4 text-md-right mt-3 mt-md-0">
                <!-- Search Form -->
                <form action="{{ route('fakechats.index') }}" method="GET" class="form-inline">
                    <div class="input-group">
                        <input type="text" name="search" class="form-control" placeholder="Search by....">
                        <div class="input-group-append">
                            <button type="submit" class="btn btn-outline-secondary">Search</button>
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
                        <th>Checkbox</th>
                        <th>ID <i class="fas fa-sort"></i></th>
                        <th>User Profile <i class="fas fa-sort"></i></th>
                        <th>User Name <i class="fas fa-sort"></i></th>
                        <th>User Email <i class="fas fa-sort"></i></th>
                        <th>Status <i class="fas fa-sort"></i></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($fakechats as $fakechat)
                    <tr>
                    <td>
                            <button class="btn btn-danger btn-delete" data-url="{{route('fakechats.destroy', $fakechat)}}"><i class="fas fa-trash"></i></button>
                        </td>
                        <td><input type="checkbox" class="checkbox" data-id="{{ $fakechat->id }}"></td>
                        <td>{{ $fakechat->id }}</td>
                        <td>
                            @if($fakechat->user && $fakechat->user->profile)
                                <a href="{{ asset('storage/app/public/users/' . $fakechat->user->profile) }}" data-lightbox="profile-{{ $fakechat->user->id }}">
                                    <img class="customer-img img-thumbnail img-fluid" src="{{ asset('storage/app/public/users/' . $fakechat->user->profile) }}" alt=""
                                        style="max-width: 100px; max-height: 100px;">
                                </a>
                            @else
                                No Image
                            @endif
                        </td>
                        <td>{{ optional($fakechat->user)->name }}</td>
                        <td>{{ optional($fakechat->user)->email }}</td>
                        <td>
                            <span class="{{ $fakechat->status == 1 ? 'status-fake' : 'status-not-fake' }}">
                                {{ $fakechat->status == 1 ? 'Fake' : 'Not-Fake' }}
                            </span>
                        </td>

                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        {{ $fakechats->render() }}
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
                    window.location.href = "{{ route('fakechats.index') }}";
                }
            });
        });
            </script>
            <script>

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
        })
    </script>

<script>
$(document).ready(function() {
    // Handle "Select All" checkbox
    $('#checkAll').change(function() {
        $('.checkbox').prop('checked', $(this).prop('checked'));
    });

    // Handle Verify Button click (Add Fake)
    $('#verifyButton').click(function() {
        var fakechatIds = [];
        $('.checkbox:checked').each(function() {
            fakechatIds.push($(this).data('id'));
        });

        if (fakechatIds.length > 0) {
            // AJAX call to backend to set status as Fake
            $.ajax({
                url: "{{ route('fakechats.verify') }}",
                method: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                    fakechat_ids: fakechatIds
                },
                success: function(response) {
                    // Handle success response
                    alert('Fake Added successfully!');
                    location.reload(); // Reload the page or update UI as needed
                },
                error: function(xhr, status, error) {
                    // Handle error response
                    console.error(error);
                    alert('Error updating status. Please try again.');
                }
            });
        } else {
            alert('Please select at least one fakechat.');
        }
    });

    // Handle Not-Fake Button click
    $('#NotFakeButton').click(function() {
        var fakechatIds = [];
        $('.checkbox:checked').each(function() {
            fakechatIds.push($(this).data('id'));
        });

        if (fakechatIds.length > 0) {
            // AJAX call to backend to set status as Not-Fake
            $.ajax({
                url: "{{ route('fakechats.notFake') }}",
                method: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                    fakechat_ids: fakechatIds
                },
                success: function(response) {
                    // Handle success response
                    alert('Not-Fake Added successfully!');
                    location.reload(); // Reload the page or update UI as needed
                },
                error: function(xhr, status, error) {
                    // Handle error response
                    console.error(error);
                    alert('Error updating status. Please try again.');
                }
            });
        } else {
            alert('Please select at least one fakechat.');
        }
    });

    // Handle status filter change
    $('#status-filter').change(function() {
        var status = $(this).val();
        var url = "{{ route('fakechats.index') }}";
        if (status) {
            url += '?status=' + status;
        }
        window.location.href = url;
    });
});
</script>



@endsection
