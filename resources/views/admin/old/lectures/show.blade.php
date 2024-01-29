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
                            <li class="breadcrumb-item active"><a href="{{route('admin.lecture.show',$lecture->id)}}">show</a></li>
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
                                <h3 class="card-title">Lecture Details</h3>
                            </div>
                            <div class="row">
                                <div class="card-body">
                                    <table class="table table-bordered table-striped">
                                        <tbody>
                                        <tr>
                                            <th>
                                                ID
                                            </th>
                                            <td>
                                                {{$lecture->id}}
                                            </td>
                                        </tr>

                                        <tr>
                                            <th>
                                                Name
                                            </th>
                                            <td>
                                                {{$lecture->title}}
                                            </td>
                                        </tr>

                                        <tr>
                                            <th>
                                                 Sub Category
                                            </th>
                                            <td>
                                                {{$lecture->lectureSubCategory->name ??''}}
                                            </td>
                                        </tr>

                                        <tr>
                                            <th>
                                                Description
                                            </th>
                                            <td>
                                                {!!  $lecture->description !!}
                                            </td>
                                        </tr>



                                        <tr>
                                            <th>
                                                Audio
                                            </th>
                                            <td>
                                                <audio class="ml-5" controls>
                                                    {{--  must need app url which on hosted. Like in local server its host on http://127.0.0.1:8000 --}}
                                                    <source src="{{\Illuminate\Support\Facades\Storage::disk('s3')->url('lecture/audios/'.$lecture->audio)}}" type="audio/mp3">
                                                </audio>
                                            </td>
                                        </tr>

                                        <tr>
                                            <th>
                                                Video
                                            </th>
                                            <td>
                                                <video class="ml-5" width="400" height="240" controls>
                                                    {{--  must need app url which on hosted. Like in local server its host on http://127.0.0.1:8000 --}}
                                                    <source src="{{\Illuminate\Support\Facades\Storage::disk('s3')->url('lecture/videos/'.$lecture->video)}}" type="video/mp4">
                                                </video>
                                            </td>
                                        </tr>

                                        <tr>
                                            <th>
                                                Status
                                            </th>
                                            <td>
                                                {!! setStatus($lecture->status) !!}
                                            </td>
                                        </tr>



                                        </tbody>
                                    </table>

                                </div>
                            </div>

                            <div class="card-footer text-center">
                                <a class="btn btn-success bg-gradient-success mr-2" href="{{route('admin.lecture.index')}}"> <i class="fas fa-backward"></i> Back To list</a>

                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </section>

        @endsection



