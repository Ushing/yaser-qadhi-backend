 <div class="modal-body">
        <div class="row">
            <table class="table table-bordered table-striped">
                <tbody>
                <tr>
                    <th>
                        ID
                    </th>
                    <td>
                        {{$tag->id}}
                    </td>
                </tr>

                <tr>
                    <th>
                        Name
                    </th>
                    <td>
                        {{$tag->name}}
                    </td>
                </tr>
                <tr>
                    <th>
                        Type
                    </th>
                    <td>
                        <span class="badge badge-info">{{ucwords($tag->type)}}</span>
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

