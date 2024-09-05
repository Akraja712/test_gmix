@extends('layouts.admin')

@section('title', 'Create Addresses')
@section('content-header', 'Create Addresses')
@section('content-actions')
    <a href="{{route('addresses.index')}}" class="btn btn-success"><i class="fas fa-back"></i>Back To Addresses</a>
@endsection
@section('content')

    <div class="card">
        <div class="card-body">

            <form action="{{ route('addresses.store') }}" method="POST" enctype="multipart/form-data">
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
                    <label for="mobile">Mobile</label>
                    <input type="number" name="mobile" class="form-control @error('mobile') is-invalid @enderror" id="mobile"
                           placeholder="mobile" value="{{ old('mobile') }}">
                    @error('mobile')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="alternate_mobile">Alternate Mobile</label>
                    <input type="number" name="alternate_mobile" class="form-control @error('alternate_mobile') is-invalid @enderror" id="alternate_mobile"
                           placeholder="alternate_mobile" value="{{ old('alternate_mobile') }}">
                    @error('alternate_mobile')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="door_no">Door No</label>
                    <input type="text" name="door_no" class="form-control @error('door_no') is-invalid @enderror" id="door_no"
                           placeholder="Door No" value="{{ old('door_no') }}">
                    @error('door_no')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                    @enderror
                </div>


                <div class="form-group">
                    <label for="street_name">Street Name</label>
                    <input type="text" name="street_name" class="form-control @error('street_name') is-invalid @enderror" id="street_name"
                           placeholder="Street Name" value="{{ old('street_name') }}">
                    @error('street_name')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="state">State</label>
                    <input type="text" name="state" class="form-control @error('state') is-invalid @enderror" id="state"
                           placeholder="state" value="{{ old('state') }}">
                    @error('state')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                    @enderror
                </div>


                <div class="form-group">
                    <label for="pincode">Pincode</label>
                    <input type="number" name="pincode" class="form-control @error('pincode') is-invalid @enderror" id="pincode"
                           placeholder="pincode" value="{{ old('pincode') }}">
                    @error('pincode')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="city">City</label>
                    <input type="text" name="city" class="form-control @error('city') is-invalid @enderror" id="city"
                           placeholder="city" value="{{ old('city') }}">
                    @error('city')
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
