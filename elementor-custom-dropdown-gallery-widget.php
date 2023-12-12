<?php
use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Repeater;

class Custom_Dropdown_Gallery_Widget extends Widget_Base {

    public function get_name() {
        return 'custom_dropdown_gallery';
    }

    public function get_title() {
        return 'Custom Dropdown Gallery';
    }

    public function get_icon() {
        return 'eicon-gallery-grid';
    }

    protected function _register_controls() {
        $this->start_controls_section(
            'content_section',
            [
                'label' => __('Content', 'plugin-name'),
                'tab' => Controls_Manager::TAB_CONTENT,
            ]
        );

        $repeater = new Repeater();

        $repeater->add_control(
            'category_name', [
                'label' => __( 'Category Name', 'plugin-name' ),
                'type' => Controls_Manager::TEXT,
                'default' => __( 'New Category' , 'plugin-name' ),
                'label_block' => true,
            ]
        );

        $repeater->add_control(
            'category_images',
            [
                'label' => __( 'Add Images', 'plugin-name' ),
                'type' => Controls_Manager::GALLERY,
                'default' => [],
            ]
        );

        $this->add_control(
            'categories_list',
            [
                'label' => __( 'Categories', 'plugin-name' ),
                'type' => Controls_Manager::REPEATER,
                'fields' => $repeater->get_controls(),
                'title_field' => '{{{ category_name }}}',
            ]
        );

        $this->end_controls_section();
    }

    protected function render() {
        $settings = $this->get_settings_for_display();

        if (!empty($settings['categories_list'])) {
            echo '<select id="custom-dropdown-gallery" class="custom-dropdown-gallery">';
            echo '<option value="">' . __('Select a Category', 'plugin-name') . '</option>';

            foreach ($settings['categories_list'] as $category) {
                echo '<option value="' . sanitize_title($category['category_name']) . '">' . esc_html($category['category_name']) . '</option>';
            }

            echo '</select>';
            echo '<div id="custom-gallery-container" class="custom-gallery-container"></div>';
        }
    }

    // Optional: Add content_template() method here if needed for live preview in Elementor editor

}

// Register the widget
\Elementor\Plugin::instance()->widgets_manager->register_widget_type( new Custom_Dropdown_Gallery_Widget() );
