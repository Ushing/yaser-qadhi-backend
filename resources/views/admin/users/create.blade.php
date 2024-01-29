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
                            <li class="breadcrumb-item active"><a href="{{route('admin.users.create')}}">Create</a></li>
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
                                <h3 class="card-title">Users Create</h3>
                            </div>
                            <form action="{{ route('admin.users.store') }}" method="post" enctype="multipart/form-data">
                                @csrf
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-sm-12 col-md-6">
                                            <div class="form-group">
                                                <label for="Name">Name <span class="text-danger">*</span></label>
                                                <input type="text" name="name"
                                                       class="form-control {{ $errors->has('name') ? 'is-invalid' : '' }}"
                                                       value="{{ old('name') }}"
                                                       placeholder="Enter User Name"/>
                                                @if ($errors->has('name'))
                                                    <div class="invalid-feedback">{{ $errors->first('name') }}</div>
                                                @endif
                                            </div>
                                        </div>


                                        <div class="col-sm-12 col-md-6">
                                            <div class="form-group">
                                                <label for="Email">Email <span class="text-danger">*</span></label>
                                                <input type="email" name="email"
                                                       class="form-control {{ $errors->has('email') ? 'is-invalid' : '' }}"
                                                       value="{{ old('email') }}"
                                                       placeholder="Enter User Email"/>
                                                @if ($errors->has('email'))
                                                    <div class="invalid-feedback">{{ $errors->first('email') }}</div>
                                                @endif
                                            </div>
                                        </div>

                                        <div class="col-sm-12 col-md-6">

                                            <div class="form-group">
                                                <label for="Roles">Role <span class="text-danger">*</span></label>
                                                <select
                                                    class="select2 form-control {{ $errors->has('roles') ? 'is-invalid' : '' }}"
                                                    name="roles[]" id="roles" style="width: 100%" required>
                                                    <option value="">Choose Role</option>
                                                    @foreach ($roles as $role)
                                                        <option class="text-black" value="{{ lcfirst($role->name) }}">{{ ucwords($role->name) }}</option>
                                                    @endforeach
                                                </select>
                                                @if ($errors->has('roles'))
                                                    <div class="invalid-feedback">{{ $errors->first('roles') }}</div>
                                                @endif
                                            </div>
                                        </div>


                                        <div class="col-sm-12 col-md-6">
                                            <div class="form-group">
                                                <label for="Name">Password <span class="text-danger">*</span></label>
                                                <input type="password" name="password"
                                                       class="form-control {{ $errors->has('password') ? 'is-invalid' : '' }}"
                                                       value="{{ old('password') }}"
                                                       placeholder="Enter User Password"/>
                                                @if ($errors->has('password'))
                                                    <div class="invalid-feedback">{{ $errors->first('password') }}</div>
                                                @endif
                                            </div>
                                        </div>





                                    </div>
                                </div>
                                <!-- /.card-body -->
                                <div class="card-footer text-center">
                                    <button type="submit" class="btn btn-success bg-gradient-success mr-2">Submit
                                    </button>
                                    <a href="{{route('admin.users.index')}}"
                                       class="btn btn-danger bg-gradient-danger mr-2">Cancel</a>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        @endsection


