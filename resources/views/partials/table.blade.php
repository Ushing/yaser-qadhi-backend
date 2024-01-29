@once
    @push('styles')
        <link rel="stylesheet" href="{{asset('admin/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css')}}">
        <link rel="stylesheet" href="{{asset('admin/plugins/datatables-responsive/css/responsive.bootstrap4.min.css')}}">
        <link rel="stylesheet" href="{{asset('admin/plugins/datatables-buttons/css/buttons.bootstrap4.min.css')}}">
        <style>
            .dataTables_wrapper .dataTables_processing {
                position: absolute;
                top: 5% !important;
                background-color: transparent;
                color: #97049d;
            }
            table.table-bordered > tbody > tr > td {
                border: 1px solid #dee2e6;
            }

        </style>
    @endpush
@endonce

<div class="table-responsive">
    <table class="table table-bordered table-hover" id="dataTable">
        <thead>
        <tr>
            @foreach ($tableHeads as $key => $title)
                <th>{{$title}}</th>
            @endforeach
        </tr>
        </thead>
        <tbody>
        </tbody>
    </table>
</div>

@once
    @push('scripts')
        <script src="{{asset('admin/plugins/jquery/jquery.min.js')}}"></script>
        <script src="{{asset('admin/plugins/bootstrap/js/bootstrap.bundle.min.js')}}"></script>
        <script src="{{asset('admin/plugins/datatables/jquery.dataTables.min.js')}}"></script>
        <script src="{{asset('admin/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js')}}"></script>
        <script src="{{asset('admin/plugins/datatables-responsive/js/dataTables.responsive.min.js')}}"></script>
        <script src="{{asset('admin/plugins/datatables-responsive/js/responsive.bootstrap4.min.js')}}"></script>
        <script src="{{asset('admin/plugins/datatables-buttons/js/dataTables.buttons.min.js')}}"></script>
        <script src="{{asset('admin/plugins/datatables-buttons/js/buttons.bootstrap4.min.js')}}"></script>
        <script src="{{asset('admin/plugins/jszip/jszip.min.js')}}"></script>
        <script src="{{asset('admin/plugins/pdfmake/pdfmake.min.js')}}"></script>
        <script src="{{asset('admin/plugins/pdfmake/vfs_fonts.js')}}"></script>
        <script src="{{asset('admin/plugins/datatables-buttons/js/buttons.html5.min.js')}}"></script>
        <script src="{{asset('admin/plugins/datatables-buttons/js/buttons.print.min.js')}}"></script>
        <script src="{{asset('admin/plugins/datatables-buttons/js/buttons.colVis.min.js')}}"></script>
        <script src="{{asset('admin/dist/js/adminlte.min.js')}}"></script>

        <script type="text/javascript">
            $(document).ready(function () {
                $(function () {
                    var columns = eval('{!! json_encode($columns) !!}');
                    var dataTable = $('#dataTable').DataTable({
                        "responsive": true, "lengthChange": true, "autoWidth": false,
                        "buttons": ["copy", "csv", "excel", "pdf", "print", "colvis"],
                        dom: "<'row'" +
                            "<'col-sm-3'l>" +
                            "<'col-sm-6 text-center'B>" +
                            "<'col-sm-3'f>>tipr",
                        language: {
                            search: "",
                            searchPlaceholder: "Search",
                            processing: '<i class="ace-icon fa fa-spinner fa-spin" style="font-size:35px;margin-top:70px;"></i>'
                        },
                        lengthMenu: [
                            [10, 15, 20, 50, 100, 150, 200, -1],
                            [10, 15, 20, 50, 100, 150, 200, "All"]
                        ],
                        pageLength: 10,
                        pagingType: "full_numbers",
                        order: [
                            [0, "desc"]
                        ],
                        processing: true,
                        serverSide: true,
                        fnRowCallback: function (nRow, aData, iDisplayIndex) {
                            $("td:first", nRow).html(iDisplayIndex + 1);
                            return nRow;
                        },
                        ajax: {
                            url: '{{ url($dataUrl) }}',
                            data: function (e) {
                                var fields = $('#searchForm').serializeArray();
                                $.each(fields, function (i, field) {
                                    e[field.name] = field.value;
                                });
                            }
                        },
                        columns: columns,
                    }).buttons().container().appendTo('#dataTable_wrapper .col-md-6:eq(0)');

                    $('#searchForm').submit(function (e) {
                        e.preventDefault();
                        dataTable.draw();
                    });

                    $('.reset').click(function (e) {
                        e.preventDefault();
                        $('#searchForm').trigger("reset");
                        dataTable.draw();
                    });

                });
            });
        </script>
    @endpush
@endonce
