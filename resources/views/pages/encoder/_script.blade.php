<script>
    $(document).ready(function () {
        $('#Datatable').DataTable({
            processing: true,
            serverSide: true,
            // lengthChange: false,
            ajax: '{{ route('encoding.video.list') }}',
            columns: [
                {
                    data: 'video_id',
                    name: 'video_id'
                },
                {
                    data: 'file_name',
                    name: 'file_name'
                },
                {
                    data: 'action',
                    name: 'action',
                    orderable: false,
                    searchable: false
                }
            ]
        });
    });
</script>

{{--Control Encoding Button--}}
<script>
    $(document).ready(function () {
        $(document).on('click', '.encodingCheckbox', function () {
            let videoId = $(this).data('id')
            let  isChecked = $(this).prop('checked')
            let controlEncodingButton = $('#controlEncodingButton').val()
            let MultipleVideos = $('#MultipleVideos')

            let currentVideoIds = MultipleVideos.val()

            if (isChecked){
                /*Add ids*/
                MultipleVideos.val(currentVideoIds ? currentVideoIds + ',' + videoId : videoId)
                /*Inc*/
                controlEncodingButton++
                $('#controlEncodingButton').val(controlEncodingButton)
                $('#encodingButton').removeClass('disabled')
            } else {
                /*Remove ids*/
                MultipleVideos.val(currentVideoIds.split(',').filter(function (value) {
                    return value != videoId;
                }).join(','));

                /*Dec*/
                controlEncodingButton--
                $('#controlEncodingButton').val(controlEncodingButton)

            }

            if(controlEncodingButton === 0){
                $('#encodingButton').addClass('disabled')
            }


        })
    })
</script>

{{-- Control Select2 and Modal Behavior--}}
<script>
    $(function(){
        $('#mySelect').select2({
            dropdownParent: $('#myModal'),
            multiple: true
        });

        $('#myModal').on('hidden.bs.modal', function () {
            // Clear the selected instances when the modal is closed
            $('#mySelect').val(null).trigger('change');
            $('#encodingPath').val(null).trigger('change');
        });
    });
</script>

{{-- Encoding --}}
<script>
    $('#EncodingSubmitBtn').on('click', function (e) {
        e.preventDefault();
        const EncodingForm = $('#EncodingForm');
        // Clear previous error messages
        $('.error-message').remove();

        // Serialize the form data
        const formData = EncodingForm.serialize();

        $.ajax({
            url: '{{ route('encoding.video') }}',
            type: 'POST',
            data: formData,
            dataType: 'json', // Expect JSON response from the server
            // JavaScript to hide the modal after a successful operation
            success: function (data) {
                if (data.response === 200) {
                    // Reset the form fields
                    $('#EncodingForm')[0].reset();
                    // Rest Video Ids
                    $('#MultipleVideos').val('')

                    // Reload the DataTable if needed
                    $('#Datatable').DataTable().ajax.reload();

                    $("#myModal .btn-close").click()

                    Toast.fire({
                        icon: 'success',
                        title: data.message
                    });
                }
            },
            error: function (xhr, status, error) {
                if (xhr.status === 422) {
                    const errors = xhr.responseJSON.errors;

                    // Display error messages for each input field
                    $.each(errors, function (field, errorMessage) {
                        const inputField = $('[name="' + field + '"]');
                        inputField.after('<span class="error-message text-danger">' + errorMessage[0] + '</span>');
                    });
                } else {
                    console.log('An error occurred:', status, error);
                }
            }
        });
    });
</script>
