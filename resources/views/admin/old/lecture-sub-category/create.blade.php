<form action="{{ route('admin.lecture-sub-category.store') }}" method="post" enctype="multipart/form-data">
    @csrf
    <div class="modal-body">
        <div class="row">
            <div class="col-sm-12 col-md-6">
                <div class="form-group">
                    <label for="Name">Name <span class="text-danger">*</span></label>
                    <input required type="text" name="name" class="form-control {{ $errors->has('name') ? 'is-invalid' : '' }}"
                           value="{{ old('name') }}" placeholder="Enter Lecture Sub Category Name"/>
                    @if ($errors->has('name'))
                        <div class="invalid-feedback">{{ $errors->first('name') }}</div>
                    @endif
                </div>
            </div>

            <div class="col-sm-12 col-md-6">
                <div class="form-group">
                    <label for="exampleSelectGender">Select Lecture Category <span class="text-danger">*</span></label>
                    <select class="select2 form-control {{ $errors->has('lecture_category_id') ? 'is-invalid' : '' }}"
                            name="lecture_category_id" id="lecture_category_id" style="width: 100%">
                        <option value="">Choose Lecture Category</option>
                        @foreach($lectureCategories as $lectureCategory)
                            <option value="{{$lectureCategory->id}}">{{$lectureCategory->name}}</option>
                        @endforeach
                    </select>
                    @if ($errors->has('lecture_category_id'))
                        <div class="invalid-feedback">{{ $errors->first('lecture_category_id') }}</div>
                    @endif
                </div>
            </div>

            <div class="col-sm-12 col-md-6">
                <div class="form-group">
                    <p class="font-weight-bold">Status <span class="text-danger">*</span></p>
                    <div class="d-flex justify-content-between w-50">
                        <div>
                            <input type="radio" id="active" name="status" value="1">
                            <label for="active">Active</label><br>
                        </div>

                        <div>
                            <input type="radio" id="inactive" name="status" value="0">
                            <label for="inactive">Inactive</label><br>
                        </div>
                    </div>

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
