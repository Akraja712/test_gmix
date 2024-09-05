@extends('layouts.admin')

@section('title', 'Create Products')
@section('content-header', 'Create Products')
@section('content-actions')
    <a href="{{route('products.index')}}" class="btn btn-success"><i class="fas fa-back"></i>Back To Products</a>
@endsection
@section('content')

    <div class="card">
        <div class="card-body">

            <form action="{{ route('products.store') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <div class="form-group">
                    <label for="name">Name</label>
                    <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                           id="name"
                           placeholder="name" value="{{ old('name') }}">
                    @error('name')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="unit">Unit</label>
                    <input type="text" name="unit" class="form-control @error('unit') is-invalid @enderror"
                           id="unit"
                           placeholder="unit" value="{{ old('unit') }}">
                    @error('unit')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="measurement">Measurement</label>
                    <input type="number" name="measurement" class="form-control @error('measurement') is-invalid @enderror" id="offer_percentage"
                           placeholder="Measurement" value="{{ old('measurement') }}">
                    @error('measurement')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                    @enderror
                </div>
                
                <div class="form-group">
                    <label for="price">Price</label>
                    <input type="number" name="price" class="form-control @error('price') is-invalid @enderror"
                           id="price"
                           placeholder="price" value="{{ old('price') }}">
                    @error('price')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="delivery_charges">Delivery Charges</label>
                    <input type="number" name="delivery_charges" class="form-control @error('delivery_charges') is-invalid @enderror"
                           id="delivery_charges"
                           placeholder="Delivery Charges" value="{{ old('delivery_charges') }}">
                    @error('delivery_charges')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="image">Image</label>
                    <div class="custom-file">
                        <input type="file" class="custom-file-input" name="image" id="image" onchange="updateProfileLabel(this)">
                        <label class="custom-file-label" for="image" id="image-label">Choose File</label>
                    </div>
                    @error('image')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                    @enderror
                </div>
              

                <button class="btn btn-success btn-block btn-lg" type="submit">Submit</button>
            </form>
        </div>
    </div>
@endsection

@section('js')
    <script src="{{ asset('plugins/bs-custom-file-input/bs-custom-file-input.min.js') }}"></script>
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
