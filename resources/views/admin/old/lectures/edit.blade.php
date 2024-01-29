@extends('layouts.admin')
@push('styles')
    <style>
        .progress-bar {
            background-color: #7846b4!important;
            font-size: large;
            font-weight: bold;
        }
        #lectureVideoProgressBar{
            display: none;
        }
        #lectureAudioProgressBar{
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
                        <h1>{{$moduleName}}</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="{{route('admin.home')}}">Home</a></li>
                            <li class="breadcrumb-item active"><a href="{{route('admin.lecture.edit',$lecture->id)}}">Edit</a></li>
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
                                <h3 class="card-title">Lecture Edit</h3>
                            </div>
                            <form action="{{ route('admin.lecture.update',$lecture->id) }}" method="post" enctype="multipart/form-data">
                                @csrf
                                @method('PUT')
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-sm-12 col-md-5">
                                            <div class="form-group">
                                                <label for="Name">Title <span class="text-danger">*</span></label>
                                                <input type="text" name="title"
                                                       class="form-control {{ $errors->has('title') ? 'is-invalid' : '' }}"
                                                       value="{{ old('title',$lecture->title) }}"
                                                       placeholder="Enter Lecture Title"/>
                                                @if ($errors->has('title'))
                                                    <div class="invalid-feedback">{{ $errors->first('title') }}</div>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="col-sm-12 col-md-3">
                                            <div class="form-group">
                                                <label for="Name">Reference ID <span class="text-danger">*</span></label>
                                                <input type="text" name="reference_id"
                                                       class="form-control {{ $errors->has('reference_id') ? 'is-invalid' : '' }}"
                                                       value="{{ old('reference_id', $lecture->reference_id) }}"
                                                       placeholder="Enter Reference ID"/>
                                                @if ($errors->has('reference_id'))
                                                    <div class="invalid-feedback">{{ $errors->first('reference_id') }}</div>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="col-sm-12 col-md-4">
                                            <div class="form-group">
                                                <label for="exampleSelectGender">Select Lecture Category <span
                                                        class="text-danger">*</span></label>
                                                <select class="select2 form-control {{ $errors->has('lecture_category_id') ? 'is-invalid' : '' }}"
                                                    name="lecture_category_id" id="lecture_category_id" style="width: 100%">
                                                    <option value="">Choose Lecture Category</option>
                                                    @foreach($lectureCategories as $lectureCategory)
                                                        <option value="{{$lectureCategory->id}}"
                                                                 {{ $lectureCategory->id == $lecture->lectureSubCategory->lecture_category_id ? 'selected' : ''}}>{{$lectureCategory->name}}</option>
                                                    @endforeach
                                                </select>
                                                @if ($errors->has('lecture_category_id'))
                                                    <div
                                                        class="invalid-feedback">{{ $errors->first('lecture_category_id') }}</div>
                                                @endif
                                            </div>
                                        </div>

                                        <div class="col-sm-12 col-md-6">
                                            <div class="form-group">
                                                <label for="exampleSelectGender">Select Lecture Sub Category <span class="text-danger">*</span></label>
                                                <select class="select2 form-control {{ $errors->has('status') ? 'is-invalid' : '' }}"
                                                    name="lecture_sub_category_id" id="lecture_sub_category_id"
                                                    style="width: 100%">
                                                    <option value="">Choose Lecture Sub Category</option>
                                                </select>
                                                @if ($errors->has('lecture_sub_category_id'))
                                                    <div
                                                        class="invalid-feedback">{{ $errors->first('lecture_sub_category_id') }}</div>
                                                @endif
                                            </div>
                                        </div>



                                        <div class="col-sm-12 col-md-5">
                                            <div class="form-group">
                                                <p class="font-weight-bold">Status <span class="text-danger">*</span></p>

                                                <div class="d-flex justify-content-between w-50">
                                                    <div>
                                                        <input type="radio" id="active" name="status" value="1" {{($lecture->status== '1') ? 'checked' : ''}} >
                                                        <label for="active">Active</label><br>
                                                    </div>
                                                    <div>
                                                        <input type="radio" id="inactive" name="status" value="0" {{($lecture->status== '0') ? 'checked' : ''}}>
                                                        <label for="inactive">Inactive</label><br>
                                                    </div>
                                                </div>

                                                @if ($errors->has('status'))
                                                    <div class="invalid-feedback">{{ $errors->first('status') }}</div>
                                                @endif
                                            </div>
                                        </div>


                                        <div class="col-sm-12 col-md-12">
                                            <div class="form-group">
                                                <label for="Name">Description <span class="text-danger">*</span></label>
                                                <textarea type="text" name="description"
                                                          class="summernote form-control {{ $errors->has('description') ? 'is-invalid' : '' }}"
                                                          placeholder="Enter Lecture Translation">{{ old('description',$lecture->description ) }}</textarea>
                                                @if ($errors->has('description'))
                                                    <div
                                                        class="invalid-feedback">{{ $errors->first('description') }}</div>
                                                @endif
                                            </div>
                                        </div>


                                        <div class="col-sm-12 col-md-6">
                                            <div class="form-group">
                                                <label for="exampleSelectGender">Select Audio <span
                                                        class="text-danger"></span></label>
                                                <br>
                                                @if(isset($lecture->audio))
                                                    <span class="text-info font-weight-bold">Old:</span>  <span class="text-black"> {{$lecture->audio}}</span>

                                                @else
                                                    <span class="text-info font-weight-bold">Previous File Not Available:</span>

                                                @endif
                                                <input id="uploadAudio" type="file" name ="audioFilename"
                                                       class="form-control lectureAudio {{ $errors->has('audio') ? 'is-invalid' : '' }}"
                                                       value="{{ old('audio',$lecture->audio )}}"
                                                       placeholder="Enter Audio" accept="audio/*">
                                                @if ($errors->has('audio'))
                                                    <div class="invalid-feedback">{{ $errors->first('audio') }}</div>
                                                @endif
                                            </div>
                                            <div class="progress progressAudioSection mt-3" style="height: 25px" id="lectureAudioProgressBar">
                                                <div class="progress-bar progress-bar-striped progress-bar-animated" id="audioProgress" role="progressbar" aria-valuenow="50" aria-valuemin="0" aria-valuemax="100" style="width: 0%; height: 100%">0%</div>
                                            </div>
                                        </div>


                                        <div class="col-sm-12 col-md-12" hidden>
                                            <div class="form-group">
                                                <label for="File">Lecture Audio <span class="text-danger"></span></label>
                                                <input type="text" name="lecture_audio" id="lecture_audio"
                                                       class="form-control"
                                                />
                                            </div>
                                        </div>


                                        <div class="col-sm-12 col-md-6">
                                            <div class="form-group">
                                                <label for="exampleSelectGender">Select Video <span
                                                        class="text-danger"></span></label>
                                                <br>
                                                @if(isset($lecture->video))
                                                  <span class="text-info font-weight-bold">Old:</span>  <span class="text-black"> {{$lecture->video}}</span>
                                                @else
                                                    <span class="text-info font-weight-bold">Previous File Not Available:</span>

                                                @endif
                                                <input id="upload" type="file" name = "filename"
                                                       class="form-control lectureVideo {{ $errors->has('video') ? 'is-invalid' : '' }}"
                                                       value="{{ old('video',$lecture->video )}}"
                                                       placeholder="Enter Video" accept="video/*">
                                                @if ($errors->has('video'))
                                                    <div class="invalid-feedback">{{ $errors->first('video') }}</div>
                                                @endif
                                            </div>
                                            <div class="progress progressVideoSection mt-3" style="height: 25px" id="lectureVideoProgressBar">
                                                <div class="progress-bar progress-bar-striped progress-bar-animated" id="videoProgress" role="progressbar" aria-valuenow="50" aria-valuemin="0" aria-valuemax="100" style="width: 0%; height: 100%">0%</div>
                                            </div>
                                        </div>


                                        <div class="col-sm-12 col-md-12" hidden>
                                            <div class="form-group">
                                                <label for="File">Lecture Video <span class="text-danger"></span></label>
                                                <input type="text" name="lecture_video" id="lecture_video"
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
                <script src="{{asset('admin/plugins/summernote/summernote-bs4.min.js')}}"></script>
                <script>
                    $(document).ready(function () {
                        $(function () {
                            $('.summernote').summernote()
                        })
                    });
                </script>
                {{--    Fetch all lecture subcategory by lecture category id--}}
                <script>
                    $(document).ready(function() {
                        let lectureCategoryId = '{{$lecture->lectureSubCategory->lecture_category_id}}'
                        let lectureSubCategoryId = '{{$lecture->lecture_sub_category_id}}'
                        $('#lecture_category_id').change(function () {
                            if ($(this).val() > 0){
                                $.get('{{route('admin.lectureSubCategory.list.lectureCategory')}}', {lectureCategoryId: $(this).val()}, function (response) {
                                    if (response.lectureSubCategories){
                                        $('#lecture_sub_category_id').html('<option value="">Choose Lecture Sub Category</option>');
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
                        // when page load 1st time
                        if (lectureCategoryId > 0 && lectureSubCategoryId > 0){
                            $.get('{{route('admin.lectureSubCategory.list.lectureCategory')}}', {lectureCategoryId: lectureCategoryId}, function (response) {
                                if (response.lectureSubCategories){
                                    $('#lecture_sub_category_id').html('<option value="">Choose Lecture Sub Category</option>');
                                    for (i in response.lectureSubCategories){
                                        lectureSubCategory = response.lectureSubCategories[i];
                                        let selected = lectureSubCategory.id == lectureSubCategoryId ? 'selected' : '';
                                        $('#lecture_sub_category_id').append('<option value="'+lectureSubCategory.id+'" '+selected+'>'+lectureSubCategory.name+'</option>')
                                    }
                                }
                            });
                        }
                    });
                </script>




                <!-- Resumable JS -->
                <script src="https://cdn.jsdelivr.net/npm/resumablejs@1.1.0/resumable.min.js"></script>

                <!-- Audio File Upload Start -->
                <script type="text/javascript">
                    let browseAudioFile = $('.lectureAudio');
                    let resumableAudioFile = new Resumable({
                        target: '{{ route('admin.lecture.upload.audio') }}',
                        query:{_token:'{{ csrf_token() }}'} ,// CSRF token
                        fileType: ['mp3'],
                        headers: {
                            'Accept' : 'application/json'
                        },
                        testChunks: false,
                        throttleProgressCallbacks: 1,
                    });

                    resumableAudioFile.assignBrowse(browseAudioFile[0]);
                    resumableAudioFile.on('fileAdded', function (file) { // trigger when file picked
                        $('#lectureAudioProgressBar').show()
                        showAudioProgress();
                        resumableAudioFile.upload() // to actually start uploading.
                    });

                    resumableAudioFile.on('fileProgress', function (file) { // trigger when file progress update
                        updateAudioProgress(Math.floor(file.progress() * 100));
                    });

                    resumableAudioFile.on('fileSuccess', function (file, response) { // trigger when file upload complete
                        response = JSON.parse(response)
                        $('#audioPreview').attr('src', response.path);
                        $('#lecture_audio').val(response.filename);
                        Swal.fire({
                            icon: 'success',
                            title: 'File Uploaded',
                            showConfirmButton: false,
                            timer: 800
                        })

                    });
                    resumableAudioFile.on('fileError', function (file, response) { // trigger when there is any error
                        Swal.fire({
                            icon: 'error',
                            title: 'Failed To Upload',
                            timer: 800
                        })
                    });
                    let progressForAudio = $('.progressAudioSection');
                    function showAudioProgress() {
                        progressForAudio.find('#audioProgress').css('width', '0%');
                        progressForAudio.find('#audioProgress').html('0%');
                        progressForAudio.find('#audioProgress').removeClass('bg-info');
                        progressForAudio.show();
                    }

                    function updateAudioProgress(value) {
                        progressForAudio.find('#audioProgress').css('width', `${value}%`)
                        progressForAudio.find('#audioProgress').html(`${value}%`)
                    }
                    function hideProgress() {
                        progressForAudio.hide();
                    }
                </script>
                <!-- Audio File Upload End -->


                <!-- Video File Upload Start -->
                <script type="text/javascript">
                    let browseVideoFile = $('.lectureVideo');
                    let resumableVideoFile = new Resumable({
                        target: '{{ route('admin.lecture.upload.video') }}',
                        query:{_token:'{{ csrf_token() }}'} ,// CSRF token
                        fileType: ['mp4','m3u8','ts','3gp','mov','avi','wmv','mkv','webm'],
                        headers: {
                            'Accept' : 'application/json'
                        },
                        testChunks: false,
                        throttleProgressCallbacks: 1,
                    });

                    resumableVideoFile.assignBrowse(browseVideoFile[0]);
                    resumableVideoFile.on('fileAdded', function (file) { // trigger when file picked
                        $('#lectureVideoProgressBar').show()
                        showProgress();
                        resumableVideoFile.upload() // to actually start uploading.
                    });

                    resumableVideoFile.on('fileProgress', function (file) { // trigger when file progress update
                        updateProgress(Math.floor(file.progress() * 100));
                    });

                    resumableVideoFile.on('fileSuccess', function (file, response) { // trigger when file upload complete
                        response = JSON.parse(response)
                        $('#videoPreview').attr('src', response.path);
                        $('#lecture_video').val(response.filename);
                        Swal.fire({
                            icon: 'success',
                            title: 'File Uploaded',
                            showConfirmButton: false,
                            timer: 800
                        })

                    });
                    resumableVideoFile.on('fileError', function (file, response) { // trigger when there is any error
                        Swal.fire({
                            icon: 'error',
                            title: 'Failed To Upload',
                            timer: 800
                        })
                    });
                    let progressForVideo = $('.progressVideoSection');
                    function showProgress() {
                        progressForVideo.find('#videoProgress').css('width', '0%');
                        progressForVideo.find('#videoProgress').html('0%');
                        progressForVideo.find('#videoProgress').removeClass('bg-info');
                        progressForVideo.show();
                    }

                    function updateProgress(value) {
                        progressForVideo.find('#videoProgress').css('width', `${value}%`)
                        progressForVideo.find('#videoProgress').html(`${value}%`)
                    }

                    function hideProgress() {
                        progressForVideo.hide();
                    }
                </script>

                <!-- Video File Upload End -->

    @endpush
    @endonce

