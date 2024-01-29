<form action="{{ route('admin.hajj_sub_lists.store') }}" method="post" enctype="multipart/form-data">
    @csrf
    <div class="modal-body">
        <div class="row">
            <div class="col-sm-12 col-md-12">
                <div class="form-group">
                    <label for="Name">Title <span class="text-danger">*</span></label>
                    <input required type="text" name="title"
                           class="form-control {{ $errors->has('title') ? 'is-invalid' : '' }}"
                           value="{{ old('title') }}" placeholder="Enter Hajj Sub List"/>
                    @if ($errors->has('title'))
                        <div class="invalid-feedback">{{ $errors->first('title') }}</div>
                    @endif
                </div>
            </div>


            <div class="col-sm-12 col-md-12">
                <div class="form-group">
                    <label for="exampleSelectGender">Select Hajj Check Title <span class="text-danger">*</span></label>
                    <select class="select2 form-control {{ $errors->has('checklist_id') ? 'is-invalid' : '' }}"
                            name="checklist_id" id="checklist_id" style="width: 100%">
                        <option value="">Choose One</option>
                        @foreach($hajjCheckLists as $hajjCheck)
                            <option value="{{$hajjCheck->id}}">{{$hajjCheck->title}}</option>
                        @endforeach
                    </select>
                    @if ($errors->has('checklist_id'))
                        <div class="invalid-feedback">{{ $errors->first('checklist_id') }}</div>
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
