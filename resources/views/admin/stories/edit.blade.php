@extends('layouts.admin')
@push('styles')
    <style>
        .progress-bar {
            background-color: #7846b4!important;
            font-size: large;
            font-weight: bold;
        }
        #quranVideoProgressBar{
            display: none;
        }
        #quranAudioProgressBar{
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
                            <li class="breadcrumb-item active"><a href="{{route('admin.stories.edit',$stories->id)}}">Edit</a></li>
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
                                <h3 class="card-title">Stories Edit</h3>
                            </div>
                            <form action="{{ route('admin.stories.update',$stories->id) }}" method="post" enctype="multipart/form-data">
                                @csrf
                                @method('PUT')
                                <div class="card-body">
                                    <div class="row">

                                        <div class="col-sm-12 col-md-6">
                                            <div class="form-group">
                                                <label for="Name">Title <span class="text-danger">*</span></label>
                                                <input required type="text" name="title" class="form-control {{ $errors->has('title') ? 'is-invalid' : '' }}" value="{{ old('title',$stories->title) }}" placeholder="" />
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
                                                       value="{{ old('reference_id', $stories->reference_id) }}"
                                                       placeholder="Enter Reference ID"/>
                                                @if ($errors->has('reference_id'))
                                                    <div class="invalid-feedback">{{ $errors->first('reference_id') }}</div>
                                                @endif
                                            </div>
                                        </div>


                                        <div class="col-sm-12 col-md-3">
                                            <div class="form-group">
                                                <p class="font-weight-bold">Status <span class="text-danger">*</span></p>

                                                <div class="d-flex justify-content-between w-50">
                                                    <div>
                                                        <input type="radio" id="active" name="status" value="1" {{($stories->status== '1') ? 'checked' : ''}} >
                                                        <label for="active">Active</label><br>
                                                    </div>
                                                    <div>
                                                        <input type="radio" id="inactive" name="status" value="0" {{($stories->status== '0') ? 'checked' : ''}}>
                                                        <label for="inactive">Inactive</label><br>
                                                    </div>
                                                </div>

                                                @if ($errors->has('status'))
                                                    <div class="invalid-feedback">{{ $errors->first('status') }}</div>
                                                @endif
                                            </div>
                                        </div>




                                        <div class="col-sm-12 col-md-6">
                                            <div class="form-group">
                                                <label for="exampleSelectGender">Select Video <span
                                                        class="text-danger"></span></label>
                                                <br>
                                                @if(isset($stories->video))
                                                  <span class="text-info font-weight-bold">Old:</span>  <span class="text-black"> {{$stories->video}}</span>
                                                @else
                                                    <span class="text-info font-weight-bold">Previous File Not Available:</span>

                                                @endif
                                                <input id="upload" type="file" name = "filename"
                                                       class="form-control quranVideo {{ $errors->has('video') ? 'is-invalid' : '' }}"
                                                       value="{{ old('video',$stories->video )}}"
                                                       placeholder="Enter Video" accept="video/*">
                                                @if ($errors->has('video'))
                                                    <div class="invalid-feedback">{{ $errors->first('video') }}</div>
                                                @endif
                                            </div>
                                            <div class="progress progressVideoSection mt-3" style="height: 25px" id="quranVideoProgressBar">
                                                <div class="progress-bar progress-bar-striped progress-bar-animated" id="videoProgress" role="progressbar" aria-valuenow="50" aria-valuemin="0" aria-valuemax="100" style="width: 0%; height: 100%">0%</div>
                                            </div>
                                        </div>

                                        <div class="col-sm-12 col-md-6">
                                            <div class="form-group">
                                                <label for="exampleSelectGender">Select Audio <span
                                                        class="text-danger"></span></label>
                                                <br>
                                                @if(isset($stories->audio))
                                                  <span class="text-info font-weight-bold">Old:</span>  <span class="text-black"> {{$stories->video}}</span>
                                                @else
                                                    <span class="text-info font-weight-bold">Previous File Not Available:</span>

                                                @endif
                                                <input id="upload" type="file" name = "filename"
                                                       class="form-control quranVideo {{ $errors->has('audio') ? 'is-invalid' : '' }}"
                                                       value="{{ old('audio',$stories->audio)}}"
                                                       placeholder="Enter Audio" accept="audio/*">
                                                @if ($errors->has('audio'))
                                                    <div class="invalid-feedback">{{ $errors->first('audio') }}</div>
                                                @endif
                                            </div>
                                            <div class="progress progressVideoSection mt-3" style="height: 25px" id="quranVideoProgressBar">
                                                <div class="progress-bar progress-bar-striped progress-bar-animated" id="videoProgress" role="progressbar" aria-valuenow="50" aria-valuemin="0" aria-valuemax="100" style="width: 0%; height: 100%">0%</div>
                                            </div>
                                        </div>


                                        <div class="col-sm-12 col-md-12" hidden>
                                            <div class="form-group">
                                                <label for="File">Surah Recitation Video <span class="text-danger"></span></label>
                                                <input type="text" name="quran_video" id="quran_video"
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
                                    <a href="{{route('admin.stories.index')}}"
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
                        $('.summernote').summernote();
                    });
                });
            </script>

            <!-- Resumable JS -->
            <script src="https://cdn.jsdelivr.net/npm/resumablejs@1.1.0/resumable.min.js"></script>

            <!-- Video and Audio File Upload Start -->
        <script type="text/javascript">
            // Video Upload
            let browseVideoFile = $('.quranVideo');
            let resumableVideoFile = new Resumable({
                target: '{{ route('admin.stories.upload.video') }}',
                query: {_token: '{{ csrf_token() }}'},
                fileType: ['mp4', 'm3u8', 'ts', '3gp', 'mov', 'avi', 'wmv', 'mkv', 'webm'],
                headers: {
                    'Accept': 'application/json'
                },
                testChunks: false,
                throttleProgressCallbacks: 1,
            });

            resumableVideoFile.assignBrowse(browseVideoFile[0]);
            resumableVideoFile.on('fileAdded', function (file) {
                $('#quranVideoProgressBar').show();
                showVideoProgress();
                resumableVideoFile.upload();
            });

            resumableVideoFile.on('fileProgress', function (file) {
                updateVideoProgress(Math.floor(file.progress() * 100));
            });

            resumableVideoFile.on('fileSuccess', function (file, response) {
                response = JSON.parse(response);
                $('#videoPreview').attr('src', response.path);
                $('#quran_video').val(response.filename);
                Swal.fire({
                    icon: 'success',
                    title: 'Video File Uploaded',
                    showConfirmButton: false,
                    timer: 800
                });
            });

            resumableVideoFile.on('fileError', function (file, response) {
                Swal.fire({
                    icon: 'error',
                    title: 'Failed To Upload Video File',
                    timer: 800
                });
            });

            let progressForVideo = $('.progressVideoSection');

            function showVideoProgress() {
                progressForVideo.find('#videoProgress').css('width', '0%');
                progressForVideo.find('#videoProgress').html('0%');
                progressForVideo.find('#videoProgress').removeClass('bg-info');
                progressForVideo.show();
            }

            function updateVideoProgress(value) {
                progressForVideo.find('#videoProgress').css('width', `${value}%`);
                progressForVideo.find('#videoProgress').html(`${value}%`);
            }

            function hideVideoProgress() {
                progressForVideo.hide();
            }

            // Audio Upload
            let browseAudioFile = $('.quranAudio');
            let resumableAudioFile = new Resumable({
                target: '{{ route('admin.stories.upload.audio') }}',
                query: {_token: '{{ csrf_token() }}'},
                fileType: ['mp3', 'wav', 'ogg', 'aac'],
                headers: {
                    'Accept': 'application/json'
                },
                testChunks: false,
                throttleProgressCallbacks: 1,
            });

            resumableAudioFile.assignBrowse(browseAudioFile[0]);
            resumableAudioFile.on('fileAdded', function (file) {
                $('#quranAudioProgressBar').show();
                showAudioProgress();
                resumableAudioFile.upload();
            });

            resumableAudioFile.on('fileProgress', function (file) {
                updateAudioProgress(Math.floor(file.progress() * 100));
            });

            resumableAudioFile.on('fileSuccess', function (file, response) {
                response = JSON.parse(response);
                $('#audioPreview').attr('src', response.path);
                $('#quran_audio').val(response.filename);
                Swal.fire({
                    icon: 'success',
                    title: 'Audio File Uploaded',
                    showConfirmButton: false,
                    timer: 800
                });
            });

            resumableAudioFile.on('fileError', function (file, response) {
                Swal.fire({
                    icon: 'error',
                    title: 'Failed To Upload Audio File',
                    timer: 800
                });
            });

            let progressForAudio = $('.progressAudioSection');

            function showAudioProgress() {
                progressForAudio.find('#audioProgress').css('width', '0%');
                progressForAudio.find('#audioProgress').html('0%');
                progressForAudio.find('#audioProgress').removeClass('bg-info');
                progressForAudio.show();
            }

            function updateAudioProgress(value) {
                progressForAudio.find('#audioProgress').css('width', `${value}%`);
                progressForAudio.find('#audioProgress').html(`${value}%`);
            }

            function hideAudioProgress() {
                progressForAudio.hide();
            }
        </script>
        <!-- Video and Audio File Upload End -->
    @endpush
@endonce

