<?php
/*
    Plugin Name: Magnify - Suggestive Search Plugin
    Plugin URI: 
    Description: Real-time search suggestions that display relevant results as users type. Easy to customize, fast, and responsive on all devices.
    Version: 1.1.5
    Author: themagnifico52
    Author URI: https://www.themagnifico.net/
    License: GPL2
    Text Domain: magnify-suggestive-search
*/

// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) {
    exit;
}
define( 'MNSSP_EXT_FILE', __FILE__ );
define( 'MNSSP_URL', plugin_dir_url( MNSSP_EXT_FILE ) );
define( 'MNSSP_PATH', plugin_dir_path( MNSSP_EXT_FILE ) );
define( 'MNSSP_API_URL', 'https://license.themagnifico.net/api/general/' );
define( 'MNSSP_VER', '1.1.5' );
define( 'MNSSP_MAIN_URL', 'https://www.themagnifico.net/' );

add_action('admin_enqueue_scripts', 'mnssp_enqueue_admin_styles' );
function mnssp_enqueue_admin_styles($hook) {

        if (  isset($_GET['page']) && $_GET['page'] === 'mnssp_create_search_bar' || isset($_GET['page']) && $_GET['page'] === 'mnssp_edit_search_bar' ) {
            
            // Select2 CSS
            wp_enqueue_style(
                'select2-css',
                MNSSP_URL . 'assets/css/select2.min.css',
                array(),
                '4.1.0'
            );

            // Select2 JS
            wp_enqueue_script(
                'select2-js',
                MNSSP_URL . 'assets/js/select2.min.js',
                array( 'jquery' ),
                '4.1.0',
                true
            );

            wp_add_inline_script(
                    'select2-js',
                    "jQuery(function($){
                        $('.select2').select2({ width: '92%' });
                    });"
                );
        }

    

        wp_enqueue_style(
            'mnssp-global-styles',
            MNSSP_URL . 'assets/css/style.css',
            array(),
            MNSSP_VER,
            'all'
        );

        if ( $hook != 'advanced-search_page_mnssp_guide_search_bar' ) {
            
            wp_enqueue_script(
                'mnssp-pagination-scripts',
                MNSSP_URL . 'assets/js/mnssp-pagination.js',
                array('jquery'),
                MNSSP_VER,
                true
            );

            wp_localize_script('mnssp-pagination-scripts', 'mnssp_pagination_object', array(
                'ajaxurl' => admin_url('admin-ajax.php'),
                'nonce'   => wp_create_nonce('mnssp_create_pagination_nonce_action')
            ));
        }

        if (strpos($hook, 'suggestive-search_page') === false && $hook != 'toplevel_page_mnssp_dashboard' && $hook != 'suggestive-search_page_mnssp_guide_search_bar' && $hook != 'toplevel_page_mnssp_templates') {
            return;
        }

        wp_enqueue_style(
            'mnssp-admin-styles',
            MNSSP_URL . 'assets/css/mnssp-admin.css',
            array(),
            MNSSP_VER,
            'all'
        );

        $custom_css = ".notice {
            display: none !important;
        }";

        wp_add_inline_style('mnssp-admin-styles', $custom_css);

        wp_enqueue_style(
            'mnssp-fontawesome-all-min-css',
            MNSSP_URL . 'assets/css/fontawesome-all.min.css',
            array(),
            MNSSP_VER,
            'all'
        );

        wp_enqueue_style(
            'mnssp-fontawesome-iconpicker-css',
            MNSSP_URL . 'assets/css/fontawesome-iconpicker.min.css',
            array(),
            MNSSP_VER,
            'all'
        );

        wp_enqueue_script(
            'mnssp-iconpicker-js',
            MNSSP_URL . 'assets/js/fontawesome-iconpicker.min.js',
            array('jquery'),
            MNSSP_VER,
            true
        );

        wp_enqueue_script(
            'mnssp-admin-scripts',
            MNSSP_URL . 'assets/js/mnssp-admin.js',
            [ 'jquery' ],
            MNSSP_VER,
            true
        );

        $redirect_url = add_query_arg(
            array(
                'page' => 'mnssp_display_search_bar',
                'nonce' => wp_create_nonce('redirect_nonce')
            ),
            admin_url('admin.php')
        );

        wp_localize_script('mnssp-admin-scripts', 'mnssp_object', array(
            'ajaxurl' => admin_url('admin-ajax.php'),
            'redirect_url' => $redirect_url,
            'nonce'   => wp_create_nonce('mnssp_create_search_bar_nonce_action')
        ));

        wp_enqueue_style('wp-color-picker');
        wp_enqueue_script('wp-color-picker');
}

