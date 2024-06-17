<?php
/*
Plugin Name: WPWC Categories
Description: Displays WooCommerce product categories in WordPress with easy-to-customize settings and shortcode support.
Version: 1.0
Author: HusamSaleh
*/

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

// Register and enqueue styles
function wpwc_categories_styles() {
    wp_enqueue_style('wpwc-categories-style', plugins_url('style-wpwc.css', __FILE__));
    wp_enqueue_style('wpwc-categories-responsive-style', plugins_url('responsive-wpwc.css', __FILE__));
}
add_action('wp_enqueue_scripts', 'wpwc_categories_styles');

// Register and enqueue admin scripts
function wpwc_categories_admin_scripts($hook) {
    if ('toplevel_page_wpwc_categories' !== $hook) {
        return;
    }
    wp_enqueue_style('wp-color-picker');
    wp_enqueue_script('wp-color-picker');
    wp_enqueue_script('wpwc-categories-script', plugins_url('wpwc-categories-script.js', __FILE__), array('jquery', 'wp-color-picker'));
}
add_action('admin_enqueue_scripts', 'wpwc_categories_admin_scripts');

// Register settings, add settings page
function wpwc_categories_register_settings() {
    add_option('wpwc_categories_image_size', '100');
    add_option('wpwc_categories_image_radius', '50');
    add_option('wpwc_categories_space_between', '10');
    add_option('wpwc_categories_font_size', '16');
    add_option('wpwc_categories_text_color', '#000000'); // Default text color
    add_option('wpwc_categories_bg_color', '#FFFFFF'); // Default background color
    add_option('wpwc_categories_orderby', 'name'); // Default orderby
    add_option('wpwc_categories_order', 'ASC'); // Default order

    register_setting('wpwc_categories_options_group', 'wpwc_categories_image_size');
    register_setting('wpwc_categories_options_group', 'wpwc_categories_image_radius');
    register_setting('wpwc_categories_options_group', 'wpwc_categories_space_between');
    register_setting('wpwc_categories_options_group', 'wpwc_categories_font_size');
    register_setting('wpwc_categories_options_group', 'wpwc_categories_text_color');
    register_setting('wpwc_categories_options_group', 'wpwc_categories_bg_color');
    register_setting('wpwc_categories_options_group', 'wpwc_categories_orderby');
    register_setting('wpwc_categories_options_group', 'wpwc_categories_order');

    add_menu_page('WPWC Categories Settings', 'WPWC Categories', 'manage_options', 'wpwc_categories', 'wpwc_categories_options_page');
}
add_action('admin_menu', 'wpwc_categories_register_settings');

// Settings page content
function wpwc_categories_options_page() {
    ?>
    <div class="wrap">
        <h2>WPWC Categories Settings</h2>
        <form method="post" action="options.php">
            <?php settings_fields('wpwc_categories_options_group'); ?>
            <table class="form-table">
                <tr valign="top">
                    <th scope="row">Image Size (px):</th>
                    <td><input type="text" id="wpwc_categories_image_size" name="wpwc_categories_image_size" value="<?php echo get_option('wpwc_categories_image_size'); ?>" /></td>
                </tr>
                <tr valign="top">
                    <th scope="row">Image Radius (%):</th>
                    <td><input type="text" id="wpwc_categories_image_radius" name="wpwc_categories_image_radius" value="<?php echo get_option('wpwc_categories_image_radius'); ?>" /></td>
                </tr>
                <tr valign="top">
                    <th scope="row">Space Between Images (px):</th>
                    <td><input type="text" id="wpwc_categories_space_between" name="wpwc_categories_space_between" value="<?php echo get_option('wpwc_categories_space_between'); ?>" /></td>
                </tr>
                <tr valign="top">
                    <th scope="row">Font Size (px):</th>
                    <td><input type="text" id="wpwc_categories_font_size" name="wpwc_categories_font_size" value="<?php echo get_option('wpwc_categories_font_size'); ?>" /></td>
                </tr>
                <tr valign="top">
                    <th scope="row">Text Color:</th>
                    <td><input type="text" id="wpwc_categories_text_color" name="wpwc_categories_text_color" value="<?php echo get_option('wpwc_categories_text_color'); ?>" class="wp-color-picker" /></td>
                </tr>
                <tr valign="top">
                    <th scope="row">Background Color:</th>
                    <td><input type="text" id="wpwc_categories_bg_color" name="wpwc_categories_bg_color" value="<?php echo get_option('wpwc_categories_bg_color'); ?>" class="wp-color-picker" /></td>
                </tr>
                <tr valign="top">
                    <th scope="row">Order By:</th>
                    <td>
                        <select id="wpwc_categories_orderby" name="wpwc_categories_orderby">
                            <option value="name" <?php selected(get_option('wpwc_categories_orderby'), 'name'); ?>>Name</option>
                            <option value="id" <?php selected(get_option('wpwc_categories_orderby'), 'id'); ?>>ID</option>
                            <option value="count" <?php selected(get_option('wpwc_categories_orderby'), 'count'); ?>>Count</option>
                        </select>
                    </td>
                </tr>
                <tr valign="top">
                    <th scope="row">Order:</th>
                    <td>
                        <select id="wpwc_categories_order" name="wpwc_categories_order">
                            <option value="ASC" <?php selected(get_option('wpwc_categories_order'), 'ASC'); ?>>Ascending</option>
                            <option value="DESC" <?php selected(get_option('wpwc_categories_order'), 'DESC'); ?>>Descending</option>
                        </select>
                    </td>
                </tr>
            </table>
            <?php submit_button(); ?>
        </form>
    </div>
    <?php
    }

// Shortcode to display categories
function wpwc_categories_shortcode($atts) {
    $atts = shortcode_atts(array(
        'limit' => 10, // Default limit
    ), $atts, 'wpwc_categories');

    $image_size = get_option('wpwc_categories_image_size', '100');
    $image_radius = get_option('wpwc_categories_image_radius', '50');
    $space_between = get_option('wpwc_categories_space_between', '10');
    $font_size = get_option('wpwc_categories_font_size', '16');
    $args = array(
        'taxonomy'   => 'product_cat',
        'orderby'    => 'name',
        'order'      => 'ASC',
        'hide_empty' => false,
        'number'     => $atts['limit'], // Use the limit from shortcode attribute
    );

    $product_categories = get_terms($args);
    $output = '<div class="wpwc-categories" style="display: flex; flex-wrap: wrap; gap: ' . esc_attr($space_between) . 'px;">';

    foreach ($product_categories as $category) {
        $thumbnail_id = get_woocommerce_term_meta($category->term_id, 'thumbnail_id', true);
        $image_url = wp_get_attachment_url($thumbnail_id);
        $category_link = get_term_link($category);

        $output .= '<div class="wpwc-category" style="border-radius: ' . esc_attr($image_radius) . '%; font-size: ' . esc_attr($font_size) . 'px;">';
        $output .= sprintf('<a href="%s" style="display: block; text-align: center;">', esc_url($category_link));
        $output .= sprintf('<img src="%s" alt="%s" style="width: %spx; height: %spx; border-radius: %s%%;" />', esc_url($image_url), esc_attr($category->name), esc_attr($image_size), esc_attr($image_size), esc_attr($image_radius));
        $output .= sprintf('<span>%s</span>', esc_html($category->name));
        $output .= '</a>';
        $output .= '</div>';
    }

    $output .= '</div>';
    return $output;
}
add_shortcode('wpwc_categories', 'wpwc_categories_shortcode');
add_shortcode('wpwc_categories', 'wpwc_categories_shortcode');