@extends('layouts.admin')

@section('title', 'Create Professions')
@section('content-header', 'Create Professions')
@section('content-actions')
    <a href="{{route('professions.index')}}" class="btn btn-success"><i class="fas fa-back"></i>Back To Professions</a>
@endsection
@section('content')

    <div class="card">
        <div class="card-body">

            <form action="{{ route('professions.store') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <div class="form-group">
                    <label for="profession">Profession</label>
                    <input type="text" name="profession" class="form-control @error('profession') is-invalid @enderror"
                           id="profession"
                           placeholder="profession" value="{{ old('profession') }}">
                    @error('profession')
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
