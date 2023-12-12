<?php
/**
 * Plugin Name: Custom Elementor Dropdown Gallery
 * Description: Elementor widget for a dropdown menu displaying image galleries.
 * Version: 1.0
 * Author: GuiFraV
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}

// Enqueue necessary scripts and styles
function custom_elementor_gallery_scripts() {
    wp_enqueue_script( 'jquery' );
    wp_enqueue_script( 'custom-gallery-ajax', plugins_url( '/js/custom-gallery.js', __FILE__ ), array( 'jquery' ), '1.0', true );
    wp_localize_script( 'custom-gallery-ajax', 'ajax_object', array( 'ajax_url' => admin_url( 'admin-ajax.php' ) ) );
    wp_enqueue_style( 'custom-gallery-style', plugins_url( '/css/custom-gallery.css', __FILE__ ) );
}
add_action( 'wp_enqueue_scripts', 'custom_elementor_gallery_scripts' );

// Load Elementor and the custom widget
function load_elementor() {
    if ( ! did_action( 'elementor/loaded' ) ) {
        return;
    }

    require_once( 'elementor-custom-dropdown-gallery-widget.php' );
}
add_action( 'init', 'load_elementor' );

// AJAX handler for loading the gallery
add_action( 'wp_ajax_load_gallery_by_category', 'load_gallery_by_category' );
add_action( 'wp_ajax_nopriv_load_gallery_by_category', 'load_gallery_by_category' );

function load_gallery_by_category() {
    $category = sanitize_text_field( $_POST['category'] );

    // Retrieve images for the selected category
    // Note: You will need to implement the logic to retrieve images based on the category

    // Placeholder for gallery HTML
    $gallery_html = '<div class="image-gallery">';

    // Example: Loop through images and add them to the gallery
    // foreach ($images as $image) {
    //     $gallery_html .= '<img src="' . esc_url($image['url']) . '" alt="' . esc_attr($image['alt']) . '">';
    // }

    $gallery_html .= '</div>';

    echo $gallery_html;
    wp_die(); // this is required to terminate immediately and return a proper response
}
