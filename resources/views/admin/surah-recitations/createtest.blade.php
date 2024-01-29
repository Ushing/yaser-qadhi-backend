@extends('layouts.admin')
@push('styles')
    <style>
    #proVal{
        display: none;
    }
    </style>
@endpush

@section('content')

    <div class="content">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1> Test</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="{{route('admin.home')}}">Home</a></li>
                            <li class="breadcrumb-item active"><a href="{{route('admin.lecture.create')}}">Create</a></li>
                        </ol>
                    </div>
                </div>
            </div><!-- /.container-fluid -->
        </section>

        <!-- Main content -->
        <section class="content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-12">
                        <!-- general form elements -->
                        <div class="card card-info">
                            <div class="card-header">
                                <h3 class="card-title">Lecture Create</h3>
                            </div>
                            <form action="#" method="post" enctype="multipart/form-data">
                                @csrf
                                <div class="card-body">
                                    <div class="row">

                                        <div class="col-sm-12 col-md-12" >

                                                <div id="upload-container" class="text-center">
{{--                                                    <button id="browseFile" class="btn btn-primary">Brows File</button>--}}
                                                    <div class="form-group">
                                                        <label for="Name">Video <span class="text-danger">*</span></label>

                                                        <input id="upload"  type="file" name = "filename"
                                                                  class="form-control lectureVideo {{ $errors->has('video') ? 'is-invalid' : '' }}"
                                                                  value="{{ old('video') }}"
                                                                  placeholder="Enter Video">

{{--                                                        <input type="file" name = "filename" id="upload">--}}
                                                        @if ($errors->has('video'))
                                                            <div class="invalid-feedback">{{ $errors->first('video') }}</div>
                                                        @endif
                                                    </div>

                                                </div>
                                                <div class="progress mt-3" style="height: 25px" id="proVal">
                                                    <div class="progress-bar progress-bar-striped progress-bar-animated" role="progressbar" aria-valuenow="50" aria-valuemin="0" aria-valuemax="100" style="width: 0%; height: 100%">0%</div>
                                                </div>
                                        </div>


                                        <div class="col-sm-12 col-md-12" hidden>
                                            <div class="form-group">
                                                <label for="File">File <span class="text-danger">*</span></label>
                                                <input type="text" name="fileChangeName" id="fileChangeName"
                                                       class="form-control"
                                                />
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- /.card-body -->
                                <div class="card-footer text-center">
                                    <button type="submit" class="btn btn-success bg-gradient-success mr-2">Submit
                                    </button>
                                    <a href="{{route('admin.lecture.index')}}"
                                       class="btn btn-danger bg-gradient-danger mr-2">Cancel</a>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        @endsection

        @once
            @push('scripts')
                <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
                <script>
                    $(document).ready(function () {
                        $(document).ready(function () {
                            $(function () {
                                $('.summernote').summernote()
                            })
                        });
                    });
                </script>
                <!-- Resumable JS -->
                <script src="https://cdn.jsdelivr.net/npm/resumablejs@1.1.0/resumable.min.js"></script>

                <script type="text/javascript">
                    let browseFile = $('.lectureVideo');
                    // let browseFile = $('input[name="video"]');
                    let resumable = new Resumable({
                        target: '{{ route('admin.lecture.upload.large') }}',
                        query:{_token:'{{ csrf_token() }}'} ,// CSRF token
                        fileType: ['mp4'],
                        headers: {
                            'Accept' : 'application/json'
                        },
                        testChunks: false,
                        throttleProgressCallbacks: 1,
                    });

                    resumable.assignBrowse(browseFile[0]);

                    resumable.on('fileAdded', function (file) { // trigger when file picked
                        $('#proVal').show()
                        showProgress();
                        resumable.upload() // to actually start uploading.
                    });

                    resumable.on('fileProgress', function (file) { // trigger when file progress update
                        updateProgress(Math.floor(file.progress() * 100));
                    });

                    resumable.on('fileSuccess', function (file, response) { // trigger when file upload complete
                        response = JSON.parse(response)
                        console.log(response.filename)
                        $('#videoPreview').attr('src', response.path);
                        $('#fileChangeName').val(response.filename);
                        Swal.fire({
                            icon: 'success',
                            title: 'File Uploaded',
                            showConfirmButton: false,
                            timer: 800
                        })

                        // $('.card-footer').show();
                    });

                    resumable.on('fileError', function (file, response) { // trigger when there is any error
                        Swal.fire({
                            icon: 'error',
                            title: 'Failed To Upload',
                            timer: 800
                        })
                    });


                    let progress = $('.progress');
                    function showProgress() {
                        progress.find('.progress-bar').css('width', '0%');
                        progress.find('.progress-bar').html('0%');
                        progress.find('.progress-bar').removeClass('bg-success');
                        progress.show();
                    }

                    function updateProgress(value) {
                        progress.find('.progress-bar').css('width', `${value}%`)
                        progress.find('.progress-bar').html(`${value}%`)
                    }

                    function hideProgress() {
                        progress.hide();
                    }
                </script>

                <script src="{{asset('admin/plugins/summernote/summernote-bs4.min.js')}}"></script>

                {{--    Fetch all lecture subcategory by lecture category id--}}
                <script>
                    $(document).ready(function() {
                        let lectureCategoryId = '{{old('lecture_category_id')}}'
                        let lectureSubCategoryId = '{{old('lecture_sub_category_id')}}'
                        $('#lecture_category_id').change(function () {
                            if ($(this).val() > 0){
                                $.get('{{route('admin.lectureSubCategory.list.lectureCategory')}}', {lectureCategoryId: $(this).val()}, function (response) {
                                    if (response.lectureSubCategories){
                                        $('#lecture_sub_category_id').html('<option value="">Choose Dua Sub Category</option>');
                                        for (i in response.lectureSubCategories){
                                            lectureSubCategory = response.lectureSubCategories[i];
                                            let selected = lectureSubCategory.id == lectureSubCategoryId ? 'selected' : '';
                                            $('#lecture_sub_category_id').append('<option value="'+lectureSubCategory.id+'" '+selected+'>'+lectureSubCategory.name+'</option>')
                                        }
                                    }
                                });
                            }
                        });
                        if (lectureCategoryId > 0){
                            $('#lecture_category_id').trigger('change');
                        }
                    });
                </script>

    @endpush
    @endonce

