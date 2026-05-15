<?php
add_action('wp_ajax_mnssp_save_search_bar', 'mnssp_save_search_bar');
function mnssp_save_search_bar() {

    check_ajax_referer('mnssp_create_search_bar_nonce_action', 'mnssp_search_bar_nonce');
    
    if (!current_user_can('edit_posts')) {
        wp_send_json_error(array('message' => 'You do not have permissions.'));
        return;
    }

    if (isset($_POST['form_data'])) {

        $form_data_raw = wp_strip_all_tags(wp_unslash($_POST['form_data']));

        if (!is_string($form_data_raw)) {
            wp_send_json_error(array('message' => 'Invalid form data.'));
            return;
        }
        $form_data = array();
        parse_str($form_data_raw, $form_data);

        $form_name = isset($form_data['mnssp_form_name']) ? sanitize_text_field($form_data['mnssp_form_name']) : '';
        $template_type = isset($form_data['mnssp_template_type']) ? sanitize_text_field($form_data['mnssp_template_type']) : '';

        $posttypes = isset($form_data['mnssp_posttypes']) && is_array($form_data['mnssp_posttypes'])
            ? array_map('sanitize_text_field', $form_data['mnssp_posttypes'])
            : array();

        $icon_picker = isset($form_data['mnssp_icon_picker']) ? sanitize_text_field($form_data['mnssp_icon_picker']) : '';
        $search_scope = isset($form_data['mnssp_search_scope']) ? sanitize_text_field($form_data['mnssp_search_scope']) : 'title';
        $priority = isset($form_data['mnssp_priority']) ? sanitize_text_field($form_data['mnssp_priority']) : 'relevance';
        $exclude_ids = isset($form_data['mnssp_exclude_ids']) ? sanitize_text_field($form_data['mnssp_exclude_ids']) : '';
        $exclude_categories = isset($form_data['mnssp_exclude_categories']) ? sanitize_text_field($form_data['mnssp_exclude_categories']) : '';
        $limit_per_page = isset($form_data['mnssp_limit_per_page']) ? sanitize_text_field($form_data['mnssp_limit_per_page']) : '';


        $post_id = isset($form_data['post_id']) ? intval($form_data['post_id']) : 0;

        if (empty($form_name) || empty($template_type)) {
            wp_send_json_error(array('message' => 'Form name and template type are required.'));
            return;
        }

        if ($post_id) {
            $post_update = array(
                'ID'           => $post_id,
                'post_title'    => $form_name,
                'post_status'   => 'publish',
                'meta_input'    => array(
                    'template_type' => $template_type,
                    'posttypes'      => $posttypes,
                    'icon_picker'    => $icon_picker,
                    'search_scope' => $search_scope,
                    'priority' => $priority,
                    'exclude_ids' => $exclude_ids,
                    'exclude_categories' => $exclude_categories,
                    'limit_per_page' => $limit_per_page,
                ),
            );

            $result = wp_update_post($post_update, true);

            if (is_wp_error($result)) {
                wp_send_json_error(array('message' => $result->get_error_message()));
            } else {
                wp_send_json_success(array('message' => 'Search bar updated successfully.'));
            }
        } else {
            $post_id = wp_insert_post(array(
                'post_type'    => 'magnify_search',
                'post_title'   => $form_name,
                'post_status'  => 'publish',
                'meta_input'   => array(
                    'template_type' => $template_type,
                    'posttypes'      => $posttypes,
                    'icon_picker'    => $icon_picker,
                    'search_scope' => $search_scope,
                    'priority' => $priority,
                    'exclude_ids' => $exclude_ids,
                    'exclude_categories' => $exclude_categories,
                    'limit_per_page' => $limit_per_page,
                ),
            ));
            
            if (is_wp_error($post_id)) {
                wp_send_json_error(array('message' => $post_id->get_error_message()));
            } else {
                wp_send_json_success(array('message' => 'Search bar created successfully.'));
            }
        }
    } else {
        wp_send_json_error(array('message' => 'No form data received.'));
    }
}

