<div class="modal fade" id="commonModal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Default Modal</h4>
                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="body">

            </div>

{{--            <div class="modal-footer justify-content-between">--}}
{{--                <button type="button" class="btn btn-default" data-bs-dismiss="modal">Close</button>--}}
{{--                <button type="button" class="btn btn-primary">Save changes</button>--}}
{{--           --}}
{{--                <br><br>--}}
{{--            </div>--}}

        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>


@push('scripts')
<script>
    $(document).on('click', 'a[data-ajax-popup="true"], button[data-ajax-popup="true"], div[data-ajax-popup="true"]', function() {

        var title1 = $(this).data("title");
        var title2 = $(this).data("bs-original-title");
        var title = (title1 != undefined) ? title1 : title2;
        var size = ($(this).data('size') == '') ? 'md' : $(this).data('size');
        var url = $(this).data('url');
        $("#commonModal .modal-title").html(title);
        $("#commonModal .modal-dialog").addClass('modal-' + size);
        $.ajax({
            url: url,
            success: function(data) {
                $('#commonModal .body').html(data);
                $("#commonModal").modal('show');
                // daterange_set();
                common_bind("#commonModal");

            },
            error: function(data) {
                data = data.responseJSON;
                show_toastr('Error', data.error, 'error')
            }
        });

    });

    function common_bind() {

    }
</script>
@endpush
