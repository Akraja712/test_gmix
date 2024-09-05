<!-- resources/views/bulk-users/upload.blade.php -->
@extends('layouts.admin')

@section('title', 'Upload Bulk Users')

@section('content')
<div class="container">
    <h1>Upload Bulk Users</h1>
    <form action="{{ route('bulk-users.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="form-group">
            <label for="file">Select Excel File:</label>
            <input type="file" name="file" class="form-control" required>
        </div>
        <button type="submit" class="btn btn-primary">Upload</button>
    </form>
</div>
@endsection
