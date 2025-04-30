<?php
/**
 * Plugin Name: Miers Car Custom Post Type
 * Description: WordPress plugin for car inventory management with custom fields and galleries
 * Version: 1.0.0
 * Author: Programmer Nomad
 * Author URI: https://github.com/ProgrammerNomad
 * Plugin URI: https://github.com/ProgrammerNomad/miers-car-custom-post
 * License: GPL v2 or later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 */

if (!defined('ABSPATH')) exit;

// Register Custom Post Type
add_action('init', 'ucp_register_update_car_cpt');
function ucp_register_update_car_cpt() {
    register_post_type('update_car', array(
        'labels' => array(
            'name' => __('Update Cars', 'update-car-plugin'),
            'singular_name' => __('Update Car', 'update-car-plugin'),
            'add_new' => __('Add New', 'update-car-plugin'),
            'add_new_item' => __('Add New Car Update', 'update-car-plugin'),
            'edit_item' => __('Edit Car Update', 'update-car-plugin'),
            'new_item' => __('New Car Update', 'update-car-plugin'),
            'view_item' => __('View Car Update', 'update-car-plugin'),
            'search_items' => __('Search Car Updates', 'update-car-plugin'),
            'not_found' => __('No updates found', 'update-car-plugin'),
            'all_items' => __('All Updates', 'update-car-plugin'),
        ),
        'public' => true,
        'has_archive' => true,
        'rewrite' => array('slug' => 'update-car'),
        'supports' => array('title', 'thumbnail'),
        'show_in_rest' => true,
        'menu_position' => 20,
        'taxonomies' => array('category'),
    ));
}

// Enable Post Thumbnails
add_action('after_setup_theme', 'ucp_enable_thumbnails');
function ucp_enable_thumbnails() {
    add_theme_support('post-thumbnails');
}

// Add Meta Boxes
add_action('add_meta_boxes', 'ucp_add_car_meta_boxes');
function ucp_add_car_meta_boxes() {
    add_meta_box(
        'car_images',
        __('Car Images', 'update-car-plugin'),
        'ucp_render_car_images_meta_box',
        'update_car',
        'normal',
        'high'
    );
    
    add_meta_box(
        'car_details',
        __('Car Details', 'update-car-plugin'),
        'ucp_render_car_details_meta_box',
        'update_car',
        'normal',
        'high'
    );
    
    add_meta_box(
        'car_content',
        __('Car Content', 'update-car-plugin'),
        'ucp_render_car_content_meta_box',
        'update_car',
        'normal',
        'high'
    );
}

// Render Image Meta Box
function ucp_render_car_images_meta_box($post) {
    wp_nonce_field('ucp_save_car_images', 'ucp_car_images_nonce');
    
    $gallery_images = get_post_meta($post->ID, 'car_gallery_images', true);
    ?>
    <div class="car-images-section">
        <div class="gallery-images">
            <label><?php _e('Gallery Images', 'update-car-plugin'); ?></label>
            <div id="gallery-container">
                <?php 
                if($gallery_images && is_array($gallery_images)): 
                    foreach($gallery_images as $image_id): 
                        $image = wp_get_attachment_image_src($image_id, 'thumbnail');
                        if($image):
                ?>
                    <div class="gallery-image" data-id="<?php echo esc_attr($image_id); ?>">
                        <img src="<?php echo esc_url($image[0]); ?>" alt="">
                        <input type="hidden" name="gallery_images[]" value="<?php echo esc_attr($image_id); ?>">
                        <button type="button" class="remove-image" title="<?php esc_attr_e('Remove image', 'update-car-plugin'); ?>">×</button>
                    </div>
                <?php 
                        endif;
                    endforeach; 
                endif; 
                ?>
            </div>
            <button type="button" class="button" id="add-gallery-images">
                <?php _e('Add Gallery Images', 'update-car-plugin'); ?>
            </button>
        </div>
    </div>
    <?php
}

