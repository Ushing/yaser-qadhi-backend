@extends('layouts.admin')
@push('styles')
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.2/css/jquery.dataTables.min.css">
    <style>
        table.table-bordered > tbody > tr > td {
            border: 1px solid #dee2e6;
        }
    </style>
@endpush

@section('content')
    <div class="content">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>Dua Tags</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="{{route('admin.home')}}">Home</a></li>
                        </ol>
                    </div>
                </div>
            </div>
        </section>
        <br>
        <br>

        <!-- Main content -->
        <section class="content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header">
                                <h3 class="card-title font-weight-bolder">Dua Tag List</h3>
                            </div>
                            <div class="card-body">
                                <table class="table table-bordered" id="duaTagTable">
                                    <thead>
                                    <tr>
                                        <th width="5%">Id</th>
                                        <th width="5%">Ref ID</th>
                                        <th width="30%">Dua Title</th>
                                        <th width="55%">Tags</th>
                                        <th width="5%">Action</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach ($duaTags as $dua)
                                        <tr>
                                            <td>{{ $dua['id'] }}</td>
                                            <td>{{ $dua['reference_id'] }}</td>
                                            <td>{{ $dua['title'] }}</td>
                                            <td>
                                                @foreach ($dua['tagIds'] as $tagId)
                                                    <span class="font-weight-bold badge badge-info mt-2 mr-2" style="font-size: .9rem">
                                                {{ \App\Models\Tag::where('id',$tagId)->first()->name }}
                                            </span>
                                                @endforeach
                                            </td>
                                            <td>
                                           <div class="d-flex">
                                               <a class="btn btn-sm btn-dark text-white" href="{{route('admin.tagDetails.dua.edit',[$dua['id'],'dua'])}}">Edit</a>
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
        <script src="https://code.jquery.com/jquery-3.5.1.js"></script>
        <script src="https://cdn.datatables.net/1.13.2/js/jquery.dataTables.min.js"></script>
        <script>
            $(document).ready(function () {
                $('#duaTagTable').DataTable({
                    lengthMenu: [
                        [10, 15, 20, 50, 100, 150, 200, -1],
                        [10, 15, 20, 50, 100, 150, 200, "All"]],
                    pageLength: 10,
                    pagingType: "full_numbers",
                    processing: true,

                });
            });
        </script>
    @endpush
@endonce

