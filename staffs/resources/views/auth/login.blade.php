@extends('layouts.auth')

@section('css')
<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Arvo:400,700&display=swap">
<style>
    body, input, button {
        font-family: 'Arvo', sans-serif;
    }
    h5 {
        font-family: 'Arvo', sans-serif; /* Apply Arvo font to h5 */
    }
    .invalid-feedback {
        display: block;
    }
</style>
@endsection

@section('content')
<form action="{{ route('login') }}" method="post">
    <center>
        <img src="{{ asset('public/images/poslg.png') }}" alt="Image description" class="rounded-circle" style="max-width: 100px; max-height: 100px;">
    </center>
    <h5 class="login-box-msg">Gmix - Staff Login</h5>
    <br>
    @csrf
    <div class="form-group">
        <div class="input-group">
            <input type="text" name="mobile" class="form-control @error('mobile') is-invalid @enderror"
                placeholder="Mobile Number" required autocomplete="mobile">
            <div class="input-group-append">
                <div class="input-group-text">
                    <span class="fas fa-mobile-alt"></span>
                </div>
            </div>
        </div>
        @error('mobile')
        <span class="invalid-feedback" role="alert">
            <strong>{{ $message }}</strong>
        </span>
        @enderror
    </div>
    <div class="form-group">
        <div class="input-group">
            <input type="password" class="form-control @error('password') is-invalid @enderror" placeholder="Password"
                name="password" required autocomplete="current-password">
            <div class="input-group-append">
                <div class="input-group-text">
                    <span class="fas fa-lock"></span>
                </div>
            </div>
        </div>
        @error('password')
        <span class="invalid-feedback" role="alert">
            <strong>{{ $message }}</strong>
        </span>
        @enderror
    </div>
    <div class="row">
        <div class="col-8">
            <div class="icheck-primary">
                <input type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
                <label for="remember">
                    Remember Me
                </label>
            </div>
        </div>
        <div class="col-4">
            <button type="submit" class="btn btn-success btn-block">Log In</button>
        </div>
    </div>
</form>
@endsection
