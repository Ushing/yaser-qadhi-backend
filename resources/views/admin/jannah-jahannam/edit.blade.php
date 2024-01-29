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
                            <li class="breadcrumb-item active"><a href="{{route('admin.jannah-jahannam.edit',$Jannah->id)}}">Edit</a></li>
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
                                <h3 class="card-title">Jannah and Jahannam Edit</h3>
                            </div>
                            <form action="{{ route('admin.jannah-jahannam.update',$Jannah->id) }}" method="post" enctype="multipart/form-data">
                                @csrf
                                @method('PUT')
                                <div class="card-body">
                                    <div class="row">

                                        <div class="col-sm-12 col-md-6">
                                            <div class="form-group">
                                                <label for="Name">Title <span class="text-danger">*</span></label>
                                                <input required type="text" name="title" class="form-control {{ $errors->has('title') ? 'is-invalid' : '' }}" value="{{ old('title',$Jannah->title) }}" placeholder="" />
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
                                                    value="{{ old('icon_image', $Jannah->icon_image) }}"
                                                    placeholder="Enter Icon Image">

                                                @if (isset($Jannah->icon_image))
                                                    <span class="text-black">{{ $Jannah->icon_image }}</span>
                                                @else
                                                    <span class="text-info font-weight-bold">File Not
                                                        Available</span>
                                                @endif

                                                @if (isset($Jannah->icon_image))
                                                    <img class="img-feature"
                                                        src="{{ asset('images/jannahAndJahannam/' . $Jannah->icon_image) }}" width="200">
                                                @else
                                                    No Thumbnail
                                                @endif
                                                <br><br>
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
                                                    value="{{ old('cover_image', $Jannah->cover_image) }}"
                                                    placeholder="Enter Icon Image">

                                                @if (isset($Jannah->cover_image))
                                                    <span class="text-black">{{ $Jannah->cover_image }}</span>
                                                @else
                                                    <span class="text-info font-weight-bold">File Not
                                                        Available</span>
                                                @endif

                                                @if (isset($Jannah->cover_image))
                                                    <img class="img-feature"
                                                        src="{{ asset('images/jannahAndJahannam/' . $Jannah->cover_image) }}" width="200">
                                                @else
                                                    No Thumbnail
                                                @endif
                                                <br><br>
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
                                    <a href="{{route('admin.Akidah_list.index')}}"
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

