@if($hasPlugin('datePicker'))
    @push('styles')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    @endpush

    @push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            flatpickr('.date-picker', {
                dateFormat: "Y-m-d",
                allowInput: true
            });
        })
    </script>
    @endpush
@endif

@if($hasPlugin('contentEditor'))
    @push('styles')
        <link href="{{ asset('assets/css/summernote/summernote-lite.min.css') }}" rel="stylesheet">
    @endpush

    @push('scripts')
        <script src="{{ asset('assets/js/summernote/summernote-lite.min.js') }}"></script>
        <script>
            $(function(){
                $('#answer').summernote({
                    height: 400,
                    toolbar: [
                        ['style', ['style']],
                        ['font', ['bold', 'italic', 'underline', 'clear']],
                        ['fontname', ['fontname']],
                        ['color', ['color']],
                        ['para', ['ul', 'ol', 'paragraph']],
                        ['height', ['height']],
                        ['table', ['table']],
                        ['insert', ['link', 'hr']],
                        ['view', ['fullscreen', 'codeview', 'help']],
                    ],
                });
            })
        </script>
    @endpush
@endif

@if($hasPlugin('dataTable'))
    @push('styles')
        <link rel="stylesheet" href="{{ asset('assets/libs/datatables.net-bs4/css/dataTables.bootstrap4.min.css') }}" type="text/css" />
        <link rel="stylesheet" href="{{ asset('assets/libs/datatables.net-buttons-bs4/css/buttons.bootstrap4.min.css') }}" type="text/css" />
        <link rel="stylesheet" href="{{ asset('assets/libs/datatables.net-responsive-bs4/css/responsive.bootstrap4.min.css') }}" type="text/css" />
    @endpush

    @push('scripts')
        <!-- Responsive examples -->
        <script src="{{ asset('assets/libs/datatables.net-responsive/js/dataTables.responsive.min.js') }}"></script>
        <script src="{{ asset('assets/libs/datatables.net-responsive-bs4/js/responsive.bootstrap4.min.js') }}"></script>
        <!-- Datatable init js -->
        <script src="{{ asset('assets/js/pages/datatables.init.js') }}"></script>
        <!-- Required datatable js -->
        <script src="{{ asset('assets/libs/datatables.net/js/jquery.dataTables.min.js') }}"></script>
        <script src="{{ asset('assets/libs/datatables.net-bs4/js/dataTables.bootstrap4.min.js') }}"></script>
        <!-- Buttons examples -->
        <script src="{{ asset('assets/libs/datatables.net-buttons/js/dataTables.buttons.min.js') }}"></script>
        <script src="{{ asset('assets/libs/datatables.net-buttons-bs4/js/buttons.bootstrap4.min.js') }}"></script>
    @endpush
@endif

@if($hasPlugin('jQueryValidate'))
    @push('scripts')
        <script src="{{ asset('assets/js/jquery.validate.min.js') }}"></script>
    @endpush
@endif

@if($hasPlugin('chosen'))
    @push('styles')
        <link rel="stylesheet" href="{{ asset('assets/css/chosen.min.css') }}" />
    @endpush
    @push('scripts')
        <script src="{{ asset('assets/js/chosen.jquery.min.js') }}"></script>
    @endpush
@endif

@if($hasPlugin('update-status'))
    @push('scripts')
    <script>
        $(function() {
            $('.update-status').change(function() {
                var status = $(this).prop('checked') ? 'Active' : 'Inactive'; // Send enum values
                var categoryId = $(this).data('id');
                let statusUpdateApiEndpoint = $(this).data('endpoint');
                const toggleButton = $(this);
                swal({
                    title: "Are you sure?",
                    text: `You really want to ${status} this?`,
                    type: "warning",
                    showCancelButton: true,
                    closeOnConfirm: false,
                }, function(isConfirm) {
                    if (isConfirm) {
                        $.ajax({
                            type: "POST",
                            dataType: "json",
                            url: statusUpdateApiEndpoint,
                            data: {
                                'status': status,
                                'id': categoryId,
                                '_token': '{{ csrf_token() }}' 
                            },
                            success: function(response) {
                                if(response.success){
                                    swal({
                                        title: "Success!",
                                        text: response.message,
                                        type: "success",
                                        showConfirmButton: false
                                    }) 
    
                                    setTimeout(() => {
                                        location.reload();
                                    }, 2000);
                                }
                            },
                            error: function(xhr, status, error) {
                                console.error('Error updating status:', error);
                            }
                        });
                    } else {
                        console.log('sasd');
                        toggleButton.prop('checked', !toggleButton.prop('checked')); 
                    }
                });
            });
        });
    </script>
    @endpush
@endif

@if($hasPlugin('imagePreview'))
<script>
    function previewImage(input, previewId) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            reader.onload = function(e) {
                $(previewId).prop('hidden', false).attr('src', e.target.result);
            }
            reader.readAsDataURL(input.files[0]);
        }
    }

    $('#category_image').change(function() {
        previewImage(this, '#preview-category');
    });

    $('#add-subcategory').change(function() {
        previewImage(this, '#preview-subcategory');
    });
</script>
@endif