// Render Details Meta Box
function ucp_render_car_details_meta_box($post) {
    wp_nonce_field('ucp_save_car_details', 'ucp_car_details_nonce');
    
    $fields = array(
        'resource_id' => __('Resource ID', 'update-car-plugin'),
        'price' => __('Price', 'update-car-plugin'),
        'year' => __('Year', 'update-car-plugin'),
        'transmission' => __('Transmission', 'update-car-plugin'),
        'fire' => __('Fire', 'update-car-plugin'),
        'type' => __('Type', 'update-car-plugin'),
        'model' => __('Model', 'update-car-plugin'),
        'fuel' => __('Fuel', 'update-car-plugin'),
        'power' => __('Power', 'update-car-plugin'),
        'co2_emissions' => __('CO2 Emissions', 'update-car-plugin'),
        'driving_range' => __('Driving Range', 'update-car-plugin'),
        'colour' => __('Colour', 'update-car-plugin'),
        'internal_ref' => __('Internal Ref', 'update-car-plugin'),
        'cc' => __('CC', 'update-car-plugin'),
        'kw' => __('KW', 'update-car-plugin'),
        'pk' => __('PK', 'update-car-plugin'),
        'kilometers' => __('Kilometers', 'update-car-plugin'),
        'euro_standard' => __('Euro Standard', 'update-car-plugin'),
        'load_capacity' => __('Load Capacity', 'update-car-plugin'),
    );
    
    echo '<div class="car-details-grid">';
    foreach($fields as $key => $label) {
        $value = get_post_meta($post->ID, $key, true);
        ?>
        <div class="field-group">
            <label for="<?php echo $key; ?>"><?php echo $label; ?></label>
            <input type="text" id="<?php echo $key; ?>" name="<?php echo $key; ?>" 
                   value="<?php echo esc_attr($value); ?>">
        </div>
        <?php
    }
    
    // Additional Specifications (textarea)
    $specs = get_post_meta($post->ID, 'additional_specs', true);
    ?>
    <div class="field-group full-width">
        <label for="additional_specs"><?php _e('Additional Specifications', 'update-car-plugin'); ?></label>
        <textarea id="additional_specs" name="additional_specs"><?php echo esc_textarea($specs); ?></textarea>
    </div>
    </div>
    <?php
}

// Render Content Meta Box
function ucp_render_car_content_meta_box($post) {
    wp_nonce_field('ucp_save_car_content', 'ucp_car_content_nonce');
    
    $main_content = get_post_meta($post->ID, 'main_content', true);
    $table_content = get_post_meta($post->ID, 'table_content', true);
    ?>
    <div class="car-content-section">
        <div class="content-field">
            <label><?php _e('Main Content', 'update-car-plugin'); ?></label>
            <?php wp_editor($main_content, 'main_content', array('media_buttons' => true)); ?>
        </div>
        <div class="content-field">
            <label><?php _e('Table Content', 'update-car-plugin'); ?></label>
            <?php wp_editor($table_content, 'table_content', array('media_buttons' => true)); ?>
        </div>
    </div>
    <?php
}

// Save Meta Box Data
add_action('save_post_update_car', 'ucp_save_car_meta', 10, 2);
function ucp_save_car_meta($post_id, $post) {
    // Verify nonces
    if (!isset($_POST['ucp_car_images_nonce']) || 
        !wp_verify_nonce($_POST['ucp_car_images_nonce'], 'ucp_save_car_images') ||
        !isset($_POST['ucp_car_details_nonce']) || 
        !wp_verify_nonce($_POST['ucp_car_details_nonce'], 'ucp_save_car_details') ||
        !isset($_POST['ucp_car_content_nonce']) || 
        !wp_verify_nonce($_POST['ucp_car_content_nonce'], 'ucp_save_car_content')) {
        return;
    }

    // Save gallery images
    if (isset($_POST['gallery_images'])) {
        update_post_meta($post_id, 'car_gallery_images', array_map('absint', $_POST['gallery_images']));
    }

    // Save car details
    $fields = array(
        'resource_id', 'price', 'year', 'transmission', 'fire', 'type', 'model',
        'fuel', 'power', 'co2_emissions', 'driving_range', 'colour', 'internal_ref',
        'cc', 'kw', 'pk', 'kilometers', 'euro_standard', 'load_capacity', 'additional_specs'
    );
    
    foreach($fields as $field) {
        if (isset($_POST[$field])) {
            update_post_meta($post_id, $field, sanitize_text_field($_POST[$field]));
        }
    }

    // Save content fields
    if (isset($_POST['main_content'])) {
        update_post_meta($post_id, 'main_content', wp_kses_post($_POST['main_content']));
    }
    if (isset($_POST['table_content'])) {
        update_post_meta($post_id, 'table_content', wp_kses_post($_POST['table_content']));
    }
}

// Enqueue Scripts and Styles
add_action('admin_enqueue_scripts', 'ucp_enqueue_admin_scripts');
function ucp_enqueue_admin_scripts($hook) {
    global $post_type;
    if ('update_car' !== $post_type) return;
    
    wp_enqueue_media();
    wp_enqueue_script('jquery-ui-sortable');
    wp_enqueue_script('ucp-admin-script', 
        plugins_url('js/admin.js', __FILE__), 
        array('jquery', 'jquery-ui-sortable'), 
        '1.0.0', 
        true
    );
    wp_enqueue_style('ucp-admin-style', 
        plugins_url('css/admin.css', __FILE__)
    );
}
