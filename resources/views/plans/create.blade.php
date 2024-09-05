@extends('layouts.admin')

@section('title', 'Create Plans')
@section('content-header', 'Create Plans')
@section('content-actions')
    <a href="{{route('plans.index')}}" class="btn btn-success"><i class="fas fa-back"></i>Back To Plans</a>
@endsection
@section('content')

    <div class="card">
        <div class="card-body">

            <form action="{{ route('plans.store') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <div class="form-group">
                    <label for="plan_name">Plan Name</label>
                    <input type="text" name="plan_name" class="form-control @error('plan_name') is-invalid @enderror"
                           id="plan_name"
                           placeholder="Plan Name" value="{{ old('plan_name') }}">
                    @error('plan_name')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="validity">Validity</label>
                    <input type="number" name="validity" class="form-control @error('validity') is-invalid @enderror" id="offer_percentage"
                           placeholder="validity" value="{{ old('validity') }}">
                    @error('validity')
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
                    <label for="save_amount">Save Amount</label>
                    <input type="number" name="save_amount" class="form-control @error('save_amount') is-invalid @enderror"
                           id="save_amount"
                           placeholder="Save Amount" value="{{ old('save_amount') }}">
                    @error('save_amount')
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
