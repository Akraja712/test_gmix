@extends('layouts.admin')

@section('title', 'Update Settings')
@section('content-header', 'Update Settings')



@section('content')
<div class="card">
    <div class="card-body">
      

        <form action="{{ route('news.update', $news->id) }}" method="POST">
        @csrf
        @method('POST')
            <div class="form-group">
                <label for="delivery_charges">Delivery Charges</label>
                <input type="text" class="form-control" id="delivery_charges" name="delivery_charges" value="{{ $news->delivery_charges }}" required style="width: 100%; max-width: 250px;">
            </div>
            <div class="form-group">
                <label for="customer_support_number">Customer Support Number</label>
                <input type="number" class="form-control" id="customer_support_number" name="customer_support_number" value="{{ $news->customer_support_number }}" required style="width: 100%; max-width: 250px;">
            </div>
            <div class="box-footer">
                <button type="submit" class="btn btn-primary">Update</button>
            </div>
        </form>
    </div>
</div>
<script src="//cdn.ckeditor.com/4.21.0/full-all/ckeditor.js"></script>
<script>
    // Replace CKEditor for privacy_policy and terms_conditions textareas
    document.addEventListener('DOMContentLoaded', function () {
        CKEDITOR.replace('privacy_policy', {
            extraPlugins: 'colorbutton'
        });
        CKEDITOR.replace('terms_conditions', {
            extraPlugins: 'colorbutton'
        });
    });
</script>
@endsection
