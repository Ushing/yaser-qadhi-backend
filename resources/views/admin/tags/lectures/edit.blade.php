@extends('layouts.admin')
@push('styles')
    <style>
        .select2-container--default .select2-selection--multiple .select2-selection__rendered {
            height: 5rem;!important;
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
                        <h1>Edit Lecture Tags</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="{{route('admin.home')}}">Home</a></li>

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
                                <h3 class="card-title">Lecture Tag Edit</h3>
                            </div>
                            <form action="{{route('admin.tagDetails.update')}}" method="post" enctype="multipart/form-data">
                                @csrf
                                @method('patch')
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-sm-12 col-md-12">
                                            <div class="form-group">
                                                <label for="exampleSelectGender">Select Tags <span class="text-danger">*</span></label>
                                                <select class="select2 my-select2 form-control {{ $errors->has('tag_id') ? 'is-invalid' : '' }}"
                                                        name="tag_ids[]" id="tag_id" style="width: 100%" multiple>
                                                    <option value="">Choose Tags</option>
                                                    @foreach($tags as $tag)
                                                        <option value="{{$tag->id}}" {{ (in_array($tag->id, $selectedTagIds)) ? 'selected' : '' }}>{{$tag->name}}</option>
                                                    @endforeach
                                                </select>
                                                @if ($errors->has('tag_id'))
                                                    <div class="invalid-feedback">{{ $errors->first('tag_id') }}</div>
                                                @endif
                                            </div>
                                        </div>

                                        <input type="text" name="content_type"
                                               class="form-control d-none {{ $errors->has('content_type') ? 'is-invalid' : '' }}"
                                               value="{{$contentType}}"
                                               placeholder="Enter content type"/>

                                        <input type="text" name="content_id"
                                               class="form-control d-none {{ $errors->has('content_id') ? 'is-invalid' : '' }}"
                                               value="{{$contentId}}"
                                               placeholder="Enter content id"/>

                                    </div>
                                </div>
                                <!-- /.card-body -->
                                <div class="card-footer text-center">
                                    <button type="submit" class="btn btn-success bg-gradient-success mr-2">Submit
                                    </button>
                                    <a href="{{route('admin.tagDetails.lecture')}}"
                                       class="btn btn-danger bg-gradient-danger mr-2">Cancel</a>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </section>

@endsection



