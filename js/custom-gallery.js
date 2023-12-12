jQuery(document).ready(function($) {
    $('#custom-dropdown-gallery').on('change', function() {
        var selectedCategory = $(this).val();

        // Make an AJAX call to the server
        $.ajax({
            url: ajax_object.ajax_url, // URL from localized script
            type: 'POST',
            data: {
                'action': 'load_gallery_by_category', // The AJAX action hook
                'category': selectedCategory // The selected category
            },
            success: function(response) {
                // Populate the gallery container with the response
                $('#custom-gallery-container').html(response);
            },
            error: function() {
                // Handle errors
                $('#custom-gallery-container').html('<p>Error loading gallery.</p>');
            }
        });
    });
});
