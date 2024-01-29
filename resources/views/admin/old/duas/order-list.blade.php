@extends('layouts.admin')
@push('styles')
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.2/css/jquery.dataTables.min.css">
@endpush
@section('content')
    @php
    use Illuminate\Support\Carbon;
    @endphp
    <div class="content">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>Short Dua</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="{{route('admin.home')}}">Home</a></li>
                        </ol>
                    </div>
                </div>
            </div>
        </section>

        <div class="row">
            @can('dua-create')
                <div class="mb-3">
                    <a href="{{route('admin.dua.index')}}" class="btn btn-success mx-3 float-right font-weight-bold"><i class="fas fa-backward"></i> Back To List</a>
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
                                <h3 class="card-title font-weight-bolder">Dua Lists</h3>

                            </div>
                            <!-- /.card-header -->
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table id="duaShortTable" class="table table-bordered display" style="width:100%">
                                        <thead>
                                        <tr>
                                            <th width="30px">#</th>
                                            <th>ID</th>
                                            <th>Reference ID</th>
                                            <th>Title</th>
                                            <th>Position</th>
                                            <th>Updated At</th>
                                        </tr>
                                        </thead>
                                        <tbody id="tableContent">
                                        @foreach($duas as $dua)
                                            <tr class="row1" data-id="{{ $dua->id }}">
                                                <td class="pl-3"><i class="fa fa-sort"></i></td>
                                                <td>{{ $dua->id }}</td>
                                                <td>{{$dua->reference_id}} </td>
                                                <td>{{ $dua->title }}</td>
                                                <td class="font-weight-bolder">{{ $dua->position }}</td>
                                                <td>{{ Carbon::parse($dua->updated_at)->diffForHumans() }}</td>
                                            </tr>
                                        @endforeach
                                        </tbody>
                                    </table>
                                </div>
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
        <script src="https://code.jquery.com/jquery-3.5.1.js"></script>
        <script src="https://cdn.datatables.net/1.13.2/js/jquery.dataTables.min.js"></script>
            <script>
                $(document).ready(function () {
                    $('#duaShortTable').DataTable({
                        lengthMenu: [
                            ["All"]
                        ],
                    });
                });
            </script>

<script>
    $(document).ready(function () {
        let $duaRows = $("#tableContent");
        $duaRows.sortable({
            cancel: 'thead',
            items: "tr",
            cursor: 'move',
            opacity: 0.6,
            update: () => {
                let items = $duaRows.sortable('toArray', {attribute: 'data-id'});
                let ids = $.grep(items, (item) => item !== "");
                let token = $('meta[name="csrf-token"]').attr('content');
                $.post('{{ route('admin.dua.reorder') }}', {
                    _token: token,
                    ids
                }).done(function (response) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Dua Order Position Is Updated',
                        showConfirmButton: false,
                        timer: 800
                    })
                    location.reload();
                }).fail(function (response) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Position Is Failed To Update',
                        timer: 800

                    })
                        location.reload();
                    });
            }
        });
        $('table, .sortable').disableSelection();
    });
</script>
        @endpush
    @endonce



