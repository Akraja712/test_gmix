@extends('layouts.admin')

@section('title', 'Update App Settings')
@section('content-header', 'Update App Settings')

@section('content')
<div class="card">
    <div class="card-body">
        <form action="{{ route('appsettings.update', $appsettings->id) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="form-group">
                <label for="link">Link</label>
                <input type="text" class="form-control" id="link" name="link" value="{{ old('link', $appsettings->link) }}" required>
            </div>

            <div class="form-group">
                <label for="app_version">App Version</label>
                <input type="text" class="form-control" id="app_version" name="app_version" value="{{ old('app_version', $appsettings->app_version) }}" required>
            </div>

            <div class="form-group">
                <label for="description">Description</label>
                <textarea name="description" id="description" class="form-control" rows="10" required>{!! old('description', $appsettings->description) !!}</textarea>
            </div>

            <div class="form-group">
                <label for="login">Log In</label>
                <div class="custom-control custom-switch">
                    <input type="hidden" name="login" value="0"> <!-- Hidden input to ensure a value is always submitted -->
                    <input type="checkbox" name="login" class="custom-control-input @error('login') is-invalid @enderror" id="login" value="1" {{ old('login', $appsettings->login) == '1' ? 'checked' : '' }}>
                    <label class="custom-control-label" for="login"></label>
                </div>
                @error('login')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
                @enderror
            </div>

            <div class="box-footer">
                <button type="submit" class="btn btn-primary">Update</button>
            </div>
        </form>
    </div>
</div>
@endsection
