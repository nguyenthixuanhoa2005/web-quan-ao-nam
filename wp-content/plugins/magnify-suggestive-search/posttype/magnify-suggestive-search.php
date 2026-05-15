<?php
function mnssp_register_post_type() {
    $labels = array(
        'name'               => _x('Adv Search Masters', 'post type general name', 'magnify-suggestive-search'),
        'singular_name'      => _x('Adv Search Master', 'post type singular name', 'magnify-suggestive-search'),
        'menu_name'          => _x('Adv Search Masters', 'admin menu', 'magnify-suggestive-search'),
        'name_admin_bar'     => _x('Adv Search Master', 'add new on admin bar', 'magnify-suggestive-search'),
        'add_new'            => _x('Add New', 'book', 'magnify-suggestive-search'),
        'add_new_item'       => __('Add New Adv Search Master', 'magnify-suggestive-search'),
        'new_item'           => __('New Adv Search Master', 'magnify-suggestive-search'),
        'edit_item'          => __('Edit Adv Search Master', 'magnify-suggestive-search'),
        'view_item'          => __('View Adv Search Master', 'magnify-suggestive-search'),
        'all_items'          => __('All Adv Search Masters', 'magnify-suggestive-search'),
        'search_items'       => __('Search Adv Search Masters', 'magnify-suggestive-search'),
        'parent_item_colon'  => __('Parent Adv Search Masters:', 'magnify-suggestive-search'),
        'not_found'          => __('No adv search masters found.', 'magnify-suggestive-search'),
        'not_found_in_trash' => __('No adv search masters found in Trash.', 'magnify-suggestive-search'),
    );

    $args = array(
        'labels'             => $labels,
        'public'             => true,
        'publicly_queryable' => true,
        'show_ui'            => true,
        'show_in_menu'       => true,
        'query_var'          => true,
        'rewrite'            => array('slug' => 'magnify_search'),
        'capability_type'    => 'post',
        'has_archive'        => true,
        'hierarchical'       => false,
        'menu_position'      => 5,
        'supports'           => array('title', 'editor', 'thumbnail'),
        'show_in_rest'       => false
    );

    register_post_type('magnify_search', $args);
}

add_action('init', 'mnssp_register_post_type');

add_action('admin_menu', 'mnssp_hide_post_type_menu');
function mnssp_hide_post_type_menu() {
    remove_menu_page('edit.php?post_type=magnify_search');
    remove_submenu_page('edit.php?post_type=magnify_search', 'post-new.php?post_type=magnify_search');
}

add_action('current_screen', 'mnssp_redirect_post_type_default_screens');

function mnssp_redirect_post_type_default_screens() {

    if (defined('DOING_AJAX') && DOING_AJAX) {
        return;
    }

    $screen = get_current_screen();
    
    if ($screen->post_type == 'magnify_search') {
        if ($screen->base == 'edit' && isset($_GET['action']) && $_GET['action'] == 'add') {

            if (isset($_GET['nonce'])) {
                $nonce = sanitize_text_field(wp_unslash($_GET['nonce']));
                if (wp_verify_nonce($nonce, 'redirect_nonce')) {
                    wp_redirect(admin_url('admin.php?page=mnssp_dashboard'));
                    exit;
                }
            }
        }
    }

    register_setting('mnssp_settings_group', 'mnssp_settings');
}