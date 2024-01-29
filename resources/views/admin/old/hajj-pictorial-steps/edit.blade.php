<form action="{{ route('admin.hajj_pictorial_steps.update', $hajjPictorialSteps->id) }}" method="post"
    enctype="multipart/form-data">
    @csrf
    @method('PUT')
    <div class="modal-body">
        <div class="row">
            <div class="col-sm-12 col-md-12">
                <div class="form-group">
                    <label for="Name">Step No. <span class="text-danger">*</span></label>
                    <input required type="text" name="step_no"
                        class="form-control {{ $errors->has('step_no') ? 'is-invalid' : '' }}"
                        value="{{ old('step_no', $hajjPictorialSteps->step_no) }}" placeholder="Enter Hajj Step No." />
                    @if ($errors->has('step_no'))
                        <div class="invalid-feedback">{{ $errors->first('step_no') }}</div>
                    @endif
                </div>
            </div>

            <div class="col-sm-12 col-md-12">
                <div class="form-group">
                    <label for="Name">Title <span class="text-danger">*</span></label>
                    <input required type="text" name="title"
                        class="form-control {{ $errors->has('title') ? 'is-invalid' : '' }}"
                        value="{{ old('title', $hajjPictorialSteps->title) }}"
                        placeholder="Enter Hajj Pictorial Step Title" />
                    @if ($errors->has('title'))
                        <div class="invalid-feedback">{{ $errors->first('title') }}</div>
                    @endif
                </div>
            </div>

            <div class="col-sm-12 col-md-6">
                <div class="form-group">
                    <label for="exampleSelectGender">Select Hajj Pictorial Image</label>
                    <input type="file" name="image"
                        class="form-control {{ $errors->has('image') ? 'is-invalid' : '' }}" value="{{ old('image') }}"
                        placeholder="Enter Image">

                    @if (isset($hajjPictorialSteps->image))
                        <img class="img-thumbnail" src="{{ public_path() . '/images/' . $hajjPictorialSteps->image }}"
                            width="200">
                    @else
                        No Thumbnail
                    @endif
                    <br><br>
                    @if ($errors->has('image'))
                        <div class="invalid-feedback">{{ $errors->first('image') }}</div>
                    @endif
                </div>

                <div class="form-group">
                    <label for="exampleSelectGender">Select Hajj Pictorial Video</label>
                    <input type="file" name="video"
                        class="form-control {{ $errors->has('video') ? 'is-invalid' : '' }}"
                        value="{{ old('video') }}" placeholder="Enter Video">

                    @if (isset($hajjPictorialSteps->video))
                        <img class="img-thumbnail" src="{{ public_path() . '/videos/' . $hajjPictorialSteps->video }}"
                            width="200">
                    @else
                        No Thumbnail
                    @endif
                    <br><br>
                    @if ($errors->has('video'))
                        <div class="invalid-feedback">{{ $errors->first('video') }}</div>
                    @endif
                </div>

                <div class="col-sm-12 col-md-12">
                    <div class="form-group">
                        <label for="Name">Description <span class="text-danger">*</span></label>
                        <input required type="text" name="description"
                            class="form-control {{ $errors->has('description') ? 'is-invalid' : '' }}"
                            value="{{ old('description', $hajjPictorialSteps->description) }}"
                            placeholder="Enter Hajj Pictorial Description" />
                        @if ($errors->has('description'))
                            <div class="invalid-feedback">{{ $errors->first('description') }}</div>
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
