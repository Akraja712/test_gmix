@extends('layouts.admin')

@section('title', 'Update Notifications')
@section('content-header', 'Update Notifications')
@section('content-actions')
    <a href="{{route('usernotifications.index')}}" class="btn btn-success"><i class="fas fa-back"></i>Back To Notifications</a>
@endsection
@section('content')

    <div class="card">
        <div class="card-body">

            <form action="{{ route('usernotifications.update', $usernotifications) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="form-group">
                    <label for="title">Title</label>
                    <input type="text" name="title" class="form-control @error('title') is-invalid @enderror"
                           id="title"
                           placeholder="title" value="{{ old('title', $usernotifications->title) }}">
                    @error('title')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                    @enderror 
                </div>

                <div class="form-group">
                    <label for="message">Message</label>
                    <input type="text" name="message" class="form-control @error('message') is-invalid @enderror"
                           id="message"
                           placeholder="message" value="{{ old('message', $usernotifications->message) }}">
                    @error('message')
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
   