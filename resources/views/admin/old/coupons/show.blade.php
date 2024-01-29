 <div class="modal-body">
        <div class="row">
            <table class="table table-bordered table-striped">
                <tbody>
                <tr>
                    <th>
                        ID
                    </th>
                    <td>
                        {{$coupon->id}}
                    </td>
                </tr>

                <tr>
                    <th>
                        Code
                    </th>
                    <td>
                        {{$coupon->code}}
                    </td>
                </tr>
                <tr>
                    <th>
                        Date
                    </th>
                    <td>
                        <span class="badge badge-info">{{$coupon->validate_date}}</span>
                    </td>
                </tr>
                <tr>
                    <th>
                        Status
                    </th>
                    <td>
                        <span class="badge badge-info">{{ucwords($coupon->status)}}</span>
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

