@extends('layouts.admin')

@section('title', 'Verifications Management')
@section('content-header', 'Verifications Management')
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
                <button class="btn btn-primary mr-3" id="verifyButton">Verify</button>

                </div>
        </div>

        <div class="row mb-4">
            <div class="col-md-8 d-flex align-items-center">
                <!-- Filter by Status -->
                <div class="form-group mb-0 d-flex align-items-center">
                    <label for="status-filter"  class="mr-1 mb-0 flex-shrink-0">Filter by status:</label>
                    <select name="status" id="status-filter" class="form-control">
                        <option value="0" {{ request()->input('status') === '0' ? 'selected' : '' }}>Pending</option>
                        <option value="1" {{ request()->input('status') === '1' ? 'selected' : '' }}>Verified</option>
                    </select>
                </div>
                <div class="form-group mb-0 d-flex align-items-center">
                    <label for="payment_status-filter" class="mr-1 mb-0 flex-shrink-0">Filter by Payment status:</label>
                    <select name="payment_status" id="payment_status-filter" class="form-control">
                        <option value="0" {{ request()->input('payment_status') === '0' ? 'selected' : '' }}>Unpaid</option>
                        <option value="1" {{ request()->input('payment_status') === '1' ? 'selected' : '' }}>Paid</option>
                    </select>
                </div>
            </div>
            <div class="col-md-4 text-md-right mt-3 mt-md-0">
                <!-- Search Form -->
                <form id="search-form" class="form-inline">
                    <div class="input-group">
                        <input type="text" id="search-input" name="search" class="form-control" placeholder="Search by...." autocomplete="off" value="{{ request()->input('search') }}">
                        <div class="input-group-append">
                            <button type="submit" class="btn btn-outline-secondary" style="display: none;">Search</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        </div>

        <div class="table-responsive">
            <table class="table table-bordered table-hover">
                <thead class="thead-dark">
                    <tr>
                        <th>Actions</th>
                        <th>Checkbox</th>
                        <th>ID <i class="fas fa-sort"></i></th>
                        <th>User Name <i class="fas fa-sort"></i></th>
                        <th>Plan Name <i class="fas fa-sort"></i></th>
                        <th>Selfie Image <i class="fas fa-sort"></i></th>
                        <th>Front Image <i class="fas fa-sort"></i></th>
                        <th>Back Image <i class="fas fa-sort"></i></th>
                        <th>Payment Image <i class="fas fa-sort"></i></th>
                        <th>Status <i class="fas fa-sort"></i></th>
                        <th>Payment Status <i class="fas fa-sort"></i></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($verifications as $verification)
                    <tr>
                        <td>
                            <a href="{{ route('verifications.edit', $verification) }}" class="btn btn-primary"><i class="fas fa-edit"></i></a>
                            <button class="btn btn-danger btn-delete" data-url="{{ route('verifications.destroy', $verification) }}"><i class="fas fa-trash"></i></button>
                        </td>
                        <td><input type="checkbox" class="checkbox" data-id="{{ $verification->id }}"></td>
                        <td>{{ $verification->id }}</td>
                        <td>{{ optional($verification->user)->name }}</td>
                        <td>{{ optional($verification->plan)->plan_name }}</td>
                        <td>
                            <a href="{{ asset('storage/app/public/verification/' . $verification->selfie_image) }}" data-lightbox="selfie_image-{{ $verification->id }}">
                                <img class="customer-img img-thumbnail img-fluid" src="{{ asset('storage/app/public/verification/' . $verification->selfie_image) }}" alt="" style="max-width: 100px; max-height: 100px;">
                            </a>
                        </td>
                        <td>
                            <a href="{{ asset('storage/app/public/verification/' . $verification->front_image) }}" data-lightbox="front_image-{{ $verification->id }}">
                                <img class="customer-img img-thumbnail img-fluid" src="{{ asset('storage/app/public/verification/' . $verification->front_image) }}" alt="" style="max-width: 100px; max-height: 100px;">
                            </a>
                        </td>
                        <td>
                            <a href="{{ asset('storage/app/public/verification/' . $verification->back_image) }}" data-lightbox="back_image-{{ $verification->id }}">
                                <img class="customer-img img-thumbnail img-fluid" src="{{ asset('storage/app/public/verification/' . $verification->back_image) }}" alt="" style="max-width: 100px; max-height: 100px;">
                            </a>
                        </td>
                        <td>
                            <a href="{{ asset('storage/app/public/verification/' . $verification->payment_image) }}" data-lightbox="payment_image-{{ $verification->id }}">
                                <img class="customer-img img-thumbnail img-fluid" src="{{ asset('storage/app/public/verification/' . $verification->payment_image) }}" alt="" style="max-width: 100px; max-height: 100px;">
                            </a>
                        </td>
                        <td>
                            <span class="{{ $verification->status == 1 ? 'text-enable' : 'text-disables' }}">
                                {{ $verification->status == 1 ? 'Verified' : 'Pending' }}
                            </span>
                        </td>
                        <td>
                            <span class="
                                {{ $verification->payment_status == 0 ? 'text-pending' : '' }}
                                {{ $verification->payment_status == 1 ? 'text-paid' : '' }}
                                {{ $verification->payment_status == 2 ? 'text-cancelled' : '' }}
                            ">
                                {{ $verification->payment_status == 0 ? 'Pending' : '' }}
                                {{ $verification->payment_status == 1 ? 'Paid' : '' }}
                                {{ $verification->payment_status == 2 ? 'Cancelled' : '' }}
                            </span>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        {{ $verifications->appends(request()->query())->links() }}
    </div>
