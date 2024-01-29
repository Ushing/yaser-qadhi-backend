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
                            <li class="breadcrumb-item active"><a href="{{route('admin.quran_program_category.index')}}">Quran Program Category</a></li>
                        </ol>
                    </div>
                </div>
            </div><!-- /.container-fluid -->
        </section>

        <div class="row">
                <div class="mb-3">
                    <a href="#" data-size="lg" data-url="{{ route('admin.quran_program_category.create') }}" data-ajax-popup="true"
                       data-bs-toggle="tooltip" title="{{__('Create')}}" data-title="{{__('Create Quran Program Category')}}"
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
                                <h3 class="card-title font-weight-bolder">Quran Program Category</h3>

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


    <!-- delete contact modal start-->
    <div class="modal fade" id="tag-delete-modal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Delete This Item</h5>
                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body py-3">
                    <div class="row">
                        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-12">
                            <div class="form-group  m-form__group mb-0">
                                <input type="hidden" name="id" id="tagId">
                                <h5 class="text-danger mb-0">Are you Sure want to delete ?</h5>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    {{-- <button type="submit" class="btn btn-success mr-3"><i class="flaticon2-paperplane"></i>Save</button>
                     <button type="reset" class="btn btn-danger"><i class="flaticon-close"></i>Cancel</button>--}}

                    <button type="button" class="btn btn-danger mr-3" data-bs-dismiss="modal"><i
                            class="flaticon-close"></i> Not Now
                    </button>
                    <button type="button" class="btn btn-success" id="delete-tag-btn"><i
                            class="flaticon2-paperplane"></i> Yes
                    </button>
                </div>
            </div>
        </div>
    </div>
@endsection

@once
    @push('scripts')

        <script src="{{asset('admin/plugins/bootstrap-switch/js/bootstrap-switch.min.js')}}"></script>

        <script>
            $(document).on('click', '.btn-delete', function (e) {
                var tagId = $(this).data('tag-id')
                $('#tagId').val(tagId);
                $('#tag-delete-modal').modal('show');
            });

            $('#delete-tag-btn').click(function (e) {
                e.preventDefault();
                var tagId = $('#tagId').val();
                $.ajax({
                    type: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    url: baseUrl + 'admin/quran_program_category/' + tagId,
                    dataType: 'JSON',
                    data: {
                        'tagId': tagId,
                    },
                    processData: false,
                    contentType: false,
                    success: function (response) {
                        $('#tag-delete-modal').modal('hide');
                        Swal.fire({
                            icon: 'success',
                            title: 'Success',
                            text: "Item Deleted successfully",
                            type: 'success',
                            showConfirmButton: true,
                            timer: 9000
                        });
                        location.reload();

                    },
                    error: function (xhr) {
                        $('#tag-delete-modal').modal('hide');
                        Swal.fire({
                            icon: 'error',
                            title: 'Oops...',
                            text: "You have no access to delete",
                            type: 'error',
                            showConfirmButton: false,
                            //confirmButtonText: 'Yes'
                            timer: 5000
                        });
                        location.reload();
                        console.log(xhr.responseText);
                    }
                });
            })
        </script>

        <script>
            $("input[data-bootstrap-switch]").each(function () {
                $(this).bootstrapSwitch('state', $(this).prop('checked'));
            })
        </script>
    @endpush
@endonce
