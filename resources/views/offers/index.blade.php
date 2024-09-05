@extends('layouts.admin')

@section('title', 'Offer Management')
@section('content-header', 'Offer Management')
@section('content-actions')
    <a href="{{route('offers.create')}}" class="btn btn-success"><i class="fas fa-plus"></i> Add New offer</a>
@endsection
@section('css')
    <link rel="stylesheet" href="{{ asset('plugins/sweetalert2/sweetalert2.min.css') }}">
@endsection
@section('content')
<div class="card">
    <div class="card-body">
    
        <div class="table-responsive">
            <table class="table table-bordered table-hover">
                <thead class="thead-dark">
                    <tr>
                        <th>ID</th>
                        <th>Offer Image</th>
                        <th>Shop Name</th>
                        <th>Title</th>
                        <th>Description</th>
                        <th>Base Price</th>
                        <th>Valid Date</th>
                        <th>Max Users</th>
                        <th>Availablity</th>
                        <th>DateTime</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($offers as $offer)
                    <tr>
                        <td>{{$offer->id}}</td>
                        <td><img class="customer-img img-thumbnail img-fluid" src="{{ asset('storage/app/public/offers/' . $offer->image) }}" alt="" style="max-width: 100px; max-height: 100px;"></td>
                        <td>{{ optional($offer->shop)->shop_name }}</td> <!-- Display shop name safely -->
                        <td>{{$offer->title}}</td>
                        <td>{{$offer->description}}</td>
                        <td>{{$offer->base_price}}</td>
                        <td>{{$offer->valid_date}}</td>
                        <td>{{$offer->max_users}}</td>
                        <td class="{{ $offer->availablity == 'disable' ? 'text-danger' : 'text-success' }}">
                         {{ $offer->availablity == 'disable' ? 'Disable' : 'Enable' }}
                       </td>
                        <td>{{$offer->datetime}}</td>
                        <td>
                            <a href="{{ route('offers.edit', $offer) }}" class="btn btn-primary"><i class="fas fa-edit"></i></a>
                            <button class="btn btn-danger btn-delete" data-url="{{route('offers.destroy', $offer)}}"><i class="fas fa-trash"></i></button>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        {{ $offers->render() }}
    </div>
</div>

@endsection

@section('js')
    <script src="{{ asset('plugins/sweetalert2/sweetalert2.min.js') }}"></script>
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
@endsection
