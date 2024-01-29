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
                            <li class="breadcrumb-item active"><a href="{{route('admin.quran_program_lists.show',$quranInterview->id)}}">show</a></li>
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
                                <h3 class="card-title">Quran Interview Details</h3>
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
                                                {{$quranInterview->id}}
                                            </td>
                                        </tr>

                                        <tr>
                                            <th>
                                                Title
                                            </th>
                                            <td>
                                                {{$quranInterview->title}}
                                            </td>
                                        </tr>

                                        <tr>
                                            <th>
                                                 Reference Id
                                            </th>
                                            <td>
                                                {{$quranInterview->reference_id ??''}}
                                            </td>
                                        </tr>




                                        <tr>
                                            <th>
                                                Audio
                                            </th>
                                            <td>
                                                <audio class="ml-5" controls>
                                                    <source src="{{asset('uploads/quranInterview/audios/'.$quranInterview->audio)}}" type="audio/mp3">
                                                </audio>
                                            </td>
                                        </tr>

                                        <tr>
                                            <th>
                                                Video
                                            </th>
                                            <td>
                                                <video class="ml-5" width="400" height="240" controls>
                                                    <source src="{{asset('uploads/quranInterview/videos/'.$quranInterview->video)}}" type="video/mp4">
                                                </video>
                                            </td>
                                        </tr>

                                        <tr>
                                            <th>
                                                Status
                                            </th>
                                            <td>
                                                {!! setStatus($quranInterview->status) !!}
                                            </td>
                                        </tr>
                                        </tbody>
                                    </table>

                                </div>
                            </div>

                            <div class="card-footer text-center">
                                <a class="btn btn-success bg-gradient-success mr-2" href="{{route('admin.quran_interviews.index')}}"> <i class="fas fa-backward"></i> Back To list</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>



{{--        <section class="content">--}}
{{--            <div class="container-fluid">--}}
{{--                <div class="row">--}}
{{--                    <div class="col-md-12">--}}
{{--                        <!-- general form elements -->--}}
{{--                        <div class="card card-info">--}}
{{--                            <div class="card-header">--}}
{{--                                <h3 class="card-title">Quran Program Files</h3>--}}
{{--                            </div>--}}
{{--                            <div class="row">--}}
{{--                                <div class="card-body">--}}
{{--                                    <table class="table table-bordered">--}}
{{--                                        <thead>--}}
{{--                                        <tr>--}}
{{--                                            <th>Sl</th>--}}
{{--                                            <th>Translation</th>--}}
{{--                                            <th>Transliteration</th>--}}
{{--                                            <th>SubTitle File</th>--}}
{{--                                            <th>Action</th>--}}
{{--                                        </tr>--}}
{{--                                        </thead>--}}
{{--                                        <tbody>--}}
{{--                                        @foreach ($quranInterviewFiles as $file)--}}
{{--                                            <tr>--}}
{{--                                                <td>{{ $loop->index+1 }}</td>--}}
{{--                                                <td>{{ \Illuminate\Support\Str::limit(strip_tags($file->translation),30) }}</td>--}}
{{--                                                <td>{{ \Illuminate\Support\Str::limit(strip_tags($file->transliteration),30) }}</td>--}}
{{--                                                <td>{{ isset($file->sub_title_file) ? 'Yes' : 'No File' }}</td>--}}
{{--                                                <td>--}}
{{--                                                    <div class="">--}}
{{--                                                        <a class="btn btn-sm btn-success text-white" href="{{ route('admin.quranInterviewFiles.edit',$file->id) }}"><i class="fa fa-edit"></i></a>--}}
{{--                                                    </div>--}}
{{--                                                </td>--}}
{{--                                            </tr>--}}
{{--                                        @endforeach--}}
{{--                                        </tbody>--}}
{{--                                    </table>--}}

{{--                                </div>--}}
{{--                            </div>--}}

{{--                        <div class="card-footer text-center">--}}
{{--                            <a class="btn btn-success bg-gradient-success mr-2" href="{{route('admin.quran_program_lists.index')}}"> <i class="fas fa-backward"></i> Back To list</a>--}}
{{--                        </div>--}}
{{--                        </div>--}}
{{--                    </div>--}}
{{--                </div>--}}
{{--            </div>--}}
{{--        </section>--}}

        @endsection



