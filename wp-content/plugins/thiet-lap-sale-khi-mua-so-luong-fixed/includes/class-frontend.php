<?php

namespace PQSALE;

/**
 * Frontend Class - Handle customer-facing features
 */
class Frontend {
    
    private $table_name;
    private $option_key;
    
    public function __construct() {
        global $wpdb;
        $this->table_name = $wpdb->prefix . 'pqsale_settings';
        $this->option_key = 'pqsale_settings_fallback';
        
        // Full width display at the very top of the cart (above products table and order totals)
        // Priority 1 ensures it renders before any theme hooks
        add_action('woocommerce_before_cart', [$this, 'display_progress_bar'], 1);
        // Also hook woocommerce_before_cart_table as secondary anchor (fires inside the cart form, above rows)
        // We use woocommerce_before_cart_contents which is INSIDE the cart form — skip that.
        // Instead rely on JS to move the already-rendered block to the right place.
        
        // INTEGRATED SNIPPET LOGIC: Replace remove link with selection checkbox
        add_filter('woocommerce_cart_item_remove_link', [$this, 'replace_remove_with_checkbox'], 10, 2);

        // Visibility and Price Filters
        add_filter('woocommerce_cart_item_visible', [$this, 'filter_cart_item_visible'], 10, 3);
        add_filter('woocommerce_widget_cart_item_visible', [$this, 'filter_cart_item_visible'], 10, 3);
        add_filter('woocommerce_checkout_cart_item_visible', [$this, 'filter_cart_item_visible'], 10, 3);
        
        add_filter('woocommerce_cart_item_price', [$this, 'filter_cart_item_price'], 10, 3);
        add_filter('woocommerce_cart_item_subtotal', [$this, 'filter_cart_item_subtotal'], 10, 3);
        
        // Dynamic Pricing Logic
        add_action('woocommerce_cart_loaded_from_session', [$this, 'prioritize_selected_items_in_cart'], 20, 1);
        add_action('woocommerce_before_calculate_totals', [$this, 'zero_unchecked_item_prices'], 20);
        add_action('woocommerce_cart_calculate_fees', [$this, 'apply_discount'], 25);

        // Restore original prices from session so they are never permanently lost
        add_filter('woocommerce_get_cart_item_from_session', [$this, 'restore_original_price_from_session'], 10, 3);
        add_filter('woocommerce_add_cart_item', [$this, 'save_original_price_to_cart_item'], 10, 2);

        // Usage and Footer
        add_action('woocommerce_checkout_order_processed', [$this, 'record_discount_usage'], 10, 1);
        add_action('wp_footer', [$this, 'output_progress_template']);
    }

    /**
     * When a product is added to cart, save its original price so it can be restored later.
     */
    public function save_original_price_to_cart_item($cart_item, $cart_item_key) {
        if (!isset($cart_item['pqsale_original_price'])) {
            $cart_item['pqsale_original_price'] = $cart_item['data']->get_price();
        }
        return $cart_item;
    }

    /**
     * When cart is loaded from session, restore original price if available.
     * This prevents the zeroed price from persisting across page loads.
     */
    public function restore_original_price_from_session($cart_item, $values, $cart_item_key) {
        if (isset($values['pqsale_original_price']) && $values['pqsale_original_price'] > 0) {
            $cart_item['pqsale_original_price'] = $values['pqsale_original_price'];
            $cart_item['data']->set_price($values['pqsale_original_price']);
        }
        return $cart_item;
    }

    public function replace_remove_with_checkbox($link, $cart_item_key) {
        $selected = $this->is_cart_item_selected($cart_item_key);
        return sprintf(
            '<input type="checkbox" class="cart-item-checkbox" %s data-cart-key="%s">',
            checked($selected, true, false),
            esc_attr($cart_item_key)
        );
    }

