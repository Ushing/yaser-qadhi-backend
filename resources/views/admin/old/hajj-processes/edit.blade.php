<form action="{{ route('admin.hajj_processes.update', $hajjProcess->id) }}" method="post" enctype="multipart/form-data">
    @csrf
    @method('PUT')
    <div class="modal-body">
        <div class="row">
            <div class="col-sm-12 col-md-12">
                <div class="form-group">
                    <label for="Name">Process Number <span class="text-danger">*</span></label>
                    <input required type="text" name="process_no"
                        class="form-control {{ $errors->has('process_no') ? 'is-invalid' : '' }}"
                        value="{{ old('process_no', $hajjProcess->process_no) }}"
                        placeholder="Enter Hajj Process Number" />
                    @if ($errors->has('process_no'))
                        <div class="invalid-feedback">{{ $errors->first('process_no') }}</div>
                    @endif
                </div>
            </div>

            <div class="col-sm-12 col-md-12">
                <div class="form-group">
                    <label for="Name">Title <span class="text-danger">*</span></label>
                    <input required type="text" name="title"
                        class="form-control {{ $errors->has('title') ? 'is-invalid' : '' }}"
                        value="{{ old('title', $hajjProcess->title) }}" placeholder="Enter Hajj Process Title" />
                    @if ($errors->has('title'))
                        <div class="invalid-feedback">{{ $errors->first('title') }}</div>
                    @endif
                </div>
            </div>

            <div class="col-sm-12 col-md-6">
                <div class="form-group">
                    <label for="exampleSelectGender">Select Hajj Process Image <span
                            class="text-danger">*</span></label>
                    <input required type="file" name="image"
                        class="form-control {{ $errors->has('image') ? 'is-invalid' : '' }}" value="{{ old('image') }}"
                        placeholder="Enter Image">

                    @if (isset($hajjProcess->image))
                        <img class="img-thumbnail" src="{{ public_path() . '/images/'. $hajjProcess->image }}"
                            width="200">
                    @else
                        No Thumbnail
                    @endif
                    <br><br>
                    @if ($errors->has('image'))
                        <div class="invalid-feedback">{{ $errors->first('image') }}</div>
                    @endif
                </div>

                <div class="col-sm-12 col-md-12">
                    <div class="form-group">
                        <label for="Name">Description <span class="text-danger">*</span></label>
                        <input required type="text" name="description"
                            class="form-control {{ $errors->has('description') ? 'is-invalid' : '' }}"
                            value="{{ old('description', $hajjProcess->description) }}"
                            placeholder="Enter Hajj Process Description" />
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