</div>

@endsection

@section('js')
<script src="{{ asset('plugins/sweetalert2/sweetalert2.min.js') }}"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/lightbox2/2.11.3/js/lightbox.min.js"></script>
<script>
    $(document).ready(function () {
        // Handle search input
        $('#search-input').on('input', function () {
            filterVerifications();
        });

        // Handle status filter change
        $('#status-filter').change(function () {
            filterVerifications();
        });

        $('#payment_status-filter').change(function () {
            filterVerifications();
        });

        function filterVerifications() {
            let search = $('#search-input').val();
            let status = $('#status-filter').val();
            let payment_status = $('#payment_status-filter').val();
            let url = `{{ route('verifications.index') }}?search=${encodeURIComponent(search)}&status=${encodeURIComponent(status)}&payment_status=${encodeURIComponent(payment_status)}`;
            window.location.href = url;
        }

        // Handle delete button click
        $(document).on('click', '.btn-delete', function () {
            const $this = $(this);
            Swal.fire({
                title: 'Are you sure?',
                text: "Do you really want to delete this verification?",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Yes, delete it!',
                cancelButtonText: 'No',
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    $.post($this.data('url'), {_method: 'DELETE', _token: '{{ csrf_token() }}'}, function () {
                        $this.closest('tr').fadeOut(500, function () {
                            $(this).remove();
                        });
                    });
                }
            });
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

        // Handle "Select All" checkbox
        $('#checkAll').change(function() {
            $('.checkbox').prop('checked', $(this).prop('checked'));
        });

        // Handle Verify Button click
        $('#verifyButton').click(function() {
            var verificationIds = [];
            $('.checkbox:checked').each(function() {
                verificationIds.push($(this).data('id'));
            });

            if (verificationIds.length > 0) {
                // AJAX call to backend
                $.ajax({
                    url: "{{ route('verifications.verify') }}",
                    method: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}',
                        verification_ids: verificationIds
                    },
                    success: function(response) {
                        // Handle success response
                        alert('Verified successfully!');
                        location.reload(); // Reload the page or update UI as needed
                    },
                    error: function(xhr, status, error) {
                        // Handle error response
                        console.error(error);
                        alert('Error updating verifications. Please try again.');
                    }
                });
            } else {
                alert('Please select at least one verification.');
            }
        });
    });
</script>
@endsection
