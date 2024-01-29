<style>

</style>

<form action="{{ route('admin.tagDetails.store') }}" method="post" enctype="multipart/form-data">
    @csrf
    <div class="modal-body">
        <div class="row">
            <div class="col-sm-12 col-md-12">
                <div class="form-group">
                    <label for="exampleSelectGender">Select Tags <span class="text-danger">*</span></label>
                    <select class="select2 my-select2 form-control {{ $errors->has('tag_id') ? 'is-invalid' : '' }}"
                            name="tag_ids[]" id="tag_id" style="width: 100%" multiple>
                        <option value="">Choose Tags</option>
                        @foreach($tags as $tag)
                            <option value="{{$tag->id}}">{{$tag->name}}</option>
                        @endforeach
                    </select>
                    @if ($errors->has('tag_id'))
                        <div class="invalid-feedback">{{ $errors->first('tag_id') }}</div>
                    @endif
                </div>
            </div>

            <input type="text" name="content_type"
                   class="form-control d-none {{ $errors->has('content_type') ? 'is-invalid' : '' }}"
                   value="{{$contentType}}"
                   placeholder="Enter content type"/>

            <input type="text" name="content_id"
                   class="form-control d-none {{ $errors->has('content_id') ? 'is-invalid' : '' }}"
                   value="{{$content}}"
                   placeholder="Enter content id"/>

        </div>
    </div>
    <!-- /.card-body -->

    <div class="card-footer text-center">
        <button type="submit" class="btn btn-success bg-gradient-success mr-2">Submit</button>
        <button type="button" class="btn btn-danger bg-gradient-danger mr-2" data-bs-dismiss="modal">Close</button>
    </div>
    <br>
</form>

<script>
    $(document).ready(function(){
        $.fn.modal.Constructor.prototype.enforceFocus = function () {};

        $('.select2').each(function() {
            $(this).select2({ dropdownParent: $(this).parent()});
        })
    });




</script>
