<style>

</style>

<form action="{{ route('admin.surahReciteFiles.store') }}" method="post" enctype="multipart/form-data">
    @csrf
    <div class="modal-body">
        <div class="row">

            <span class="h5 text-black text-center font-weight-bold">Surah Title:
                  <span class="text-sm text-info">{{\App\Models\SurahRecitation::where('id',$surahId)->first()->title}}</span>
            </span>

            <hr style="width: 96%;   border-top: 4px dashed darkblue;"/>

            <div class="col-sm-12 col-md-12">
                <div class="form-group">
                    <label for="exampleSelectGender">Select Language <span class="text-danger">*</span></label>
                    <select class="select2 my-select2 form-control {{ $errors->has('recite_language_id') ? 'is-invalid' : '' }}"
                            name="recite_language_id" id="recite_language_id" style="width: 100%" >
                        <option value="">Choose Language</option>
                        @foreach($languages as $language)
                            <option value="{{$language->id}}">{{$language->title}}</option>
                        @endforeach
                    </select>
                    @if ($errors->has('recite_language_id'))
                        <div class="invalid-feedback">{{ $errors->first('recite_language_id') }}</div>
                    @endif
                </div>
            </div>

            <div class="col-sm-12 col-md-12">
                <div class="form-group">
                    <label for="Name">Translation {{-- <span class="text-danger">*</span> --}}</label>
                    <textarea type="text" name="translation"
                              class="summernote form-control {{ $errors->has('translation') ? 'is-invalid' : '' }}"
                              placeholder="Enter Dua Translation">{{ old('translation') }}</textarea>
                    @if ($errors->has('translation'))
                        <div class="invalid-feedback">{{ $errors->first('translation') }}</div>
                    @endif
                </div>
            </div>

            <div class="col-sm-12 col-md-12">
                <div class="form-group">
                    <label for="Name">Transliteration{{-- <span
                                                        class="text-danger">*</span> --}}</label>
                    <textarea type="text" name="transliteration"
                              class="summernote form-control {{ $errors->has('transliteration') ? 'is-invalid' : '' }}"
                              placeholder="Enter Dua Translation">{{ old('transliteration') }}</textarea>
                    @if ($errors->has('transliteration'))
                        <div class="invalid-feedback">{{ $errors->first('transliteration') }}
                        </div>
                    @endif
                </div>
            </div>


            <div class="col-sm-12 col-md-12">
                <div class="form-group">
                    <label for="exampleSelectGender">Select Sub Title File(SRT) <span
                            class="text-danger">*</span></label>
                    <input required type="file" name="sub_title_file"
                           class="form-control {{ $errors->has('sub_title_file') ? 'is-invalid' : '' }}"
                           value="{{ old('sub_title_file') }}"
                           placeholder="Enter Image" accept=".srt">
                    @if ($errors->has('sub_title_file'))
                        <div class="invalid-feedback">{{ $errors->first('sub_title_file') }}</div>
                    @endif
                </div>
            </div>

            <input type="text" name="surah_recitation_id"
                   class="form-control d-none {{ $errors->has('surah_recitation_id') ? 'is-invalid' : '' }}"
                   value="{{$surahId}}"
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


@if (session('recite_validation_error'))
    <script type="text/javascript">
        $('#commonModal').modal('show');
    </script>
@endif

<script>
    $(document).ready(function(){
        $.fn.modal.Constructor.prototype.enforceFocus = function () {};

        $('.select2').each(function() {
            $(this).select2({ dropdownParent: $(this).parent()});
        })
    });

</script>

<script src="{{asset('admin/plugins/summernote/summernote-bs4.min.js')}}"></script>
<script>
    $(document).ready(function () {
        $(function () {
            $('.summernote').summernote()
        })
    });
</script>
