<form action="{{ route('admin.tag.update',$tag->id) }}" method="post" enctype="multipart/form-data">
    @csrf
    @method('PUT')
    <div class="modal-body">
        <div class="row">
            <div class="col-sm-12 col-md-12">
                <div class="form-group">
                    <label for="Name">Name <span class="text-danger">*</span></label>
                    <input required type="text" name="name" class="form-control {{ $errors->has('name') ? 'is-invalid' : '' }}" value="{{ old('name',$tag->name) }}" placeholder="Enter Dua Category Name" />
                    @if ($errors->has('name'))
                        <div class="invalid-feedback">{{ $errors->first('name') }}</div>
                    @endif
                </div>
            </div>

            <div class="col-sm-12 col-md-12">
                <div class="form-group">
                    <label for="exampleSelectGender">Select Type <span class="text-danger">*</span></label>
                    <select class="form-control {{ $errors->has('type') ? 'is-invalid' : '' }}"
                            name="type" id="type" style="width: 100%">
                        <option value="">Choose Type</option>
                        <option value="search" {{$tag->type == 'search' ? 'selected' : ''}}>Search</option>
                        <option value="feeling" {{$tag->type == 'feeling' ? 'selected' : ''}}>Feeling</option>
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
        <button type="submit" class="btn btn-success bg-gradient-success mr-2">Update</button>
        <button type="button" class="btn btn-danger bg-gradient-danger mr-2" data-bs-dismiss="modal">Close</button>
    </div>
    <br>
</form>
