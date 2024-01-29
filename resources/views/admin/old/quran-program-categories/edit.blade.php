<form action="{{ route('admin.quran_program_category.update',$quranProgramCategory->id) }}" method="post" enctype="multipart/form-data">
    @csrf
    @method('PUT')
    <div class="modal-body">
        <div class="row">
            <div class="col-sm-12 col-md-12">
                <div class="form-group">
                    <label for="Name">Title <span class="text-danger">*</span></label>
                    <input required type="text" name="title" class="form-control {{ $errors->has('title') ? 'is-invalid' : '' }}" value="{{ old('title',$quranProgramCategory->title) }}" placeholder="Enter Dua Category Name" />
                    @if ($errors->has('title'))
                        <div class="invalid-feedback">{{ $errors->first('title') }}</div>
                    @endif
                </div>
            </div>

            <div class="col-sm-12 col-md-12">
                <div class="form-group">
                    <label for="exampleSelectGender">Select Status <span class="text-danger">*</span></label>
                    <select class="form-control {{ $errors->has('type') ? 'is-invalid' : '' }}"
                            name="status" id="status" style="width: 100%">
                        <option value="">Choose Status</option>
                        <option value="1" {{$quranProgramCategory->status == '1' ? 'selected' : ''}}>Active</option>
                        <option value="0" {{$quranProgramCategory->status == '0' ? 'selected' : ''}}>InActive</option>
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
        <button type="submit" class="btn btn-success bg-gradient-success mr-2">Update</button>
        <button type="button" class="btn btn-danger bg-gradient-danger mr-2" data-bs-dismiss="modal">Close</button>
    </div>
    <br>
</form>
