<form action="{{ route('admin.hajj_pictorial_steps.store') }}" method="post" enctype="multipart/form-data">
    @csrf
    <div class="modal-body">
        <div class="row">
            <div class="col-sm-12 col-md-12">
                <div class="form-group">
                    <label for="Name">Step No. <span class="text-danger">*</span></label>
                    <input required type="text" name="step_no"
                        class="form-control {{ $errors->has('step_no') ? 'is-invalid' : '' }}"
                        value="{{ old('step_no') }}" placeholder="Enter Step No." />
                    @if ($errors->has('step_no'))
                        <div class="invalid-feedback">{{ $errors->first('step_no') }}</div>
                    @endif
                </div>
            </div>

            <div class="col-sm-12 col-md-12">
                <div class="form-group">
                    <label for="Name">Title <span class="text-danger">*</span></label>
                    <input required type="text" name="title"
                        class="form-control {{ $errors->has('title') ? 'is-invalid' : '' }}" value="{{ old('title') }}"
                        placeholder="Enter Check List" />
                    @if ($errors->has('title'))
                        <div class="invalid-feedback">{{ $errors->first('title') }}</div>
                    @endif
                </div>
            </div>

            <div class="col-sm-12 col-md-12">
                <div class="form-group">
                    <label for="exampleSelectGender">Select Image</label>
                    <input type="file" name="image"
                           class="form-control {{ $errors->has('image') ? 'is-invalid' : '' }}"
                           value="{{ old('image') }}"
                           placeholder="Enter Image">
                    @if ($errors->has('image'))
                        <div class="invalid-feedback">{{ $errors->first('image') }}</div>
                    @endif
                </div>
            </div>

            <div class="col-sm-12 col-md-12">
                <div class="form-group">
                    <label for="exampleSelectGender">Select Video</label>
                    <input type="file" name="video"
                           class="form-control {{ $errors->has('video') ? 'is-invalid' : '' }}"
                           value="{{ old('video') }}"
                           placeholder="Enter Video">
                    @if ($errors->has('video'))
                        <div class="invalid-feedback">{{ $errors->first('video') }}</div>
                    @endif
                </div>
            </div>

            <div class="col-sm-12 col-md-12">
                <div class="form-group">
                    <label for="Name">Description <span class="text-danger">*</span></label>
                    <input required type="text" name="description"
                        class="form-control {{ $errors->has('description') ? 'is-invalid' : '' }}"
                        value="{{ old('description') }}" placeholder="Description" />
                    @if ($errors->has('description'))
                        <div class="invalid-feedback">{{ $errors->first('description') }}</div>
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
