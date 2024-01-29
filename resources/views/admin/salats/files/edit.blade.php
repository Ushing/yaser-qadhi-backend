@extends('layouts.admin')
@push('styles')
    <style>
        .progress-bar {
            background-color: #7846b4!important;
            font-size: large;
            font-weight: bold;
        }
        #surahVideoProgressBar{
            display: none;
        }
        #surahAudioProgressBar{
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
                            <li class="breadcrumb-item active"><a href="{{route('admin.quranProgramFiles.update',$quranProgramFile->id)}}">Edit</a></li>
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
                                <h3 class="card-title">Surah Recitation Edit</h3>
                            </div>
{{--                            <form action="" method="post" enctype="multipart/form-data">--}}
                            <form action="{{ route('admin.quranProgramFiles.update',$quranProgramFile->id) }}" method="post" enctype="multipart/form-data">
                                @csrf
                                @method('PUT')
                                <div class="card-body">
                                    <div class="row">

                                        <div class="col-sm-12 col-md-12">
                                            <div class="form-group">
                                                <label for="exampleSelectGender">Select Srt File <span
                                                        class="text-danger"></span></label>
                                                @if(isset($quranProgramFile->sub_title_file))
                                                    <span class="text-info font-weight-bold">(Old:</span>  <span class="text-black"> {{$quranProgramFile->sub_title_file}})</span>

                                                @else
                                                    <span class="text-info font-weight-bold">Previous File Not Available:</span>

                                                @endif
                                                <input id="uploadAudio" type="file" name ="sub_title_file"
                                                       class="form-control {{ $errors->has('audio') ? 'is-invalid' : '' }}"
                                                       value="{{ old('sub_title_file',$quranProgramFile->sub_title_file )}}"
                                                       placeholder="Enter Recite Subtitle" accept=".srt">
                                                @if ($errors->has('sub_title_file'))
                                                    <div class="invalid-feedback">{{ $errors->first('sub_title_file') }}</div>
                                                @endif
                                            </div>
                                        </div>


                                        <div class="col-sm-12 col-md-12">
                                            <div class="form-group">
                                                <label for="Name">Translation <span class="text-danger">*</span></label>
                                                <textarea type="text" name="translation"
                                                          class="summernote form-control {{ $errors->has('translation') ? 'is-invalid' : '' }}"
                                                          placeholder="Enter Surah Recitation Translation">{{ old('translation',$quranProgramFile->translation ) }}</textarea>
                                                @if ($errors->has('translation'))
                                                    <div
                                                        class="invalid-feedback">{{ $errors->first('translation') }}</div>
                                                @endif
                                            </div>
                                        </div>


                                        <div class="col-sm-12 col-md-12">
                                            <div class="form-group">
                                                <label for="Name">Transliteration <span class="text-danger">*</span></label>
                                                <textarea type="text" name="transliteration"
                                                          class="summernote form-control {{ $errors->has('transliteration') ? 'is-invalid' : '' }}"
                                                          placeholder="Enter Surah Recitation Translation">{{ old('transliteration',$quranProgramFile->transliteration ) }}</textarea>
                                                @if ($errors->has('transliteration'))
                                                    <div
                                                        class="invalid-feedback">{{ $errors->first('transliteration') }}</div>
                                                @endif
                                            </div>
                                        </div>



                                        <div class="col-sm-12 col-md-12" hidden>
                                            <div class="form-group">
                                                <label for="File">Surah <span class="text-danger"></span></label>
                                                <input type="text" name="quran_program_list_id " id="quran_program_list_id "
                                                       class="form-control" value="{{ old('quran_program_list_id ',$quranProgramFile->quran_program_list_id)}}"
                                                />
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- /.card-body -->
                                <div class="card-footer text-center">
                                    <button type="submit" class="btn btn-success bg-gradient-success mr-2">Submit
                                    </button>
                                    <a href="{{route('admin.quran_program_lists.index')}}"
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



    @endpush
    @endonce

