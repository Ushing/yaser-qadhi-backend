@extends('layouts.admin')

@section('content')

    <div class="content">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>Roles</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="{{route('admin.home')}}">Home</a></li>
                            <li class="breadcrumb-item active"><a href="{{route('admin.roles.edit',$role->id)}}">Edit</a></li>
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
                                <h3 class="card-title">Roles Create</h3>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <form action="{{ route('admin.roles.update', $role->id) }}" method="POST">
                                        @method('PUT')
                                        @csrf
                                        <div class="form-group">
                                            <label for="name">Role Name</label>
                                            <input type="text" class="form-control" id="name" value="{{ $role->name }}" name="name" placeholder="Enter a Role Name">
                                        </div>

                                        <div class="form-group">
                                            <label for="name">Permissions</label>

                                            <div class="form-check">
                                                <input type="checkbox" class="form-check-input" id="checkPermissionAll" value="1" {{ \App\Models\User::roleHasPermissions($role, $all_permissions) ? 'checked' : '' }}>
                                                <label class="form-check-label" for="checkPermissionAll">All</label>
                                            </div>
                                            <hr>
                                            @php $i = 1; @endphp
                                            @foreach ($permission_groups as $group)
                                                <div class="row">
                                                    @php
                                                        $permissions = \App\Models\User::getpermissionsByGroupName($group->name);
                                                        $j = 1;
                                                    @endphp

                                                    <label for="name" style="text-decoration: underline">{{ucwords($group->name)}}</label>

                                                    <div class="col-9 role-{{ $i }}-management-checkbox">

                                                        @foreach ($permissions as $permission)
                                                            <div class="form-check">
                                                                <input type="checkbox" class="form-check-input" onclick="checkSinglePermission('role-{{ $i }}-management-checkbox', '{{ $i }}Management', {{ count($permissions) }})" name="permissions[]" {{ $role->hasPermissionTo($permission->name) ? 'checked' : '' }} id="checkPermission{{ $permission->id }}" value="{{ $permission->name }}">
                                                                <label class="form-check-label" for="checkPermission{{ $permission->id }}">{{ $permission->name }}</label>
                                                            </div>
                                                            @php  $j++; @endphp
                                                        @endforeach
                                                        <br>
                                                    </div>

                                                </div>
                                                @php  $i++; @endphp
                                            @endforeach
                                        </div>
                                        <button type="submit" class="btn btn-primary mt-4 pr-4 pl-4">Update Role</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        @endsection

        @push('scripts')
            <script src="{{ asset('admin/dist/custom/jquery-2.2.4.min.js') }}"></script>
            <script>
                $(document).ready(function () {
                    $("#checkPermissionAll").click(function(){
                        if($(this).is(':checked')){
                            // check all the checkbox
                            $('input[type=checkbox]').prop('checked', true);
                        }else{
                            // un check all the checkbox
                            $('input[type=checkbox]').prop('checked', false);
                        }
                    });
                });
            </script>
    @endpush



