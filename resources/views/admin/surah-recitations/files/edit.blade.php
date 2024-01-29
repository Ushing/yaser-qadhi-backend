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
                            <li class="breadcrumb-item active"><a href="{{route('admin.surahReciteFiles.update',$reciteFile->id)}}">Edit</a></li>
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
                            <form action="{{ route('admin.surahReciteFiles.update',$reciteFile->id) }}" method="post" enctype="multipart/form-data">
                                @csrf
                                @method('PUT')
                                <div class="card-body">
                                    <div class="row">

                                        <div class="col-sm-12 col-md-6">
                                            <div class="form-group">
                                                <label for="exampleSelectGender">Language <span class="text-danger">*</span></label>
                                                <select class="form-control {{ $errors->has('recite_language_id') ? 'is-invalid' : '' }}" name="recite_language_id" id="recite_language_id">
                                                    <option value="">---Choose Lecture Sub Category ---</option>
                                                    @foreach($languages as $language)
                                                        <option value="{{$language->id}}" {{$language->id == $reciteFile->recite_language_id ? 'selected' : ''}}>{{$language->title}}</option>
                                                    @endforeach
                                                </select>
                                                @if ($errors->has('recite_language_id'))
                                                    <div class="invalid-feedback">{{ $errors->first('recite_language_id') }}</div>
                                                @endif
                                            </div>
                                        </div>



                                        <div class="col-sm-12 col-md-6">
                                            <div class="form-group">
                                                <label for="exampleSelectGender">Select Srt File <span
                                                        class="text-danger"></span></label>
                                                @if(isset($reciteFile->sub_title_file))
                                                    <span class="text-info font-weight-bold">(Old:</span>  <span class="text-black"> {{$reciteFile->sub_title_file}})</span>

                                                @else
                                                    <span class="text-info font-weight-bold">Previous File Not Available:</span>

                                                @endif
                                                <input id="uploadAudio" type="file" name ="sub_title_file"
                                                       class="form-control {{ $errors->has('audio') ? 'is-invalid' : '' }}"
                                                       value="{{ old('sub_title_file',$reciteFile->sub_title_file )}}"
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
                                                          placeholder="Enter Surah Recitation Translation">{{ old('translation',$reciteFile->translation ) }}</textarea>
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
                                                          placeholder="Enter Surah Recitation Translation">{{ old('transliteration',$reciteFile->transliteration ) }}</textarea>
                                                @if ($errors->has('transliteration'))
                                                    <div
                                                        class="invalid-feedback">{{ $errors->first('transliteration') }}</div>
                                                @endif
                                            </div>
                                        </div>



                                        <div class="col-sm-12 col-md-12" hidden>
                                            <div class="form-group">
                                                <label for="File">Surah <span class="text-danger"></span></label>
                                                <input type="text" name="surah_recitation_id" id="surah_recitation_id"
                                                       class="form-control" value="{{ old('sub_title_file',$reciteFile->surah_recitation_id )}}"
                                                />
                                            </div>
                                        </div>






                                    </div>
                                </div>
                                <!-- /.card-body -->
                                <div class="card-footer text-center">
                                    <button type="submit" class="btn btn-success bg-gradient-success mr-2">Submit
                                    </button>
                                    <a href="{{route('admin.surah_recitations.index')}}"
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

