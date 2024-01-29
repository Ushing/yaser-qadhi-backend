 <div class="modal-body">
        <div class="row">
            <table class="table table-bordered table-striped">
                <tbody>
                <tr>
                    <th>
                        ID
                    </th>
                    <td>
                        {{$subscription->id}}
                    </td>
                </tr>

                <tr>
                    <th>
                       Plan Name
                    </th>
                    <td>
                        {{$subscription->plan_name}}
                    </td>
                </tr>

                <tr>
                    <th>
                        Plan Cost
                    </th>
                    <td>
                        {{$subscription->plan_cost}}
                    </td>
                </tr>

                <tr>
                    <th>
                        Duration
                    </th>
                    <td>
                        {{$subscription->duration}}
                    </td>
                </tr>

                <tr>
                    <th>
                        Description
                    </th>
                    <td>
                        {{$subscription->plan_description}}
                    </td>
                </tr>


                <tr>
                    <th>
                        Status
                    </th>
                    <td>
                        {!! setStatus($subscription->status) !!}
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

