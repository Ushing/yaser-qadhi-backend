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
                            <li class="breadcrumb-item active"><a href="{{route('admin.lecture.show',$event->id)}}">show</a></li>
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
                                <h3 class="card-title">Event Details</h3>
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
                                                {{$event->id}}
                                            </td>
                                        </tr>

                                        <tr>
                                            <th>
                                                Title
                                            </th>
                                            <td>
                                                {{$event->event_title}}
                                            </td>
                                        </tr>

                                        <tr>
                                            <th>
                                                 Details
                                            </th>
                                            <td>
                                                {{$event->event_details}}
                                            </td>
                                        </tr>

                                        <tr>
                                            <th>
                                                Date
                                            </th>
                                            <td>
                                                {{$event->event_date}}

                                            </td>
                                        </tr>


                                        <tr>
                                            <th>
                                                Status
                                            </th>
                                            <td>
                                                {!! setStatus($event->status) !!}
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



