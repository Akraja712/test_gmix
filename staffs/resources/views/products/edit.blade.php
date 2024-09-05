@extends('layouts.admin')

@section('title', 'Update Products')
@section('content-header', 'Update Products')
@section('content-actions')
    <a href="{{route('products.index')}}" class="btn btn-success"><i class="fas fa-back"></i>Back To Products</a>
@endsection
@section('content')

    <div class="card">
        <div class="card-body">

        <form action="{{ route('products.update', $product) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

                <div class="form-group">
                    <label for="name">Name</label>
                    <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                           id="name"
                           placeholder="Name" value="{{ old('name', $product->name) }}">
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
                           placeholder="unit" value="{{ old('unit', $product->unit) }}">
                    @error('unit')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                    @enderror 
                </div>

                <div class="form-group">
                    <label for="measurement">Measurement</label>
                    <input type="number" name="measurement" class="form-control @error('measurement') is-invalid @enderror"
                           id="measurement"
                           placeholder="Measurement" value="{{ old('measurement', $product->measurement) }}">
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
                           placeholder="price" value="{{ old('price', $product->price) }}">
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
                           placeholder="Delivery Charges" value="{{ old('delivery_charges', $product->delivery_charges) }}">
                    @error('delivery_charges')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                    @enderror 
                </div>

                <div class="form-group">
                    <span>Current Image:</span>
                    <img src="{{ asset('storage/app/public/products/' . $product->image) }}" alt="{{ $product->name }}" style="max-width: 100px; max-height: 100px;">
                    <br>
                    <label for="image">New Image</label>
                    <div class="custom-file">
                        <input type="file" class="custom-file-input" name="image" id="image">
                        <label class="custom-file-label" for="image">Choose file</label>
                        @if($product->image)
                            <input type="hidden" name="existing_image" value="{{ $product->image }}">
                        @endif
                    </div>
                    @error('image')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                    @enderror
                </div>

                <button class="btn btn-success btn-block btn-lg" type="submit">Save Changes</button>
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
    </script>
      <script>
document.addEventListener('DOMContentLoaded', function () {
    const fileInput = document.getElementById('image');
    const fileInputLabel = fileInput.nextElementSibling;

    fileInput.addEventListener('change', function () {
        const fileName = this.files[0].name;
        fileInputLabel.textContent = fileName;
    });
});
</script>
@endsection
