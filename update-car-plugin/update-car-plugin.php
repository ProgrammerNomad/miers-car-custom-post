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

/**
 * Register Custom Post Type for Cars
 */
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

/**
 * Enable Post Thumbnails Support
 */
add_action('after_setup_theme', 'ucp_enable_thumbnails');
function ucp_enable_thumbnails() {
    add_theme_support('post-thumbnails');
}

/**
 * Register Advanced Custom Fields
 * Requires ACF Plugin to be installed
 */
add_action('acf/init', 'ucp_register_acf_fields');
function ucp_register_acf_fields() {
    if (!function_exists('acf_add_local_field_group')) return;

    acf_add_local_field_group(array(
        'key' => 'group_update_car',
        'title' => __('Car Details', 'update-car-plugin'),
        'fields' => array(
            // Image Fields
            array(
                'key' => 'field_single_image',
                'label' => __('Car Image', 'update-car-plugin'),
                'name' => 'car_image',
                'type' => 'image',
                'return_format' => 'array',
                'preview_size' => 'medium',
                'library' => 'all',
                'instructions' => __('Upload a single image for this car.', 'update-car-plugin'),
            ),
            array(
                'key' => 'field_additional_gallery',
                'label' => __('Additional Gallery', 'update-car-plugin'),
                'name' => 'additional_gallery',
                'type' => 'gallery',
                'instructions' => __('Upload additional gallery images.', 'update-car-plugin'),
                'preview_size' => 'medium',
                'library' => 'all',
                'return_format' => 'array',
                'insert' => 'append',
            ),
            // Car Specifications
            array('key' => 'field_resource_id', 'label' => 'ResourceID', 'name' => 'resource_id', 'type' => 'text'),
            array('key' => 'field_price', 'label' => 'Price', 'name' => 'price', 'type' => 'number'),
            array('key' => 'field_year', 'label' => 'Year', 'name' => 'year', 'type' => 'text'),
            array('key' => 'field_transmission', 'label' => 'Transmission', 'name' => 'transmission', 'type' => 'text'),
            array('key' => 'field_fire', 'label' => 'Fire', 'name' => 'fire', 'type' => 'text'),
            array('key' => 'field_type', 'label' => 'Type', 'name' => 'type', 'type' => 'text'),
            array('key' => 'field_model', 'label' => 'Model', 'name' => 'model', 'type' => 'text'),
            array('key' => 'field_fuel', 'label' => 'Fuel', 'name' => 'fuel', 'type' => 'text'),
            array('key' => 'field_power', 'label' => 'Power', 'name' => 'power', 'type' => 'text'),
            array('key' => 'field_co2', 'label' => 'CO2 emissions', 'name' => 'co2_emissions', 'type' => 'text'),
            array('key' => 'field_driving_range', 'label' => 'Driving Range', 'name' => 'driving_range', 'type' => 'text'),
            array('key' => 'field_additional_specs', 'label' => 'Options', 'name' => 'additional_specs', 'type' => 'textarea'),
            array('key' => 'field_colour', 'label' => 'Colour', 'name' => 'colour', 'type' => 'text'),
            array('key' => 'field_internal_ref', 'label' => 'InternalRef', 'name' => 'internal_ref', 'type' => 'text'),
            array('key' => 'field_first_registration', 'label' => 'First Registration', 'name' => 'first_registration', 'type' => 'date_picker'),
            array('key' => 'field_cc', 'label' => 'CC', 'name' => 'cc', 'type' => 'text'),
            array('key' => 'field_kw', 'label' => 'KW', 'name' => 'kw', 'type' => 'text'),
            array('key' => 'field_pk', 'label' => 'PK', 'name' => 'pk', 'type' => 'text'),
            array('key' => 'field_kilometers', 'label' => 'Kilometers', 'name' => 'kilometers', 'type' => 'text'),
            array('key' => 'field_euro_standard', 'label' => 'Euro Standard', 'name' => 'euro_standard', 'type' => 'text'),
            array('key' => 'field_load_capacity', 'label' => 'Load Capacity', 'name' => 'load_capacity', 'type' => 'text'),
            // Content Fields
            array(
                'key' => 'field_main_content',
                'label' => __('Main Content', 'update-car-plugin'),
                'name' => 'main_content',
                'type' => 'wysiwyg',
                'instructions' => __('Write formatted content with text, media, HTML, etc.', 'update-car-plugin'),
                'tabs' => 'all',
                'toolbar' => 'full',
                'media_upload' => 1,
            ),
            array(
                'key' => 'field_table_content',
                'label' => __('Table', 'update-car-plugin'),
                'name' => 'table_content',
                'type' => 'wysiwyg',
                'instructions' => __('Add table content here (supports HTML or WYSIWYG).', 'update-car-plugin'),
                'tabs' => 'all',
                'toolbar' => 'full',
                'media_upload' => 1,
            ),
        ),
        'location' => array(
            array(
                array(
                    'param' => 'post_type',
                    'operator' => '==',
                    'value' => 'update_car',
                ),
            ),
        ),
    ));
}
