@extends('layouts.admin')

@section('title', 'Update Chat Points')
@section('content-header', 'Update Chat Points')
@section('content-actions')
    <a href="{{route('chat_points.index')}}" class="btn btn-success"><i class="fas fa-back"></i>Back To Chat Points</a>
@endsection
@section('content')

    <div class="card">
        <div class="card-body">

            <form action="{{ route('chat_points.update', $chat_points) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="form-group">
                    <label for="user_id">User ID</label>
                    <input type="number" name="user_id" class="form-control @error('user_id') is-invalid @enderror"
                           id="user_id"
                           placeholder="User ID" value="{{ old('user_id', $chat_points->user_id) }}">
                    @error('user_id')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                    @enderror 
                </div>


                <div class="form-group">
                    <label for="chat_user_id">Chat User ID</label>
                    <input type="number" name="chat_user_id" class="form-control @error('chat_user_id') is-invalid @enderror"
                           id="chat_user_id"
                           placeholder="Chat User ID" value="{{ old('chat_user_id', $chat_points->chat_user_id) }}">
                    @error('chat_user_id')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                    @enderror 
                </div>

                <div class="form-group">
                    <label for="points">Points</label>
                    <input type="number" name="points" class="form-control @error('points') is-invalid @enderror"
                           id="points"
                           placeholder="points" value="{{ old('points', $chat_points->points) }}">
                    @error('points')
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
