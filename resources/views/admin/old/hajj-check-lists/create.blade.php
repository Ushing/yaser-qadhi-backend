<form action="{{ route('admin.hajj_check_lists.store') }}" method="post" enctype="multipart/form-data">
    @csrf
    <div class="modal-body">
        <div class="row">
            <div class="col-sm-12 col-md-12">
                <div class="form-group">
                    <label for="Name">Title <span class="text-danger">*</span></label>
                    <input required type="text" name="title"
                           class="form-control {{ $errors->has('title') ? 'is-invalid' : '' }}"
                           value="{{ old('title') }}" placeholder="Enter Check List"/>
                    @if ($errors->has('title'))
                        <div class="invalid-feedback">{{ $errors->first('title') }}</div>
                    @endif
                </div>
            </div>


            <div class="col-sm-12 col-md-12">
                <div class="form-group">
                    <label for="exampleSelectGender">Status <span class="text-danger">*</span></label>
                    <select class="form-control {{ $errors->has('status') ? 'is-invalid' : '' }}"
                            name="status" id="status" style="width: 100%">
                        <option value="true">True</option>
                        <option value="false">False</option>

                    </select>
                    @if ($errors->has('status'))
                        <div class="invalid-feedback">{{ $errors->first('status') }}</div>
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
