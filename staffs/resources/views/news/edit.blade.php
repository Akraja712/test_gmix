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
                <label for="telegram">Telegram</label>
                <input type="text" class="form-control" id="telegram" name="telegram" value="{{ $news->telegram }}" required>
            </div>

            <div class="form-group">
                <label for="instagram">Instagram</label>
                <input type="text" class="form-control" id="instagram" name="instagram" value="{{ $news->instagram }}" required>
            </div>

            <div class="form-group">
                <label for="upi_id">UPI ID</label>
                <input type="text" class="form-control" id="upi_id" name="upi_id" value="{{ $news->upi_id }}" required>
            </div>

            <div class="form-group">
                <label for="privacy_policy">Privacy Policy</label>
                <textarea name="privacy_policy" id="privacy_policy" class="form-control ckeditor-content" rows="10" required>{!! $news->privacy_policy !!}</textarea>
            </div>

            <div class="form-group">
                <label for="terms_conditions">Terms & Conditions</label>
                <textarea name="terms_conditions" id="terms_conditions" class="form-control ckeditor-content" rows="10" required>{!! $news->terms_conditions !!}</textarea>
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
