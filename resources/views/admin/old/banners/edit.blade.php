<form action="{{ route('admin.banner.update',$banner->id) }}" method="post" enctype="multipart/form-data">
    @csrf
    @method('PUT')
    <div class="modal-body">
        <div class="row">
            <div class="col-sm-12 col-md-6">
                <div class="form-group">
                    <label for="Name">Title <span class="text-danger">*</span></label>
                    <input required type="text" name="title" class="form-control {{ $errors->has('title') ? 'is-invalid' : '' }}" value="{{ old('title',$banner->title) }}" placeholder="Enter Dua Category Name" />
                    @if ($errors->has('title'))
                        <div class="invalid-feedback">{{ $errors->first('title') }}</div>
                    @endif
                </div>
            </div>

            <div class="col-sm-12 col-md-6">
                <div class="form-group">
                    <p class="font-weight-bold">Status <span class="text-danger">*</span></p>

                    <div class="d-flex justify-content-between w-50">
                        <div>
                            <input type="radio" id="active" name="status" value="1" {{($banner->status== '1') ? 'checked' : ''}} >
                            <label for="active">Active</label><br>
                        </div>
                        <div>
                            <input type="radio" id="inactive" name="status" value="0" {{($banner->status== '0') ? 'checked' : ''}}>
                            <label for="inactive">Inactive</label><br>
                        </div>
                    </div>

                    @if ($errors->has('status'))
                        <div class="invalid-feedback">{{ $errors->first('status') }}</div>
                    @endif
                </div>
            </div>


            <div class="col-sm-12 col-md-6">
                <div class="form-group">
                    <label for="exampleSelectGender">Select Banner <span
                            class="text-danger">*</span></label>
                    <input required type="file" name="image"
                           class="form-control {{ $errors->has('image') ? 'is-invalid' : '' }}"
                           value="{{ old('image') }}"
                           placeholder="Enter Image">

                    @if(isset( $banner->image))
                        <img class="img-thumbnail" src="{{ Storage::disk('public')->url('banners/'.$banner->image)}}"
                             width="200">
                    @else
                        No Thumbnail
                    @endif
                    <br><br>
                    @if ($errors->has('image'))
                        <div class="invalid-feedback">{{ $errors->first('image') }}</div>
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