    public function sync_cart_selection($selected_keys) {
        if (!WC()->session || !WC()->cart) return;

        $selected_keys = array_fill_keys(array_map('strval', (array) $selected_keys), true);

        foreach (WC()->cart->get_cart() as $cart_item_key => $cart_item) {
            WC()->session->set(
                'selected_item_' . $cart_item_key,
                isset($selected_keys[(string) $cart_item_key]) ? 'yes' : 'no'
            );
        }
    }

    public function prioritize_selected_items_in_cart($cart) {
        if (is_admin() && !defined('DOING_AJAX')) return;
        if (!$cart || empty($cart->cart_contents) || !is_array($cart->cart_contents)) return;

        $selected_items = [];
        $unselected_items = [];

        foreach ($cart->cart_contents as $cart_item_key => $cart_item) {
            if ($this->is_cart_item_selected($cart_item_key)) {
                $selected_items[$cart_item_key] = $cart_item;
            } else {
                $unselected_items[$cart_item_key] = $cart_item;
            }
        }

        $cart->cart_contents = $selected_items + $unselected_items;
    }
    
    public function display_progress_bar() {
        if (!is_cart()) return;
        $progress_data = $this->get_progress_data();
        if (empty($progress_data)) return;
        ?>
        <div class="pqsale-progress-container">
            <div class="pqsale-active-rules">
                <?php foreach ($progress_data as $rule) : ?>
                    <div class="pqsale-rule-item <?php echo $rule['is_active'] ? 'active' : 'inactive'; ?>">
                        <div class="pqsale-rule-header">
                            <h4><?php echo esc_html($rule['name']); ?></h4>
                            <span class="pqsale-discount-badge">
                                <?php echo $rule['discount_type'] === 'percent' ? 
                                    '-' . (float)$rule['discount_value'] . '%' : 
                                    '-' . number_format((float)$rule['discount_value'], 0, ',', '.') . 'đ'; 
                                ?>
                            </span>
                        </div>
                        <div class="pqsale-progress-wrapper">
                            <div class="pqsale-progress-bar">
                                <div class="pqsale-progress-fill" style="width: <?php echo min(100, ($rule['current_quantity'] / $rule['min_quantity']) * 100); ?>%">
                                    <span class="pqsale-progress-text">
                                        <?php echo (int)$rule['current_quantity']; ?>/<?php echo (int)$rule['min_quantity']; ?>
                                    </span>
                                </div>
                            </div>
                            <p class="pqsale-progress-hint">
                                <?php 
                                if ($rule['is_active']) {
                                    echo '✓ Bạn đã đạt điều kiện! Giảm giá đã được áp dụng.';
                                } else {
                                    $remaining = $rule['min_quantity'] - $rule['current_quantity'];
                                    echo 'Chọn thêm ' . (int)$remaining . ' sản phẩm để được giảm giá!';
                                }
                                ?>
                            </p>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
        <?php
    }
    
    public function get_progress_data() {
        if (!WC()->cart) return [];
        $cart_items = $this->get_selected_cart_items();
        if (empty($cart_items)) return [];
        $total_quantity = $this->calculate_cart_quantity($cart_items);
        $settings = $this->get_active_settings('ASC');
        if (empty($settings)) return [];
        $progress_data = [];
        foreach ($settings as $setting) {
            $progress_data[] = [
                'id' => (int)$setting->id,
                'name' => esc_html($setting->name),
                'min_quantity' => (int)$setting->min_quantity,
                'current_quantity' => (int)$total_quantity,
                'discount_type' => $setting->discount_type,
                'discount_value' => (float)$setting->discount_value,
                'is_active' => $total_quantity >= (int)$setting->min_quantity,
            ];
        }
        return $progress_data;
    }
    
