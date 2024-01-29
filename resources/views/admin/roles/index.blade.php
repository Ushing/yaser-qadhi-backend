@extends('layouts.admin')
@push('styles')
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/limonte-sweetalert2/5.0.7/sweetalert2.min.css" rel="stylesheet">

@endpush

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
                            <li class="breadcrumb-item active"><a href="{{route('admin.roles.index')}}">Roles</a></li>
                        </ol>
                    </div>
                </div>
            </div><!-- /.container-fluid -->
        </section>

        <div class="row">
            @can('role-create')
                <div class="mb-3">
                    <a href="{{route('admin.roles.create')}}" class="btn btn-success mx-3 float-right font-weight-bold"><i class="fas fa-plus"></i> Add New</a>
                </div>

            @endcan
        </div>

        <!-- Main content -->
        <section class="content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header">
                                <h3 class="card-title font-weight-bolder">Roles List</h3>

                            </div>
                            <!-- /.card-header -->
                            <div class="card-body">
                                <table class="table table-bordered">
                                    <thead>
                                    <tr>
                                        <th width="5%">Sl</th>
                                        <th width="10%">Name</th>
                                        <th width="60%">Permissions</th>
                                        <th width="15%">Action</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach ($roles as $role)
                                        <tr>
                                            <td>{{ $loop->index+1 }}</td>
                                            <td style="font-size: 1rem;font-weight: bold">{{ ucwords($role->name) }}</td>
                                            <td>
                                                @foreach ($role->permissions as $perm)
                                                    <span class="font-weight-bold badge badge-info mt-2 mr-2" style="font-size: .9rem">
                                                {{ $perm->name }}
                                            </span>
                                                @endforeach
                                            </td>
                                            <td>
                                           <div class="d-flex">


                                               @can('role-edit')
                                               <a class="btn btn-sm btn-success text-white" href="{{ route('admin.roles.edit', $role->id) }}"><i class="fa fa-edit"></i></a>

                                               @endcan
                                                @can('role-delete')
                                                    @if(!\Illuminate\Support\Facades\Auth::user()->roles()->first()->name == 'super-admin')
                                               <form method="POST" action="{{ route('admin.roles.destroy', $role->id) }}">
                                                   @csrf
                                                   <input name="_method" type="hidden" value="DELETE">
                                                   <button type="submit" class="btn btn-sm btn-danger ml-2 text-white show-alert-delete-box btn-sm" data-toggle="tooltip" title='Delete'><i class="fa fa-trash"></i></button>
                                               </form>
                                                       @endif
                                               @endcan
                                           </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>

@endsection
@once
    @push('scripts')
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"></script>

        <script type="text/javascript">
            $('.show-alert-delete-box').click(function(event){
                var form =  $(this).closest("form");
                var name = $(this).data("name");
                event.preventDefault();
                swal({
                    title: "Are you sure you want to delete this record?",
                    text: "If you delete this, it will be gone forever.",
                    icon: "warning",
                    type: "warning",
                    buttons: ["Cancel","Yes!"],
                    confirmButtonColor: '#d63049',
                    cancelButtonColor: '#200a0a',
                    confirmButtonText: 'Yes, delete it!'
                }).then((willDelete) => {
                    if (willDelete) {
                        form.submit();
                    }
                });
            });
        </script>
    @endpush
@endonce

