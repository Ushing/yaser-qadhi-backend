<form action="{{ route('admin.subscription.update',$subscription->id) }}" method="post" enctype="multipart/form-data">
    @csrf
    @method('PUT')
    <div class="modal-body">
        <div class="row">
            <div class="col-sm-12 col-md-6">
                <div class="form-group">
                    <label for="Name">Plan Name <span class="text-danger">*</span></label>
                    <input required type="text" name="plan_name" class="form-control {{ $errors->has('plan_name') ? 'is-invalid' : '' }}" value="{{ old('plan_name',$subscription->plan_name) }}" placeholder="Enter Dua Category Name" />
                    @if ($errors->has('plan_name'))
                        <div class="invalid-feedback">{{ $errors->first('plan_name') }}</div>
                    @endif
                </div>
            </div>

            <div class="col-sm-12 col-md-6">
                <div class="form-group">
                    <label for="Name">Plan Cost <span class="text-danger">*</span></label>
                    <input required type="text" name="plan_cost" class="form-control {{ $errors->has('plan_cost') ? 'is-invalid' : '' }}" value="{{ old('plan_cost',$subscription->plan_cost) }}" placeholder="Enter Dua Category Name" />
                    @if ($errors->has('plan_cost'))
                        <div class="invalid-feedback">{{ $errors->first('plan_cost') }}</div>
                    @endif
                </div>
            </div>

            <div class="col-sm-12 col-md-6">
                <div class="form-group">
                    <label for="exampleSelectGender">Select Duration <span class="text-danger">*</span></label>
                    <select class="form-control {{ $errors->has('duration') ? 'is-invalid' : '' }}"
                            name="duration" id="duration" style="width: 100%">
                        <option value="">Choose Duration</option>
                        <option value="yearly" {{$subscription->duration == 'yearly' ? 'selected' : ''}}>Yearly</option>
                        <option value="half-yearly" {{$subscription->duration == 'half-yearly' ? 'selected' : ''}}>Half Yearly</option>
                        <option value="monthly" {{$subscription->duration == 'monthly' ? 'selected' : ''}}>Monthly</option>
                    </select>
                    @if ($errors->has('duration'))
                        <div class="invalid-feedback">{{ $errors->first('duration') }}</div>
                    @endif
                </div>
            </div>

            <div class="col-sm-12 col-md-6">
                <div class="form-group">
                    <p class="font-weight-bold">Status <span class="text-danger">*</span></p>

                    <div class="d-flex justify-content-between w-50">
                        <div>
                            <input type="radio" id="active" name="status" value="1" {{($subscription->status== '1') ? 'checked' : ''}} >
                            <label for="active">Active</label><br>
                        </div>
                        <div>
                            <input type="radio" id="inactive" name="status" value="0" {{($subscription->status== '0') ? 'checked' : ''}}>
                            <label for="inactive">Inactive</label><br>
                        </div>
                    </div>

                    @if ($errors->has('status'))
                        <div class="invalid-feedback">{{ $errors->first('status') }}</div>
                    @endif
                </div>
            </div>

            <div class="col-sm-12 col-md-12">
                <div class="form-group">
                    <label for="Description">Description <span class="text-danger">*</span></label>
                    <textarea type="text" name="plan_description"
                              class="form-control {{ $errors->has('plan_description') ? 'is-invalid' : '' }}"
                              placeholder="Enter Plan Description">{{ old('plan_description',$subscription->plan_description) }}</textarea>
                    @if ($errors->has('plan_description'))
                        <div
                            class="invalid-feedback">{{ $errors->first('plan_description') }}</div>
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
