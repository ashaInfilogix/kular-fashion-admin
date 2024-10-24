@extends('layouts.app')

@section('content')
    <div class="page-content">
        <div class="container-fluid">

            <!-- start page title -->
            <div class="row">
                <div class="col-12">
                    <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                        <h4 class="mb-sm-0 font-size-18">Create a new Category</h4>

                        <div class="page-title-right">
                            <a href="{{ route('categories.index') }}" class="btn btn-primary"><i class="bx bx-arrow-back"></i> Back to categories</a>
                        </div>

                    </div>
                </div>
            </div>
            <!-- end page title -->

            <div class="row">
                <div class="col-12">
                    <x-error-message :message="$errors->first('message')" />
                    <x-success-message :message="session('success')" />

                    <div class="card">
                        <div class="card-body">
                            <form action="{{ route('categories.store') }}" method="post" enctype="multipart/form-data">
                                @csrf
                                @include('categories.form')
                            </form>    
                        </div>    
                    </div>
                </div> <!-- end col -->
            </div> <!-- end row -->
        </div> <!-- container-fluid -->
    </div>
    <script>
$(document).ready(function() {
    $('#imageInput').on('change', function(event) {
        var file = event.target.files[0];
        if (file) {
            var reader = new FileReader();
            reader.onload = function(e) {
                $('#imagePreview').attr('src', e.target.result).show(); // Show the preview
            };
            reader.readAsDataURL(file);
        } else {
            $('#imagePreview').hide(); // Hide if no file selected
        }
    });
});
        </script>
@endsection