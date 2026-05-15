<?php
/**
 * Plugin Name: Thiết Lập Sale Khi Mua Số Lượng
 * Description: Plugin để thiết lập giảm giá theo số lượng sản phẩm được chọn
 * Version: 1.0.0
 * Author: Hoa
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

// Define plugin constants
define('PQSALE_PLUGIN_DIR', plugin_dir_path(__FILE__));
define('PQSALE_PLUGIN_URL', plugin_dir_url(__FILE__));
define('PQSALE_VERSION', '1.0.0');

// Include plugin configuration
require_once PQSALE_PLUGIN_DIR . 'includes/config.php';

// Include plugin files
require_once PQSALE_PLUGIN_DIR . 'includes/functions.php';
require_once PQSALE_PLUGIN_DIR . 'includes/class-main.php';
require_once PQSALE_PLUGIN_DIR . 'includes/class-admin.php';
require_once PQSALE_PLUGIN_DIR . 'includes/class-frontend.php';

register_activation_hook(__FILE__, function () {
    $main = new \PQSALE\Main();
    $main->activate_plugin();
});

register_deactivation_hook(__FILE__, function () {
    $main = new \PQSALE\Main();
    $main->deactivate_plugin();
});

// Initialize plugin
add_action('plugins_loaded', function() {
    new \PQSALE\Main();
});
