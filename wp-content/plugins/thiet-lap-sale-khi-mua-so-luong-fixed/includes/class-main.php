<?php

namespace PQSALE;

/**
 * Main Plugin Class
 */
class Main {
    private $frontend = null;
    
    public function __construct() {
        add_action('init', [$this, 'maybe_create_tables']);

        // Hook admin menu
        add_action('admin_menu', [$this, 'register_admin_menu']);
        
        // Hook admin enqueue scripts
        add_action('admin_enqueue_scripts', [$this, 'admin_enqueue_scripts']);
        
        // Hook frontend enqueue scripts
        add_action('wp_enqueue_scripts', [$this, 'frontend_enqueue_scripts']);
        
        // AJAX handlers
        add_action('wp_ajax_pqsale_get_progress', [$this, 'ajax_get_progress']);
        add_action('wp_ajax_nopriv_pqsale_get_progress', [$this, 'ajax_get_progress']);
        add_action('wp_ajax_pqsale_sync_selection', [$this, 'ajax_sync_selection']);
        add_action('wp_ajax_nopriv_pqsale_sync_selection', [$this, 'ajax_sync_selection']);
        add_action('wp_ajax_update_item_selected_status', [$this, 'ajax_update_item_selected_status']);
        add_action('wp_ajax_nopriv_update_item_selected_status', [$this, 'ajax_update_item_selected_status']);
        
        // Admin AJAX handlers
        add_action('wp_ajax_pqsale_delete_setting', [$this, 'ajax_delete_setting']);

        // Initialize Frontend only after all plugins are loaded
        add_action('plugins_loaded', [$this, 'init_frontend'], 20);
    }

    /**
     * Initialize Frontend class if WooCommerce is active
     */
    public function init_frontend() {
        if (class_exists('WooCommerce')) {
            $this->frontend = new Frontend();
        }
    }

    /**
     * Create plugin tables if activation hook was not triggered.
     */
    public function maybe_create_tables() {
        if (!is_admin()) {
            return;
        }

        $installed_version = get_option('pqsale_db_version');

        if ($installed_version !== PQSALE_VERSION) {
            $this->activate_plugin();
        }
    }
    
    /**
     * Register admin menu
     */
    public function register_admin_menu() {
        add_menu_page(
            'Sale Số Lượng',
            'Sale Số Lượng',
            'manage_options',
            'pqsale-settings',
            [$this, 'render_admin_page'],
            'dashicons-tag',
            26
        );
    }
    
    /**
     * Render admin page
     */
    public function render_admin_page() {
        $admin = new Admin();
        $admin->render_page();
    }
    
    /**
     * Enqueue admin scripts and styles
     */
    public function admin_enqueue_scripts($hook) {
        if (strpos($hook, 'pqsale-settings') === false) {
            return;
        }
        
        wp_enqueue_style('pqsale-admin', PQSALE_PLUGIN_URL . 'assets/css/admin.css', [], PQSALE_VERSION);
        wp_enqueue_script('pqsale-admin', PQSALE_PLUGIN_URL . 'assets/js/admin.js', ['jquery'], PQSALE_VERSION, true);
        
        wp_localize_script('pqsale-admin', 'pqsaleAdmin', [
            'ajaxurl' => admin_url('admin-ajax.php'),
            'nonce' => wp_create_nonce('pqsale_nonce'),
        ]);
    }
    
    /**
     * Enqueue frontend scripts and styles
     */
    public function frontend_enqueue_scripts() {
        if (!function_exists('is_woocommerce')) {
            return;
        }

        if (!is_woocommerce() && !is_cart() && !is_checkout()) {
            return;
        }
        
        wp_enqueue_style('pqsale-frontend', PQSALE_PLUGIN_URL . 'assets/css/frontend.css', [], PQSALE_VERSION);
        wp_enqueue_script('pqsale-frontend', PQSALE_PLUGIN_URL . 'assets/js/frontend.js', ['jquery', 'wc-add-to-cart'], PQSALE_VERSION, true);
        
        wp_localize_script('pqsale-frontend', 'pqsaleData', [
            'ajaxurl' => admin_url('admin-ajax.php'),
            'nonce' => wp_create_nonce('pqsale_nonce'),
        ]);
    }
    
