@extends('app.main')

@section('title')
    Upload CSV
@endsection

@section('content')
    <main class="content">
        <form method="post" action="{{ route('products.upload') }}" enctype="multipart/form-data" class="content-form">
            @include('shared.error')
            @include('shared.success')

            @csrf

            <label class="content-form__label">
                <span>Upload a CSV file</span>
                <input type="file" name="file" class="content-form__input" accept="text/csv" required>
            </label>

            <button id="upload-file-submit" class="content-form__submit">Upload</button>
        </form>
    </main>
@endsection
