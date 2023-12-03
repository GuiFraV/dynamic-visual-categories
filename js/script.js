jQuery(document).ready(function($) {
    $('.category-dropdown').change(function() {
        var category_id = $(this).val();
        
        $.ajax({
            url: dvcAjax.ajaxurl,
            type: 'POST',
            data: {
                action: 'dvc_get_images',
                category_id: category_id
            },
            success: function(response) {
                if(response.success) {
                    $('.image-grid').html(response.content);
                } else {
                    // Handle errors.
                }
            }
        });
    });
});
