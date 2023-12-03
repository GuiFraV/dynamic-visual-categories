<?php
/*
    Plugin Name: Dynamic Visual Categories
    Plugin URI: https://yourwebsite.com/
    Description: A dynamic dropdown menu that displays images based on the selected category.
    Version: 1.0
    Author: Your Name
    Author URI: https://yourwebsite.com/
    License: GPL2
*/


function dvc_enqueue_scripts() {
    wp_enqueue_style('dvc-style', plugins_url('/css/style.css', __FILE__));
    wp_enqueue_script('dvc-script', plugins_url('/js/script.js', __FILE__), array('jquery'), null, true);
    wp_enqueue_script('dvc-lightbox', plugins_url('/js/lightbox.js', __FILE__), array('jquery'), null, true);

    // Localize the script with new data
    wp_localize_script('dvc-script', 'dvcAjax', array('ajaxurl' => admin_url('admin-ajax.php')));
}
add_action('wp_enqueue_scripts', 'dvc_enqueue_scripts');

// Function to handle the AJAX request
function dvc_ajax_handler() {
    // Check for the category ID and sanitize it to prevent security issues
    if (isset($_POST['category_id'])) {
        $category_id = intval($_POST['category_id']); // Sanitize as integer

        // Initialize the response array
        $response = array('success' => false, 'content' => '');

        // Fetch images associated with the category
        $args = array(
            'post_type' => 'attachment',
            'post_mime_type' => 'image',
            'posts_per_page' => -1,
            'post_status' => 'inherit',
            'tax_query' => array(
                array(
                    'taxonomy' => 'category', // Use the correct taxonomy
                    'field' => 'term_id',
                    'terms' => $category_id,
                ),
            ),
        );

        $images = get_posts($args);

        // If images are found for the category
        if (!empty($images)) {
            ob_start(); // Start output buffering to capture HTML
            foreach ($images as $image) {
                // Generate the image HTML, could be a custom function to render your image
                echo wp_get_attachment_image($image->ID, 'large'); // Or any size you want
            }
            $html_of_images = ob_get_clean(); // Get the buffered content

            // Prepare the response data
            $response['success'] = true;
            $response['content'] = $html_of_images;
        } else {
            // No images found for the category
            $response['content'] = '<p>No images found in this category.</p>';
        }

        // Send the JSON response
        wp_send_json($response);
    } else {
        // Send an error response if category ID is not set
        wp_send_json(array('success' => false, 'content' => 'Category ID not provided.'));
    }

    // Don't forget to stop execution afterward
    wp_die();
}

// Hook the function to wp_ajax_ and wp_ajax_nopriv_ actions
add_action('wp_ajax_dvc_get_images', 'dvc_ajax_handler');
add_action('wp_ajax_nopriv_dvc_get_images', 'dvc_ajax_handler');

function dvc_shortcode($atts) {
    // Default attributes can be merged with user-defined ones.
    $atts = shortcode_atts(array(
        'default_category' => 'all',
    ), $atts, 'dynamic_visual_categories');

    ob_start();
    ?>
    <select class="category-dropdown">
        <!-- Options will be dynamically populated based on categories -->
    </select>
    <div class="image-grid">
        <!-- Images will be loaded here based on the selected category -->
    </div>
    <?php
    return ob_get_clean();
}
add_shortcode('dynamic_visual_categories', 'dvc_shortcode');
