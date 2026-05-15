<?php

/**
 * Plugin Configuration
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

// Plugin Info
if (!defined('PQSALE_PLUGIN_NAME')) {
    define('PQSALE_PLUGIN_NAME', 'Thiết Lập Sale Khi Mua Số Lượng');
}

if (!defined('PQSALE_PLUGIN_SLUG')) {
    define('PQSALE_PLUGIN_SLUG', 'product-quantity-sale');
}

if (!defined('PQSALE_TEXT_DOMAIN')) {
    define('PQSALE_TEXT_DOMAIN', 'product-quantity-sale');
}

// Paths
if (!defined('PQSALE_PLUGIN_DIR')) {
    define('PQSALE_PLUGIN_DIR', plugin_dir_path(__FILE__));
}

if (!defined('PQSALE_PLUGIN_URL')) {
    define('PQSALE_PLUGIN_URL', plugin_dir_url(__FILE__));
}

// Database Tables
define('PQSALE_TABLE_SETTINGS', 'pqsale_settings');
define('PQSALE_TABLE_USAGE', 'pqsale_usage');

// Settings Keys
define('PQSALE_SETTING_MIN_QTY', 'min_quantity');
define('PQSALE_SETTING_DISCOUNT_TYPE', 'discount_type');
define('PQSALE_SETTING_DISCOUNT_VALUE', 'discount_value');
define('PQSALE_SETTING_ENABLED_FROM', 'enabled_from');
define('PQSALE_SETTING_ENABLED_TO', 'enabled_to');
define('PQSALE_SETTING_MAX_USAGE', 'max_usage');

// Discount Types
define('PQSALE_DISCOUNT_TYPE_PERCENT', 'percent');
define('PQSALE_DISCOUNT_TYPE_FIXED', 'fixed');

// Capabilities
define('PQSALE_CAPABILITY_MANAGE', 'manage_options');

// Cache Keys
define('PQSALE_CACHE_KEY_PROMOTIONS', PQSALE_PLUGIN_SLUG . '_promotions');
define('PQSALE_CACHE_DURATION', HOUR_IN_SECONDS);

// Messages
define('PQSALE_MSG_CHOOSE_MORE', 'Chọn thêm %d sản phẩm để được giảm giá!');
define('PQSALE_MSG_ACHIEVED', '✓ Bạn đã đạt điều kiện! Giảm giá đã được áp dụng.');
define('PQSALE_MSG_DISCOUNT_APPLIED', 'Giảm giá %s');

// CSS/JS Version (for cache busting)
define('PQSALE_ASSETS_VERSION', PQSALE_VERSION . '-' . date('YmdHi'));

// Debug Mode (dùng WP_DEBUG nếu có)
define('PQSALE_DEBUG', defined('WP_DEBUG') && WP_DEBUG);

// Limits
define('PQSALE_MAX_PROMOTIONS', 100);
define('PQSALE_MAX_PROMOTION_NAME_LENGTH', 100);
define('PQSALE_MAX_QUANTITY', 999);
define('PQSALE_MAX_DISCOUNT_PERCENT', 100);
define('PQSALE_MAX_DISCOUNT_FIXED', 9999999);

// Settings
define('PQSALE_AUTO_APPLY_DISCOUNT', true);
define('PQSALE_SHOW_PROGRESS_BAR', true);
define('PQSALE_PROGRESS_BAR_POSITION', 'after_cart_table'); // before_cart_table, after_cart_table

// Hooks
define('PQSALE_HOOK_DISCOUNT_APPLIED', PQSALE_PLUGIN_SLUG . '_discount_applied');
define('PQSALE_HOOK_PROGRESS_UPDATED', PQSALE_PLUGIN_SLUG . '_progress_updated');
define('PQSALE_HOOK_PROMOTION_CREATED', PQSALE_PLUGIN_SLUG . '_promotion_created');
define('PQSALE_HOOK_PROMOTION_DELETED', PQSALE_PLUGIN_SLUG . '_promotion_deleted');

// AJAX Actions
define('PQSALE_AJAX_GET_PROGRESS', 'pqsale_get_progress');
define('PQSALE_AJAX_DELETE_SETTING', 'pqsale_delete_setting');
define('PQSALE_AJAX_UPDATE_SETTING', 'pqsale_update_setting');

// User Roles
if (!defined('PQSALE_ADMIN_ROLE')) {
    define('PQSALE_ADMIN_ROLE', 'administrator');
}
