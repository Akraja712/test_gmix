@extends('layouts.admin')

@section('title', 'Update Orders')
@section('content-header', 'Update Orders')
@section('content-actions')
    <a href="{{ route('orders.index') }}" class="btn btn-success"><i class="fas fa-back"></i> Back To Orders</a>
@endsection

@section('content')

    <div class="card">
        <div class="card-body">

            <form action="{{ route('orders.update', $order) }}" method="POST">
                @csrf
                @method('PUT')
                <input type="hidden" name="address_id" value="{{ $addresses->id }}">

                <div class="form-group">
                    <label for="first_name">First Name</label>
                    <input type="text" name="first_name" class="form-control @error('first_name') is-invalid @enderror"
                           id="first_name"
                           placeholder="First Name" value="{{ old('first_name', $addresses->first_name) }}">
                    @error('first_name')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="last_name">Last Name</label>
                    <input type="text" name="last_name" class="form-control @error('last_name') is-invalid @enderror"
                        id="last_name"
                        placeholder="Last Name" value="{{ old('last_name', $addresses->last_name) }}">
                    @error('last_name')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="mobile">Mobile</label>
                    <input type="text" name="mobile" class="form-control @error('mobile') is-invalid @enderror"
                           id="mobile"
                           placeholder="Mobile" value="{{ old('mobile', $addresses->mobile) }}">
                    @error('mobile')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="alternate_mobile">Alternate Mobile</label>
                    <input type="text" name="alternate_mobile" class="form-control @error('alternate_mobile') is-invalid @enderror"
                           id="alternate_mobile"
                           placeholder="Alternate Mobile" value="{{ old('alternate_mobile', $addresses->alternate_mobile) }}">
                    @error('alternate_mobile')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="door_no">Door No</label>
                    <input type="text" name="door_no" class="form-control @error('door_no') is-invalid @enderror"
                           id="door_no"
                           placeholder="Door No" value="{{ old('door_no', $addresses->door_no) }}">
                    @error('door_no')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="street_name">Street Name</label>
                    <input type="text" name="street_name" class="form-control @error('street_name') is-invalid @enderror"
                           id="street_name"
                           placeholder="Street Name" value="{{ old('street_name', $addresses->street_name) }}">
                    @error('street_name')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="city">City</label>
                    <input type="text" name="city" class="form-control @error('city') is-invalid @enderror"
                           id="city"
                           placeholder="City" value="{{ old('city', $addresses->city) }}">
                    @error('city')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="pincode">Pincode</label>
                    <input type="text" name="pincode" class="form-control @error('pincode') is-invalid @enderror"
                           id="pincode"
                           placeholder="Pincode" value="{{ old('pincode', $addresses->pincode) }}">
                    @error('pincode')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="state">State</label>
                    <input type="text" name="state" class="form-control @error('state') is-invalid @enderror"
                           id="state"
                           placeholder="State" value="{{ old('state', $addresses->state) }}">
                    @error('state')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="landmark">Landmark</label>
                    <input type="text" name="landmark" class="form-control @error('landmark') is-invalid @enderror"
                           id="landmark"
                           placeholder="Landmark" value="{{ old('landmark', $addresses->landmark) }}">
                    @error('landmark')
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
@endsection
