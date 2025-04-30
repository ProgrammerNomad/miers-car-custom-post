jQuery(document).ready(function($) {
    // Gallery Image Upload
    $('#add-gallery-images').on('click', function(e) {
        e.preventDefault();

        var frame = wp.media({
            title: 'Select Gallery Images',
            button: {
                text: 'Add to gallery'
            },
            multiple: true
        });

        frame.on('select', function() {
            var attachments = frame.state().get('selection').map(function(attachment) {
                attachment = attachment.toJSON();
                return '<div class="gallery-image">' +
                    '<img src="' + attachment.sizes.thumbnail.url + '" alt="">' +
                    '<button type="button" class="remove-image" title="Remove image">×</button>' +
                    '<input type="hidden" name="gallery_images[]" value="' + attachment.id + '">' +
                    '</div>';
            });
            $('#gallery-container').append(attachments.join(''));
        });

        frame.open();
    });

    // Remove Gallery Image
    $('#gallery-container').on('click', '.remove-image', function(e) {
        e.preventDefault();
        $(this).closest('.gallery-image').fadeOut(300, function() {
            $(this).remove();
        });
    });

    // Make gallery items sortable
    $('#gallery-container').sortable({
        items: '.gallery-image',
        cursor: 'move',
        scrollSensitivity: 40,
        forcePlaceholderSize: true,
        placeholder: 'gallery-image-placeholder'
    });
});