<div class="modal-body">
        <div class="row">
            <table class="table table-bordered table-striped">
                <tbody>
                <tr>
                    <th>
                        ID
                    </th>
                    <td>
                        {{$duaSubCategory->id}}
                    </td>
                </tr>

                <tr>
                    <th>
                        Name
                    </th>
                    <td>
                        {{$duaSubCategory->name}}
                    </td>
                </tr>

                <tr>
                    <th>
                        Dua Category
                    </th>
                    <td>
                        {{$duaSubCategory->duaCategory->name ??''}}
                    </td>
                </tr>

                <tr>
                    <th>
                        Status
                    </th>
                    <td>
                        {!! setStatus($duaSubCategory->status) !!}
                    </td>
                </tr>

                </tbody>
            </table>
        </div>
    </div>
    <!-- /.card-body -->
    <div class="card-footer text-center">
        <button type="button" class="btn btn-danger bg-gradient-danger mr-2" data-bs-dismiss="modal">Close</button>
    </div>
    <br>

