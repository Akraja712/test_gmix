@extends('layouts.admin')

@section('title', 'Update Addresses')
@section('content-header', 'Update Addresses')
@section('content-actions')
    <a href="{{ route('addresses.index') }}" class="btn btn-success"><i class="fas fa-back"></i> Back To Addresses</a>
@endsection

@section('content')

    <div class="card">
        <div class="card-body">

            <form action="{{ route('addresses.update', $addresses) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="form-group">
                    <label for="user_id">User Name</label>
                    <input type="text" name="user_id" class="form-control @error('user_id') is-invalid @enderror"
                           id="user_id"
                           placeholder="User name" value="{{ $users->firstWhere('id', $addresses->user_id)->name ?? 'No user selected' }}" readonly>
                    @error('user_id')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                    @enderror 
                </div>

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
                    <input type="number" name="mobile" class="form-control @error('mobile') is-invalid @enderror"
                           id="mobile"
                           placeholder="mobile" value="{{ old('mobile', $addresses->mobile) }}">
                    @error('mobile')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="alternate_mobile">Alternate Mobile</label>
                    <input type="number" name="alternate_mobile" class="form-control @error('alternate_mobile') is-invalid @enderror"
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
                    <label for="state">State</label>
                    <input type="text" name="state" class="form-control @error('state') is-invalid @enderror"
                           id="state"
                           placeholder="state" value="{{ old('state', $addresses->state) }}">
                    @error('state')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="pincode">Pincode</label>
                    <input type="number" name="pincode" class="form-control @error('pincode') is-invalid @enderror"
                           id="pincode"
                           placeholder="Pincode" value="{{ old('pincode', $addresses->pincode) }}">
                    @error('pincode')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="city">City</label>
                    <input type="text" name="city" class="form-control @error('city') is-invalid @enderror"
                           id="city"
                           placeholder="city" value="{{ old('city', $addresses->city) }}">
                    @error('city')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="landmark">Landmark</label>
                    <input type="text" name="landmark" class="form-control @error('landmark') is-invalid @enderror"
                           id="landmark"
                           placeholder="landmark" value="{{ old('landmark', $addresses->landmark) }}">
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
    <script>
document.addEventListener('DOMContentLoaded', function () {
    const fileInput = document.getElementById('profile');
    const fileInputLabel = fileInput.nextElementSibling;

    fileInput.addEventListener('change', function () {
        const fileName = this.files[0].name;
        fileInputLabel.textContent = fileName;
    });
});
</script>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const fileInput = document.getElementById('cover_img');
    const fileInputLabel = fileInput.nextElementSibling;

    fileInput.addEventListener('change', function () {
        const fileName = this.files[0].name;
        fileInputLabel.textContent = fileName;
    });
});
</script>
@endsection
