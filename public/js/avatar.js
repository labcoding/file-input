$(function () {

    var fileUploaded = false;

    $.each($('div.fileinput .thumbnail'), function() {
        var parent = $(this).closest('.fileinput');

        $('div.fileinput .thumbnail').on('click', function() {
            parent.find('[type=file]').click();
        });
    });

    $("input[type=file]").change(function() {
        if(this.files.length == 0) {
            $('input[name="avater"]').val('');
            return;
        }

        var parent = $(this).closest('.form-group');
        parent.removeClass('has-error');
        parent.find('.help-block').html('');

        var reader = new FileReader();
        reader.readAsDataURL(this.files[0]);
        reader.onload = function() {
            $('#crop-modal').find('img#crop-avatar').attr('src', this.result);
            $('#crop-modal').modal('show');
        };

        fileUploaded = false;
    });

    var $image = $('#crop-avatar');
    $('#crop-modal').on('shown.bs.modal', function () {
        $image.cropper({
            // autoCropArea: 0.5,
            aspectRatio: 1,
            preview: '.preview-container',
            minCropBoxWidth: 220,
            minCropBoxHeight: 220,
            movable: false
        });
    }).on('hidden.bs.modal', function () {
        $image.cropper('destroy');
        $('#crop-modal').find('.help-block').html('');

        if(fileUploaded === false) {
            $('.fileinput').fileinput('reset');
        }
    });

    $('#crop-modal').on('click', '#save', function() {

        var selection = $image.cropper("getData");

        var formData = new FormData();
        formData.append('avater', $("input[type=file]")[0].files[0]);
        formData.append('crop[x]', Math.abs(Math.ceil(selection.x)));
        formData.append('crop[y]', Math.abs(Math.ceil(selection.y)));
        formData.append('crop[w]', selection.width);
        formData.append('crop[h]', selection.height);

        $.ajax({
            url: $('#crop-modal').data('url'),
            type: 'POST',
            data: formData,
            enctype: 'multipart/form-data',
            processData: false,  // tell jQuery not to process the data
            contentType: false,   // tell jQuery not to set contentType
            success: function(response, statusText, xhr) {
                fileUploaded = true;

                $('input[name="avater"]').val(response.name);
                $('.fileinput-preview img').attr('src', response.file);

                $('#crop-modal').modal('hide');
            },
            error: function(xhr, statusText, errorThrown) {
                var messages = '';
                console.log(xhr);
                $.each(xhr.responseJSON.errors, function(k, errors) {
                    $.each(errors, function(k, v) {
                        messages += v + '<br\>';
                    });
                });

                $('#crop-modal').find('.help-block').html(messages);
            }
        });
    });
});