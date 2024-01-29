@extends('layouts.admin')

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
                            <li class="breadcrumb-item active"><a href="{{route('admin.event.create')}}">Create</a></li>
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
                                <h3 class="card-title">Event Create</h3>
                            </div>
                            <form action="{{ route('admin.event.store') }}" method="post" enctype="multipart/form-data">
                                @csrf
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-sm-12 col-md-6">
                                            <div class="form-group">
                                                <label for="Name">Title <span class="text-danger">*</span></label>
                                                <input type="text" name="event_title"
                                                       class="form-control {{ $errors->has('event_title') ? 'is-invalid' : '' }}"
                                                       value="{{ old('event_title') }}"
                                                       placeholder="Enter Event Title"/>
                                                @if ($errors->has('event_title'))
                                                    <div class="invalid-feedback">{{ $errors->first('event_title') }}</div>
                                                @endif
                                            </div>
                                        </div>

                                        <div class="col-sm-12 col-md-6">
                                            <div class="form-group">
                                                <label for="Name">Date <span class="text-danger">*</span></label>
                                                <input type="date" name="event_date"
                                                       class="form-control {{ $errors->has('event_date') ? 'is-invalid' : '' }}"
                                                       value="{{ old('event_date') }}"
                                                       placeholder="Enter Event Date"/>
                                                @if ($errors->has('event_date'))
                                                    <div class="invalid-feedback">{{ $errors->first('event_date') }}</div>
                                                @endif
                                            </div>
                                        </div>


                                        <div class="col-sm-12 col-md-12">
                                            <div class="form-group">
                                                <label for="Details">Details <span class="text-danger">*</span></label>
                                                <textarea type="text" name="event_details"
                                                          class="form-control {{ $errors->has('event_details') ? 'is-invalid' : '' }}"
                                                          placeholder="Enter Event Details">{{ old('event_details') }}</textarea>
                                                @if ($errors->has('event_details'))
                                                    <div
                                                        class="invalid-feedback">{{ $errors->first('event_details') }}</div>
                                                @endif
                                            </div>
                                        </div>

                                        <div class="col-sm-12 col-md-6">
                                            <div class="form-group">
                                                <p class="font-weight-bold">Status <span class="text-danger">*</span>
                                                </p>
                                                <div class="d-flex justify-content-between" style="width: 40%" >
                                                    <div>
                                                        <input type="radio" id="active" name="status" value="1">
                                                        <label for="active">Active</label><br>
                                                    </div>

                                                    <div>
                                                        <input type="radio" id="inactive" name="status" value="0">
                                                        <label for="inactive">Inactive</label><br>
                                                    </div>
                                                </div>

                                                @if ($errors->has('status'))
                                                    <div class="invalid-feedback">{{ $errors->first('status') }}</div>
                                                @endif
                                            </div>
                                        </div>


                                    </div>
                                </div>
                                <!-- /.card-body -->
                                <div class="card-footer text-center">
                                    <button type="submit" class="btn btn-success bg-gradient-success mr-2">Submit
                                    </button>
                                    <a href="{{route('admin.event.index')}}"
                                       class="btn btn-danger bg-gradient-danger mr-2">Cancel</a>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        @endsection


