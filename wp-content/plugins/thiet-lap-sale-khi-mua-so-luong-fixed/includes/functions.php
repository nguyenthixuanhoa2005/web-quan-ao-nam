<?php

namespace PQSALE;

/**
 * Helper Functions
 */

/**
 * Get all active promotions
 */
function get_active_promotions() {
    global $wpdb;
    $table_name = $wpdb->prefix . 'pqsale_settings';
    $current_time = current_time('mysql');
    
    return $wpdb->get_results($wpdb->prepare("
        SELECT * FROM {$table_name}
        WHERE (enabled_from IS NULL OR enabled_from <= %s)
        AND (enabled_to IS NULL OR enabled_to >= %s)
        AND (max_usage = 0 OR usage_count < max_usage)
        ORDER BY min_quantity ASC
    ", $current_time, $current_time));
}

/**
 * Get promotion by ID
 */
function get_promotion($id) {
    global $wpdb;
    $table_name = $wpdb->prefix . 'pqsale_settings';
    
    return $wpdb->get_row($wpdb->prepare("
        SELECT * FROM {$table_name}
        WHERE id = %d
    ", $id));
}

/**
 * Calculate discount amount
 */
function calculate_discount($promotion, $cart_total) {
    if ($promotion->discount_type === 'percent') {
        return $cart_total * ((float)$promotion->discount_value / 100);
    } else {
        return (float)$promotion->discount_value;
    }
}

/**
 * Check if promotion is active
 */
function is_promotion_active($promotion) {
    $current_time = current_time('mysql');
    
    // Check date range
    if ($promotion->enabled_from && $promotion->enabled_from > $current_time) {
        return false;
    }
    
    if ($promotion->enabled_to && $promotion->enabled_to < $current_time) {
        return false;
    }
    
    // Check usage limit
    if ($promotion->max_usage > 0 && $promotion->usage_count >= $promotion->max_usage) {
        return false;
    }
    
    return true;
}

/**
 * Format price
 */
function format_price($amount) {
    return number_format($amount, 0, ',', '.');
}

/**
 * Record promotion usage
 */
function record_promotion_usage($promotion_id) {
    global $wpdb;
    $table_name = $wpdb->prefix . 'pqsale_settings';
    $usage_table = $wpdb->prefix . 'pqsale_usage';
    
    $user_id = get_current_user_id();
    $session_id = WC()->session->get_customer_id();
    
    // Insert usage record
    $wpdb->insert($usage_table, [
        'setting_id' => (int)$promotion_id,
        'user_id' => $user_id > 0 ? $user_id : null,
        'session_id' => $session_id,
    ]);
    
    // Update usage count
    $wpdb->query($wpdb->prepare("
        UPDATE {$table_name}
        SET usage_count = usage_count + 1
        WHERE id = %d
    ", $promotion_id));
}

/**
 * Get promotion usage count
 */
function get_promotion_usage_count($promotion_id) {
    global $wpdb;
    $usage_table = $wpdb->prefix . 'pqsale_usage';
    
    return (int)$wpdb->get_var($wpdb->prepare("
        SELECT COUNT(*) FROM {$usage_table}
        WHERE setting_id = %d
    ", $promotion_id));
}

/**
 * Delete promotion
 */
function delete_promotion($promotion_id) {
    global $wpdb;
    $table_name = $wpdb->prefix . 'pqsale_settings';
    
    return $wpdb->delete($table_name, ['id' => $promotion_id], ['%d']);
}

/**
 * Update promotion
 */
function update_promotion($promotion_id, $data) {
    global $wpdb;
    $table_name = $wpdb->prefix . 'pqsale_settings';
    
    $update_data = [];
    $where = ['id' => $promotion_id];
    
    if (isset($data['name'])) {
        $update_data['name'] = sanitize_text_field($data['name']);
    }
    
    if (isset($data['min_quantity'])) {
        $update_data['min_quantity'] = (int)$data['min_quantity'];
    }
    
    if (isset($data['discount_type'])) {
        $update_data['discount_type'] = in_array($data['discount_type'], ['percent', 'fixed']) ? $data['discount_type'] : 'percent';
    }
    
    if (isset($data['discount_value'])) {
        $update_data['discount_value'] = (float)$data['discount_value'];
    }
    
    if (isset($data['enabled_from'])) {
        $update_data['enabled_from'] = !empty($data['enabled_from']) ? $data['enabled_from'] : null;
    }
    
    if (isset($data['enabled_to'])) {
        $update_data['enabled_to'] = !empty($data['enabled_to']) ? $data['enabled_to'] : null;
    }
    
    if (isset($data['max_usage'])) {
        $update_data['max_usage'] = (int)$data['max_usage'];
    }
    
    return $wpdb->update($table_name, $update_data, $where);
}
