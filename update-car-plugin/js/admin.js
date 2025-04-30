jQuery(document).ready(function($) {
    var frame;
    
    $('#add-gallery-images').on('click', function(e) {
        e.preventDefault();
        
        // If the media frame already exists, reopen it
        if (frame) {
            frame.open();
            return;
        }
        
        // Create a new media frame
        frame = wp.media({
            title: 'Select Gallery Images',
            button: {
                text: 'Add to gallery'
            },
            multiple: true
        });

        // When an image is selected in the media frame...
        frame.on('select', function() {
            // Get media attachment details from the frame state
            var attachments = frame.state().get('selection').map(function(attachment) {
                attachment = attachment.toJSON();
                if (attachment.sizes && attachment.sizes.thumbnail) {
                    return {
                        id: attachment.id,
                        url: attachment.sizes.thumbnail.url
                    };
                }
                return {
                    id: attachment.id,
                    url: attachment.url
                };
            });

            // Loop through the attachments and add them to the gallery
            attachments.forEach(function(attachment) {
                var imageHtml = '<div class="gallery-image" data-id="' + attachment.id + '">' +
                    '<img src="' + attachment.url + '" alt="">' +
                    '<input type="hidden" name="gallery_images[]" value="' + attachment.id + '">' +
                    '<button type="button" class="remove-image" title="Remove image">×</button>' +
                    '</div>';
                $('#gallery-container').append(imageHtml);
            });
        });

        // Finally, open the modal on click
        frame.open();
    });

    // Remove image when the × is clicked
    $('#gallery-container').on('click', '.remove-image', function(e) {
        e.preventDefault();
        $(this).closest('.gallery-image').remove();
    });

    // Make gallery sortable
    $('#gallery-container').sortable({
        items: '.gallery-image',
        cursor: 'move',
        scrollSensitivity: 40,
        forcePlaceholderSize: true,
        forceHelperSize: true,
        helper: 'clone',
        opacity: 0.65,
        placeholder: 'gallery-image-placeholder',
        tolerance: 'pointer',
        start: function(e, ui) {
            ui.placeholder.height(ui.item.height());
        }
    }).disableSelection();
});