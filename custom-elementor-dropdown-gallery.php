<?php
/**
 * Plugin Name: Custom Elementor Dropdown Gallery
 * Description: Elementor widget for a dropdown menu displaying image galleries.
 * Version: 1.0
 * Author: Your Name
 */

define( 'WP_DEBUG', true );
define( 'WP_DEBUG_LOG', true );
define( 'WP_DEBUG_DISPLAY', false );
@ini_set( 'display_errors', 0 );


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
    error_log('Début du traitement AJAX pour charger la galerie.');

    if ( ! isset( $_POST['category'] ) ) {
        error_log('Erreur AJAX : Aucune catégorie n\'a été transmise.');
        wp_die('Aucune catégorie n\'a été transmise.', 400); // Envoie une réponse 400 (Bad Request)
    }

    $category = sanitize_text_field( $_POST['category'] );
    error_log('Catégorie demandée : ' . $category);

    $images = get_images_by_category($category);
    if ( empty( $images ) ) {
        error_log('Aucune image trouvée pour la catégorie : ' . $category);
        wp_die('Aucune image trouvée.', 404); // Envoie une réponse 404 (Not Found)
    }

    $gallery_html = '<div class="image-gallery">';
    foreach ($images as $image) {
        $gallery_html .= '<img src="' . esc_url($image['url']) . '" alt="' . esc_attr($image['alt']) . '">';
    }
    $gallery_html .= '</div>';

    error_log('Galerie générée pour la catégorie : ' . $category);
    echo $gallery_html;
    wp_die();
}


function custom_gallery_shortcode($atts) {
    // Attributs du shortcode, si nécessaire
    $atts = shortcode_atts( array(
        'category' => '', // Exemple d'attribut pour filtrer par catégorie
    ), $atts );

    $category = $atts['category'];
    $images = get_images_by_category($category); // Récupérez les images comme avant

    $gallery_html = '<div class="image-gallery">';
    foreach ($images as $image) {
        $gallery_html .= '<img src="' . esc_url($image['url']) . '" alt="' . esc_attr($image['alt']) . '">';
    }
    $gallery_html .= '</div>';

    return $gallery_html;
}
add_shortcode('custom_gallery', 'custom_gallery_shortcode');


// Implémentez ici votre logique pour récupérer les images en fonction de la catégorie
function get_images_by_category($category) {
    // Votre code pour récupérer les images
    return []; // Retourner un tableau d'images
}
