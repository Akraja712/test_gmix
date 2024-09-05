@extends('layouts.admin')

@section('title', 'Update Professions')
@section('content-header', 'Update Professions')
@section('content-actions')
    <a href="{{route('professions.index')}}" class="btn btn-success"><i class="fas fa-back"></i>Back To Professions</a>
@endsection
@section('content')

    <div class="card">
        <div class="card-body">

            <form action="{{ route('professions.update', $professions) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="form-group">
                    <label for="profession">Profession</label>
                    <input type="text" name="profession" class="form-control @error('profession') is-invalid @enderror"
                           id="profession"
                           placeholder="profession" value="{{ old('profession', $professions->profession) }}">
                    @error('profession')
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
