jQuery(document).ready(function () {
    var $ = jQuery;
    var frame;
    if ($('.set_custom_images').length > 0) {
        if (typeof wp !== 'undefined' && wp.media && wp.media.editor) {
            $(document).on('click', '.set_custom_images', function (e) {
                e.preventDefault();

                var button = $(this);
                var hidden_img = button.parents('.item').find('.image_id');
                var img_url = button.parents('.item').find('.img_url');

                // Create a new media frame
                frame = wp.media({
                    title: 'Select or Upload media of your chosen slider',
                    button: {
                        text: 'Insert into slider'
                    },
                    multiple: false  // Set to true to allow multiple files to be selected
                });


                // When an image is selected in the media frame...
                frame.on( 'select', function() {

                    // Get media attachment details from the frame state
                    var attachment = frame.state().get('selection').first().toJSON();

                    // Send the attachment URL to our custom image input field.
                    var ext = attachment.url.substr( (attachment.url.lastIndexOf('.') +1) );

                    if (ext === 'jpeg' || ext === 'jpg' || ext === 'png'){

                        var img = button.parents('.item').find('.slider_img');

                        if (img.length < 1){
                            var img_elem = '<img src="'+attachment.url+'" alt="img" width="200" height="200" class="slider_img" id="slider_img_'+jQuery('input[name="i"]').val()+'"/>';

                            button.parents('.item').find('.img_container').prepend(img_elem);
                            button.parents('.item').find('.img_container > .video_elem').remove();

                            //jQuery('input[name="i"]').val( parseInt(jQuery('input[name="i"]').val() )+ 1 ) ;
                        }else
                            img.attr('src', attachment.url);

                    }else if (ext === 'mp4' || ext === 'webm' || ext === 'ogg'){
                        var img = button.parents('.item').find('.slider_img');

                        var video_elem = '<video class="video_elem" controls="" ><source src="'+attachment.url+'" type="video/'+ext.toLowerCase()+'"></video>';

                        img.after(video_elem);
                        img.remove();

                    }

                    hidden_img.val(attachment.id);
                    img_url.val(attachment.url);

                    // Send the attachment id to our hidden input
                    imgIdInput.val( attachment.id );

                    // Hide the add image link
                    addImgLink.addClass( 'hidden' );

                    // Unhide the remove image link
                    delImgLink.removeClass( 'hidden' );
                });

                // Finally, open the modal on click
                frame.open();

                return false;
            });
        }
    }
    $('#add_more').click(function () {
        $('.addmore_spinner').addClass('show').removeClass('hide');

        var index_elem = $('#i');
        var index = parseInt(index_elem.val());
        index_elem.val(index + 1);

        var data = {
            'action': 'get_slider_item_template',
            'index': ( index + 1 )
        };

        $.get(bx_slider.ajax_url, data, function (response) {
            $('.post_slider_imgs_wrapper').append(response);

            $('.addmore_spinner').removeClass('show').addClass('hide');
        });
    });


    $(document).on('click', '.remove_slide', function (e) {
        if (confirm('Are you sure?')) {
            $(this).parents('.item').fadeOut(function () {
                $(this).remove();
            });
        }
        return false;
    });


    if ($('#adaptive_height').prop("checked") === true) {
        $('#slider_height').attr('readonly', true).attr('title', 'Disabled because adaptive height is enabled.');
    } else {
        $('#slider_height').attr('readonly', false).attr('title', '');

    }


    $('#adaptive_height').click(function () {

        if ($(this).prop("checked") === true)
            $('#slider_height').attr('readonly', true).attr('title', 'Disabled because adaptive height is enabled.');
        else
            $('#slider_height').attr('readonly', false).attr('title', '');

    });

    $(".item-parent").sortable({
        placeholder: "ui-state-highlight"
    });
    $(".item-parent").disableSelection();
});