    public function apply_discount() {
        if (is_admin() && !defined('DOING_AJAX')) return;
        if (!WC()->cart || WC()->cart->is_empty()) return;
        $cart_items = $this->get_selected_cart_items();
        if (empty($cart_items)) return;
        $total_quantity = $this->calculate_cart_quantity($cart_items);
        
        // Use WC built-in subtotal which reflects current quantities and our zeroed items
        $selected_subtotal = WC()->cart->get_subtotal();

        $settings = $this->get_active_settings('DESC');
        if (empty($settings)) return;
        $applicable_discount = null;
        foreach ($settings as $setting) {
            if ($total_quantity >= (int)$setting->min_quantity) {
                $applicable_discount = $setting;
                break;
            }
        }
        if (!$applicable_discount) return;
        if ($applicable_discount->discount_type === 'percent') {
            $discount_amount = $selected_subtotal * ((float)$applicable_discount->discount_value / 100);
        } else {
            $discount_amount = (float)$applicable_discount->discount_value;
        }
        if ($discount_amount > 0) {
            $discount_amount = round($discount_amount);
            WC()->cart->add_fee(
                __('Giảm giá ' . esc_html($applicable_discount->name), 'product-quantity-sale'),
                -$discount_amount,
                false
            );
            if (WC()->session) {
                WC()->session->set('pqsale_applied_rule_id', (int)$applicable_discount->id);
            }
        }
    }

    public function filter_cart_item_visible($visible, $cart_item, $cart_item_key) {
        return $visible;
    }

    public function filter_cart_item_price($price, $cart_item, $cart_item_key) {
        if (!$this->is_cart_item_selected($cart_item_key)) {
            // Show original price with strikethrough to indicate it's excluded from total
            $original_price = isset($cart_item['pqsale_original_price']) && $cart_item['pqsale_original_price'] > 0
                ? $cart_item['pqsale_original_price']
                : $cart_item['data']->get_price();
            return '<span class="pqsale-excluded-price">' . wc_price($original_price) . '</span>';
        }
        return $price;
    }

    public function filter_cart_item_subtotal($subtotal, $cart_item, $cart_item_key) {
        if (!$this->is_cart_item_selected($cart_item_key)) {
            // Show original subtotal with strikethrough to indicate it's excluded from total
            $original_price = isset($cart_item['pqsale_original_price']) && $cart_item['pqsale_original_price'] > 0
                ? $cart_item['pqsale_original_price']
                : $cart_item['data']->get_price();
            $original_subtotal = $original_price * $cart_item['quantity'];
            return '<span class="pqsale-excluded-price">' . wc_price($original_subtotal) . '</span>';
        }
        return $subtotal;
    }

    public function zero_unchecked_item_prices($cart) {
        if (is_admin() && !defined('DOING_AJAX')) return;
        foreach ($cart->get_cart() as $cart_item_key => $cart_item) {
            if (!$this->is_cart_item_selected($cart_item_key)) {
                // Store the original price before zeroing so it can be restored on next load
                $original_price = $cart_item['data']->get_price();
                if (!isset($cart_item['pqsale_original_price'])) {
                    $cart->cart_contents[$cart_item_key]['pqsale_original_price'] = $original_price;
                }
                $cart_item['data']->set_price(0);
            } else {
                // Restore original price if it was previously zeroed
                if (isset($cart_item['pqsale_original_price']) && $cart_item['pqsale_original_price'] > 0) {
                    $cart_item['data']->set_price($cart_item['pqsale_original_price']);
                }
            }
        }
    }

    private function is_cart_item_selected($cart_item_key) {
        if (!WC()->session) return true;
        return WC()->session->get('selected_item_' . $cart_item_key) !== 'no';
    }

    private function get_selected_cart_items() {
        if (!WC()->cart) return [];
        $selected_items = [];
        foreach (WC()->cart->get_cart() as $cart_item_key => $cart_item) {
            if ($this->is_cart_item_selected($cart_item_key)) {
                $selected_items[$cart_item_key] = $cart_item;
            }
        }
        return $selected_items;
    }

    private function calculate_cart_quantity($cart_items) {
        $total_quantity = 0;
        foreach ($cart_items as $item) {
            $total_quantity += (int) ($item['quantity'] ?? 0);
        }
        return $total_quantity;
    }

