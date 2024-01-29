<form action="{{ route('admin.coupon.store') }}" method="post" enctype="multipart/form-data">
    @csrf
    <div class="modal-body">
        <div class="row">

{{--            <div class="col-sm-12 col-md-12">--}}
{{--                <div class="form-group">--}}
{{--                    <label for="Name">Code <span class="text-danger">*</span></label>--}}
{{--                    <input required type="text" name="code" class="form-control {{ $errors->has('code') ? 'is-invalid' : '' }}"--}}
{{--                           value="{{ old('code') }}" placeholder="Enter Coupon Code"/>--}}
{{--                    @if ($errors->has('code'))--}}
{{--                        <div class="invalid-feedback">{{ $errors->first('code') }}</div>--}}
{{--                    @endif--}}
{{--                </div>--}}
{{--            </div>--}}

            <div class="col-sm-12 col-md-12">
                <div class="form-group">
                    <label for="Name">Validate Date <span class="text-danger">*</span></label>
                    <input required type="date" name="validate_date" class="form-control {{ $errors->has('validate_date') ? 'is-invalid' : '' }}"
                           value="{{ old('validate_date') }}" placeholder="Enter Date"/>
                    @if ($errors->has('validate_date'))
                        <div class="invalid-feedback">{{ $errors->first('validate_date') }}</div>
                    @endif
                </div>
            </div>



            <div class="col-sm-12 col-md-12">
                <div class="form-group">
                    <label for="exampleSelectGender">Select Status <span class="text-danger">*</span></label>
                    <select class="form-control {{ $errors->has('type') ? 'is-invalid' : '' }}"
                            name="status" id="status" style="width: 100%">
                        <option value="">Choose Status</option>
                        <option value="true">True</option>
                        <option value="false">False</option>

                    </select>
                    @if ($errors->has('type'))
                        <div class="invalid-feedback">{{ $errors->first('type') }}</div>
                    @endif
                </div>
            </div>


        </div>
    </div>

    <!-- /.card-body -->
    <div class="card-footer text-center">
        <button type="submit" class="btn btn-success bg-gradient-success mr-2">Submit</button>
        <button type="button" class="btn btn-danger bg-gradient-danger mr-2" data-bs-dismiss="modal">Close</button>
    </div>
    <br>
</form>