add_action('wp_enqueue_scripts', 'mnssp_enqueue_styles');
function mnssp_enqueue_styles() {
    global $mnssp_template_types;global $post;

    if (isset($mnssp_template_types)) {
        $template_types = array_unique($mnssp_template_types);

        foreach ($template_types as $template_type) {
            wp_enqueue_style('mnssp-' . $template_type, plugins_url("assets/css/{$template_type}.css", __FILE__), array(), MNSSP_VER, 'all');
        }
        $mnssp_template_types = array();
    }
    
    if ( isset($post) && has_shortcode($post->post_content, 'mnssp-bar') ) {

        wp_enqueue_script('jquery-ui-autocomplete');

        wp_enqueue_script(
            'mnssp-frontend-scripts',
            MNSSP_URL . 'assets/js/mnssp-frontend.js',
            array('jquery'),
            MNSSP_VER,
            true
        );

        $mnssp_settings = get_option('mnssp_settings');
        $minimum_character = isset($mnssp_settings['minimum_character']) ? $mnssp_settings['minimum_character'] : 2;

        wp_localize_script('mnssp-frontend-scripts', 'mnssp_frontend_object', array(
            'ajaxurl'           => admin_url('admin-ajax.php'),
            'nonce'             => wp_create_nonce('mnssp_search_bar_nonce_action'),
            'minimum_character' => $minimum_character
        ));

        wp_enqueue_style(
            'mnssp-jquery-base-ui-css',
            MNSSP_URL . 'assets/css/jquery-ui.css',
            array(),
            MNSSP_VER,
            'all'
        );

        wp_enqueue_style(
            'mnssp-fontawesome-all-min-css',
            MNSSP_URL . 'assets/css/fontawesome-all.min.css',
            array(),
            MNSSP_VER,
            'all'
        );

        wp_enqueue_style(
            'mnssp-inline-styles',
            MNSSP_URL . 'assets/css/mnssp-inline.css',
            array(),
            MNSSP_VER,
            'all'
        );
    }
}

add_action( 'enqueue_block_editor_assets', 'mnssp_enqueue_block_editor_assets' );
function mnssp_enqueue_block_editor_assets() {

    wp_enqueue_script(
        'mnssp-editor-js',
        MNSSP_URL . 'assets/js/mnssp-editor.js',
        array( 'jquery' ),
        MNSSP_VER,
        true
    );

    wp_enqueue_style(
        'mnssp-editor-styles',
        MNSSP_URL . 'assets/css/mnssp-editor.css',
        array(),
        MNSSP_VER,
        'all'
    );
}

require_once MNSSP_PATH . 'ajax/ajax.php';
require_once MNSSP_PATH . 'global-functions.php';
require_once MNSSP_PATH . 'menus/admin-menu.php';
require_once MNSSP_PATH . 'posttype/magnify-suggestive-search.php';
require_once MNSSP_PATH . 'templates/shortcode.php';



// add_action('pre_get_posts', 'mnssp_limit_search_results');
// function mnssp_limit_search_results($query) {
//     if (isset($_GET['source']) && $_GET['source'] == 'magnify-suggestive-search') {
//         $query->set('posts_per_page', $_GET['source']);
//         $query->set('no_found_rows', true);
//     }
// }
add_filter('upload_size_limit', function () {
    return 256 * 1024 * 1024; 
});

add_filter('big_image_size_threshold', '__return_false');

/**
 * COMPLETELY disable image processing
 * (critical for large PNG maps)
 */
add_filter('wp_image_editors', '__return_empty_array');

/**
 * Stop generating image metadata
 */
add_filter('wp_generate_attachment_metadata', function () {
    return [];
});

/**
 * Stop thumbnail creation
 */
add_filter('intermediate_image_sizes_advanced', '__return_empty_array');
