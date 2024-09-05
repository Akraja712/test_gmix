@extends('layouts.admin')

@section('title', 'App Settings Management')
@section('content-header', 'App Settings Management')
@section('content-actions')
   
@endsection
@section('content')
<div class="card">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered table-hover">
                <thead class="thead-dark">
                    <tr>
                        <th>ID</th>
                        <th>Link</th>
                        <th>App Version</th>
                        <th>Description</th>
                        <th>Login</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($appsettingsList as $appsettings)
                    <tr>
                        <td>{{ $appsettings->id }}</td>
                        <td>{{ $appsettings->link }}</td>
                        <td>{{ $appsettings->app_version }}</td>
                        <td>{{ $appsettings->description }}</td>
                        <td>{{ $appsettings->login }}</td>
                        <td>
                            <a href="{{ route('appsettings.edit', $appsettings->id) }}" class="btn btn-primary"><i class="fas fa-edit"></i></a>
                           
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