    /**
     * Activate plugin - Create database table
     */
    public function activate_plugin() {
        global $wpdb;
        $charset_collate = $wpdb->get_charset_collate();
        
        $settings_table = $wpdb->prefix . 'pqsale_settings';
        $usage_table = $wpdb->prefix . 'pqsale_usage';
        
        $sql = "CREATE TABLE $settings_table (
            id bigint(20) unsigned NOT NULL AUTO_INCREMENT,
            name varchar(100) NOT NULL,
            min_quantity int(11) NOT NULL DEFAULT 1,
            discount_type varchar(20) NOT NULL DEFAULT 'percent',
            discount_value decimal(10,2) NOT NULL DEFAULT 0,
            enabled_from datetime NULL,
            enabled_to datetime NULL,
            max_usage int(11) NOT NULL DEFAULT 0,
            usage_count int(11) NOT NULL DEFAULT 0,
            created_at datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
            PRIMARY KEY  (id)
        ) $charset_collate;";
        
        require_once ABSPATH . 'wp-admin/includes/upgrade.php';
        dbDelta($sql);
        
        $sql2 = "CREATE TABLE $usage_table (
            id bigint(20) unsigned NOT NULL AUTO_INCREMENT,
            setting_id bigint(20) unsigned NOT NULL,
            user_id bigint(20) unsigned NULL,
            session_id varchar(100) NULL,
            used_at datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
            PRIMARY KEY  (id),
            KEY setting_id (setting_id)
        ) $charset_collate;";
        
        dbDelta($sql2);

        update_option('pqsale_db_version', PQSALE_VERSION);
    }
    
    /**
     * Deactivate plugin
     */
    public function deactivate_plugin() {
        // Cleanup if needed
    }
    
    /**
     * AJAX: Get progress data
     */
    public function ajax_get_progress() {
        check_ajax_referer('pqsale_nonce', 'nonce');

        if (!class_exists('WooCommerce')) {
            wp_send_json_error(['message' => 'WooCommerce chưa được kích hoạt.']);
        }
        
        $frontend = new Frontend();
        wp_send_json_success($frontend->get_progress_data());
    }

    /**
     * AJAX: Sync selected cart items into session.
     */
    public function ajax_sync_selection() {
        check_ajax_referer('pqsale_nonce', 'nonce');

        if (!class_exists('WooCommerce') || !WC()->session) {
            wp_send_json_error(['message' => 'WooCommerce session không khả dụng.']);
        }

        $selected_keys = isset($_POST['selected_keys']) ? (array) $_POST['selected_keys'] : [];
        $selected_keys = array_values(array_filter(array_map('sanitize_text_field', $selected_keys)));

        $frontend = new Frontend();
        $frontend->sync_cart_selection($selected_keys);

        wp_send_json_success([
            'selected_keys' => $selected_keys,
            'progress' => $frontend->get_progress_data(),
        ]);
    }

    /**
     * AJAX: Update a single cart item's selected state.
     */
    public function ajax_update_item_selected_status() {
        check_ajax_referer('pqsale_nonce', 'nonce');

        if (!class_exists('WooCommerce') || !WC()->session) {
            wp_send_json_error(['message' => 'WooCommerce session không khả dụng.']);
        }

        $cart_key = isset($_POST['cart_key']) ? sanitize_text_field(wp_unslash($_POST['cart_key'])) : '';
        $selected = isset($_POST['selected']) ? sanitize_text_field(wp_unslash($_POST['selected'])) : 'yes';

        if ($cart_key === '') {
            wp_send_json_error(['message' => 'Thiếu cart_key.']);
        }

        $normalized = $selected === 'no' ? 'no' : 'yes';
        WC()->session->set('selected_item_' . $cart_key, $normalized);

        $frontend = new Frontend();
        wp_send_json_success([
            'cart_key' => $cart_key,
            'selected' => $normalized,
            'progress' => $frontend->get_progress_data(),
        ]);
    }

    /**
     * AJAX: Delete setting
     */
    public function ajax_delete_setting() {
        $admin = new Admin();
        $admin->ajax_delete_setting();
    }
}