    public function record_discount_usage($order_id) {
        global $wpdb;
        if (!WC()->session) return;
        $rule_id = (int) WC()->session->get('pqsale_applied_rule_id');
        if ($rule_id <= 0) return;
        if ($this->table_exists()) {
            $wpdb->insert($wpdb->prefix . 'pqsale_usage', [
                'setting_id' => $rule_id,
                'user_id' => get_current_user_id() ?: null,
                'session_id' => WC()->session->get_customer_id(),
            ]);
            $wpdb->query($wpdb->prepare("UPDATE {$this->table_name} SET usage_count = usage_count + 1 WHERE id = %d", $rule_id));
        }
        WC()->session->__unset('pqsale_applied_rule_id');
    }

    private function get_active_settings($order = 'ASC') {
        global $wpdb;
        $order = strtoupper($order) === 'DESC' ? 'DESC' : 'ASC';
        $current_time = current_time('mysql');
        $settings = [];
        if ($this->table_exists()) {
            $settings = $wpdb->get_results($wpdb->prepare(
                "SELECT * FROM {$this->table_name}
                WHERE (enabled_from IS NULL OR enabled_from <= %s)
                AND (enabled_to IS NULL OR enabled_to >= %s)
                AND (max_usage = 0 OR usage_count < max_usage)
                ORDER BY min_quantity {$order}",
                $current_time, $current_time
            ));
        }
        if (!empty($settings)) return $settings;
        $fallback = get_option($this->option_key, []);
        if (!is_array($fallback)) return [];
        $filtered = array_filter($fallback, function ($item) use ($current_time) {
            return (empty($item['enabled_from']) || $item['enabled_from'] <= $current_time) &&
                   (empty($item['enabled_to']) || $item['enabled_to'] >= $current_time) &&
                   ((int) ($item['max_usage'] ?? 0) === 0 || (int) ($item['usage_count'] ?? 0) < (int) ($item['max_usage'] ?? 0));
        });
        usort($filtered, function ($a, $b) use ($order) {
            return $order === 'ASC' ? ($a['min_quantity'] <=> $b['min_quantity']) : ($b['min_quantity'] <=> $a['min_quantity']);
        });
        return array_map(function ($item) { return (object) $item; }, $filtered);
    }

    private function table_exists() {
        global $wpdb;
        $result = $wpdb->get_var($wpdb->prepare('SHOW TABLES LIKE %s', $this->table_name));
        return !empty($result) && strtolower($result) === strtolower($this->table_name);
    }
    
    public function output_progress_template() {
        if (!is_cart() && !is_checkout()) return;
        ?>
        <script id="pqsale-progress-template" type="text/template">
            <div class="pqsale-progress-container">
                <div class="pqsale-active-rules">
                    {{#rules}}
                    <div class="pqsale-rule-item {{#is_active}}active{{/is_active}}{{^is_active}}inactive{{/is_active}}">
                        <div class="pqsale-rule-header">
                            <h4>{{name}}</h4>
                            <span class="pqsale-discount-badge">
                                {{#is_percent}}-{{discount_value}}%{{/is_percent}}
                                {{^is_percent}}-{{discount_value_formatted}}đ{{/is_percent}}
                            </span>
                        </div>
                        <div class="pqsale-progress-wrapper">
                            <div class="pqsale-progress-bar">
                                <div class="pqsale-progress-fill" style="width: {{progress_percent}}%">
                                    <span class="pqsale-progress-text">{{current_quantity}}/{{min_quantity}}</span>
                                </div>
                            </div>
                            <p class="pqsale-progress-hint">
                                {{#is_active}}✓ BẠN ĐÃ ĐẠT ĐIỀU KIỆN! GIẢM GIÁ ĐÃ ĐƯỢC ÁP DỤNG.{{/is_active}}
                                {{^is_active}}CHỌN THÊM {{remaining_quantity}} SẢN PHẨM ĐỂ ĐƯỢC GIẢM GIÁ!{{/is_active}}
                            </p>
                        </div>
                    </div>
                    {{/rules}}
                </div>
            </div>
        </script>
        <?php
    }
}