function mnssp_autocomplete_search() {
    global $wpdb;

    check_ajax_referer('mnssp_search_bar_nonce_action', 'mnssp_autocomplete_nonce');

    $term = isset($_GET['term']) ? sanitize_text_field(wp_unslash($_GET['term'])) : '';
    $post_types = isset($_GET['post_types']) ? explode(',', sanitize_text_field(wp_unslash($_GET['post_types']))) : array('post');

    $mnssp_settings = get_option('mnssp_settings');
    $no_result_label = isset($mnssp_settings['no_result_label']) && $mnssp_settings['no_result_label'] != '' ? $mnssp_settings['no_result_label'] : 'No post available';


    $bar_id = isset($_GET['bar_id']) ? intval($_GET['bar_id']) : 0;
    $search_scope = get_post_meta($bar_id, 'search_scope', true) ?: 'title';
    $priority = get_post_meta($bar_id, 'priority', true);
    $exclude_ids = get_post_meta($bar_id, 'exclude_ids', true);
    $exclude_categories = get_post_meta($bar_id, 'exclude_categories', true);

    $args = array(
        'post_type'   => $post_types,
        'post_status' => 'publish',
        'title_like'           => $term,
        'posts_per_page' => -1,
        'fields'      => 'ids',
    );

    if ($priority === 'date') {
        $args['orderby'] = 'date';
        $args['order'] = 'DESC';
    } elseif ($priority === 'views') {
        $args['meta_key'] = 'post_views_count';
        $args['orderby'] = 'meta_value_num';
        $args['order'] = 'DESC';
    } elseif ($priority === 'manual') {
        $args['meta_key'] = 'mnssp_priority';
        $args['orderby'] = 'meta_value_num';
    }

    if (!empty($exclude_ids)) {
        $args['post__not_in'] = array_map('intval', explode(',', $exclude_ids));
    }
    
    if (!empty($exclude_categories)) {
        $args['tax_query'] = array(
            array(
                'taxonomy' => 'category',
                'field' => 'term_id',
                'terms' => array_map('intval', explode(',', $exclude_categories)),
                'operator' => 'NOT IN'
            )
        );
    }
    

    // add_filter('posts_where', 'mnssp_title_like_posts_where', 10, 2);
    add_filter('posts_where', function ($where, $wp_query) use ($search_scope, $term, $wpdb) {
        $like = '%' . $wpdb->esc_like($term) . '%';
    
        if ($search_scope === 'title') {
            $where .= $wpdb->prepare(" AND $wpdb->posts.post_title LIKE %s", $like);
        } elseif ($search_scope === 'excerpt') {
            $where .= $wpdb->prepare(" AND $wpdb->posts.post_excerpt LIKE %s", $like);
        } elseif ($search_scope === 'content') {
            $where .= $wpdb->prepare(" AND $wpdb->posts.post_content LIKE %s", $like);
        } else {
            $where .= $wpdb->prepare(" AND ($wpdb->posts.post_title LIKE %s OR $wpdb->posts.post_excerpt LIKE %s OR $wpdb->posts.post_content LIKE %s)", $like, $like, $like);
        }
    
        return $where;
    }, 10, 2);

    $query = new WP_Query($args);

    remove_filter('posts_where', 'mnssp_title_like_posts_where', 10, 2);

    $suggestions = array();
    if ($query->have_posts()) {
        while ($query->have_posts()) {
            $query->the_post();

            $suggestions[] = array(
                'label' => get_the_title(),
                'value' => get_permalink(),
            );
        }
        wp_reset_postdata();
    } else {
        $suggestions[] = array(
            'label' => $no_result_label,
            'value' => '#',
        );
    }

    wp_send_json($suggestions);
}
add_action('wp_ajax_mnssp_autocomplete_search', 'mnssp_autocomplete_search');
add_action('wp_ajax_nopriv_mnssp_autocomplete_search', 'mnssp_autocomplete_search');

function mnssp_title_like_posts_where($where, $wp_query) {
    global $wpdb;

    if ($title_like = $wp_query->get('title_like')) {
        $where .= $wpdb->prepare(" AND $wpdb->posts.post_title LIKE %s", '%' . $wpdb->esc_like($title_like) . '%');
    }

    return $where;
}