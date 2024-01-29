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
                            <li class="breadcrumb-item active"><a href="{{route('admin.recite_languages.index')}}">Recite Languages</a></li>
                        </ol>
                    </div>
                </div>
            </div><!-- /.container-fluid -->
        </section>

        <div class="row">
                <div class="mb-3">
                    <a href="#" data-size="lg" data-url="{{ route('admin.recite_languages.create') }}" data-ajax-popup="true"
                       data-bs-toggle="tooltip" title="{{__('Create')}}" data-title="{{__('Create Recite Language')}}"
                       class="btn btn-success mx-3 float-right font-weight-bold"><i class="fas fa-plus"></i> Add New</a>
                </div>

        </div>

        <!-- Main content -->
        <section class="content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header">
                                <h3 class="card-title font-weight-bolder">Recite Language Lists</h3>

                            </div>
                            <!-- /.card-header -->
                            <div class="card-body">
                                @include('partials.table')
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>


@endsection



