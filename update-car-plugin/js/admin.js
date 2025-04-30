jQuery(document).ready(function($) {
    // Gallery Image Upload
    $('#add-gallery-images').click(function(e) {
        e.preventDefault();
        
        var frame = wp.media({
            title: 'Select Gallery Images',
            button: { text: 'Add to gallery' },
            library: { type: 'image' },
            multiple: true
        });

        frame.on('select', function() {
            var attachments = frame.state().get('selection').map(function(attachment) {
                attachment = attachment.toJSON();
                return '<div class="gallery-image">' +
                    '<img src="' + attachment.sizes.thumbnail.url + '">' +
                    '<button class="remove-image">×</button>' +
                    '<input type="hidden" name="gallery_images[]" value="' + attachment.id + '">' +
                    '</div>';
            });
            
            $('#gallery-container').append(attachments.join(''));
        });

        frame.open();
    });

    // Remove Gallery Image
    $(document).on('click', '.remove-image', function(e) {
        e.preventDefault();
        $(this).parent('.gallery-image').remove();
    });
});