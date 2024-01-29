<div class="modal-body">
        <div class="row">
            <table class="table table-bordered table-striped">
                <tbody>
                <tr>
                    <th>
                        ID
                    </th>
                    <td>
                        {{$lectureSubCategory->id}}
                    </td>
                </tr>

                <tr>
                    <th>
                        Name
                    </th>
                    <td>
                        {{$lectureSubCategory->name}}
                    </td>
                </tr>

                <tr>
                    <th>
                        Lecture Category
                    </th>
                    <td>
                        {{$lectureSubCategory->lectureCategory->name ??''}}
                    </td>
                </tr>

                <tr>
                    <th>
                        Status
                    </th>
                    <td>
                        {!! setStatus($lectureSubCategory->status) !!}
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

