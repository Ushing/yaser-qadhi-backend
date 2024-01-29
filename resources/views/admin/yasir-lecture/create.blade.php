@extends('layouts.admin')
@push('styles')
    <style>
        .progress-bar {
            background-color: #7846b4 !important;
            font-size: large;
            font-weight: bold;
        }

        #quranVideoProgressBar {
            display: none;
        }

        #quranAudioProgressBar {
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
                            <li class="breadcrumb-item active"><a href="{{route('admin.yasir-lecture.create')}}">Create</a>
                            </li>
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
                                <h3 class="card-title">{{\Illuminate\Support\Str::upper('yasir-lecture')}}</h3>
                            </div>
                            <form action="{{ route('admin.yasir-lecture.store') }}" method="post"
                                  enctype="multipart/form-data">
                                @csrf
                                <div class="card-body">
                                    <div class="row">

                                        <div class="col-sm-12 col-md-6">
                                            <div class="form-group">
                                                <label for="Name">Title <span class="text-danger">*</span></label>
                                                <input type="text" name="title"
                                                       class="form-control {{ $errors->has('title') ? 'is-invalid' : '' }}"
                                                       value="{{ old('title') }}" placeholder="Enter Title" />
                                                @if ($errors->has('title'))
                                                    <div class="invalid-feedback">{{ $errors->first('title') }}</div>
                                                @endif
                                            </div>
                                        </div>

                                        <div class="col-sm-12 col-md-6">
                                            <div class="form-group">
                                                <label for="exampleSelectGender">Select Icon Image</label>
                                                <input type="file" name="icon_image"
                                                    class="form-control {{ $errors->has('icon_image') ? 'is-invalid' : '' }}"
                                                    value="{{ old('icon_image') }}" placeholder="Enter Icon Image">
                                                @if ($errors->has('icon_image'))
                                                    <div class="invalid-feedback">{{ $errors->first('icon_image') }}</div>
                                                @endif
                                            </div>
                                        </div>

                                        <div class="col-sm-12 col-md-6">
                                            <div class="form-group">
                                                <label for="exampleSelectGender">Select Cover Image</label>
                                                <input type="file" name="cover_image"
                                                    class="form-control {{ $errors->has('cover_image') ? 'is-invalid' : '' }}"
                                                    value="{{ old('cover_image') }}" placeholder="Enter Cover Image">
                                                @if ($errors->has('cover_image'))
                                                    <div class="invalid-feedback">{{ $errors->first('cover_image') }}</div>
                                                @endif
                                            </div>
                                        </div>



                                    </div>
                                </div>
                                <!-- /.card-body -->
                                <div class="card-footer text-center">
                                    <button type="submit" class="btn btn-success bg-gradient-success mr-2">Submit
                                    </button>
                                    <a href="{{route('admin.yasir-lecture.index')}}"
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

        @endpush
        @endonce

