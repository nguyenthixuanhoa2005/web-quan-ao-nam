<?php

namespace PQSALE;

/**
 * Admin Class - Handle admin settings page
 */
class Admin {
    
    private $table_name;
    private $option_key;
    
    public function __construct() {
        global $wpdb;
        $this->table_name = $wpdb->prefix . 'pqsale_settings';
        $this->option_key = 'pqsale_settings_fallback';
    }
    
    /**
     * Render admin settings page
     */
    public function render_page() {
        global $wpdb;

        $this->ensure_table_ready();
        
        // Handle form submission
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'pqsale_save') {
            check_admin_referer('pqsale_nonce');
            $save_result = $this->save_settings($_POST);

            if ($save_result === true) {
                echo '<div class="notice notice-success"><p>Cài đặt đã được lưu thành công!</p></div>';
            } else {
                echo '<div class="notice notice-error"><p>Lưu cài đặt thất bại: ' . esc_html($save_result) . '</p></div>';
            }
        }
        
        // Get all settings (DB first, fallback to options)
        $settings = $this->get_all_settings();

        if (!empty($wpdb->last_error)) {
            echo '<div class="notice notice-error"><p>Lỗi đọc dữ liệu plugin: ' . esc_html($wpdb->last_error) . '</p></div>';
        }
        
        ?>
        <div class="wrap">
            <h1>Thiết Lập Sale Theo Số Lượng Sản Phẩm</h1>
            
            <div class="pqsale-container">
                <div class="pqsale-form-wrapper">
                    <h2 id="form-title">Thêm Cài Đặt Sale</h2>
                    <form method="POST" class="pqsale-settings-form" id="pqsale-form">
                        <?php wp_nonce_field('pqsale_nonce'); ?>
                        <input type="hidden" name="action" value="pqsale_save">
                        <input type="hidden" name="setting_id" id="setting_id" value="">
                        
                        <div class="form-group">
                            <label for="setting_name">Tên Cài Đặt:</label>
                            <input type="text" id="setting_name" name="setting_name" required placeholder="VD: Sale 3 sản phẩm">
                        </div>
                        
                        <div class="form-group">
                            <label for="min_quantity">Số Lượng Sản Phẩm Tối Thiểu:</label>
                            <input type="number" id="min_quantity" name="min_quantity" min="1" required placeholder="VD: 3">
                        </div>
                        
                        <div class="form-group">
                            <label for="discount_type">Loại Giảm Giá:</label>
                            <select id="discount_type" name="discount_type" onchange="this.form.querySelector('.discount_value_label').innerText = this.value === 'percent' ? 'Phần Trăm (%)' : 'Số Tiền (đ)'">
                                <option value="percent">Phần Trăm (%)</option>
                                <option value="fixed">Số Tiền (đ)</option>
                            </select>
                        </div>
                        
                        <div class="form-group">
                            <label class="discount_value_label" for="discount_value">Phần Trăm (%):</label>
                            <input type="number" id="discount_value" name="discount_value" min="0" step="0.01" required placeholder="VD: 10">
                        </div>
                        
                        <div class="form-group">
                            <label for="enabled_from">Bắt Đầu Từ:</label>
                            <input type="datetime-local" id="enabled_from" name="enabled_from">
                        </div>
                        
                        <div class="form-group">
                            <label for="enabled_to">Kết Thúc:</label>
                            <input type="datetime-local" id="enabled_to" name="enabled_to">
                        </div>
                        
                        <div class="form-group">
                            <label for="max_usage">Số Lần Sử Dụng Tối Đa (0 = Không Giới Hạn):</label>
                            <input type="number" id="max_usage" name="max_usage" min="0" placeholder="VD: 100">
                        </div>
                        
                        <div class="form-actions">
                            <button type="submit" class="button button-primary" id="submit-button">Lưu Cài Đặt</button>
                            <button type="reset" class="button" id="reset-button">Hủy / Làm mới</button>
                        </div>
                    </form>
                </div>
                
                <div class="pqsale-list-wrapper">
                    <h2>Danh Sách Cài Đặt</h2>
                    <?php if (!empty($settings)) : ?>
                        <table class="widefat striped">
                            <thead>
                                <tr>
                                    <th>Tên</th>
                                    <th>Số Lượng Tối Thiểu</th>
                                    <th>Loại Giảm</th>
                                    <th>Giá Trị</th>
                                    <th>Từ Ngày</th>
                                    <th>Đến Ngày</th>
                                    <th>Lần Dùng</th>
                                    <th>Hành Động</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($settings as $setting) : ?>
                                    <tr>
                                        <td><strong><?php echo esc_html($setting->name); ?></strong></td>
                                        <td><?php echo (int)$setting->min_quantity; ?></td>
                                        <td><?php echo $setting->discount_type === 'percent' ? '%' : 'đ'; ?></td>
                                        <td><?php echo (float)$setting->discount_value; ?></td>
                                        <td><?php echo $setting->enabled_from ? date('d/m/Y H:i', strtotime($setting->enabled_from)) : '-'; ?></td>
                                        <td><?php echo $setting->enabled_to ? date('d/m/Y H:i', strtotime($setting->enabled_to)) : '-'; ?></td>
                                        <td><?php echo (int)$setting->usage_count; ?> / <?php echo $setting->max_usage > 0 ? (int)$setting->max_usage : '∞'; ?></td>
                                        <td>
                                            <a href="#" class="edit-setting button button-small" 
                                               data-id="<?php echo (int)$setting->id; ?>"
                                               data-name="<?php echo esc_attr($setting->name); ?>"
                                               data-min_quantity="<?php echo (int)$setting->min_quantity; ?>"
                                               data-discount_type="<?php echo esc_attr($setting->discount_type); ?>"
                                               data-discount_value="<?php echo (float)$setting->discount_value; ?>"
                                               data-enabled_from="<?php echo $setting->enabled_from ? date('Y-m-d\TH:i', strtotime($setting->enabled_from)) : ''; ?>"
                                               data-enabled_to="<?php echo $setting->enabled_to ? date('Y-m-d\TH:i', strtotime($setting->enabled_to)) : ''; ?>"
                                               data-max_usage="<?php echo (int)$setting->max_usage; ?>">Sửa</a>
                                            <a href="#" class="delete-setting button button-small button-link-delete" data-id="<?php echo (int)$setting->id; ?>">Xóa</a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    <?php else : ?>
                        <p>Chưa có cài đặt sale nào. Hãy thêm cài đặt đầu tiên!</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        
        <style>
            .pqsale-container {
                display: grid;
                grid-template-columns: 350px 1fr;
                gap: 20px;
                margin-top: 20px;
                align-items: start;
            }
            
            .pqsale-form-wrapper,
            .pqsale-list-wrapper {
                background: white;
                padding: 20px;
                border: 1px solid #ccd0d4;
                box-shadow: 0 1px 1px rgba(0,0,0,.04);
            }
            
            .pqsale-form-wrapper h2, .pqsale-list-wrapper h2 {
                margin-top: 0;
                padding-bottom: 10px;
                border-bottom: 1px solid #eee;
            }
            
            .form-group {
                margin-bottom: 15px;
            }
            
            .form-group label {
                display: block;
                margin-bottom: 5px;
                font-weight: 600;
            }
            
            .form-group input,
            .form-group select {
                width: 100%;
                padding: 8px;
                border: 1px solid #ddd;
                border-radius: 4px;
                font-size: 14px;
            }
            
            .form-actions {
                margin-top: 20px;
                display: flex;
                gap: 10px;
                border-top: 1px solid #eee;
                padding-top: 15px;
            }
            
            .delete-setting {
                color: #a00 !important;
            }
            
            .delete-setting:hover {
                color: #dc3232 !important;
            }
            
            @media (max-width: 1024px) {
                .pqsale-container {
                    grid-template-columns: 1fr;
                }
            }
        </style>
        <?php
    }
    
    /**
     * Save settings to database
     */
    private function save_settings($post_data) {
        global $wpdb;
        
        $id = (int)($post_data['setting_id'] ?? 0);
        $name = sanitize_text_field($post_data['setting_name'] ?? '');
        $min_quantity = (int)($post_data['min_quantity'] ?? 1);
        $discount_type = in_array($post_data['discount_type'] ?? '', ['percent', 'fixed']) ? $post_data['discount_type'] : 'percent';
        $discount_value = (float)($post_data['discount_value'] ?? 0);
        $enabled_from = $this->to_mysql_datetime($post_data['enabled_from'] ?? '');
        $enabled_to = $this->to_mysql_datetime($post_data['enabled_to'] ?? '');
        $max_usage = (int)($post_data['max_usage'] ?? 0);

        if (empty($name)) {
            return 'Tên cài đặt không được để trống.';
        }

        $payload = [
            'name' => $name,
            'min_quantity' => $min_quantity,
            'discount_type' => $discount_type,
            'discount_value' => $discount_value,
            'enabled_from' => $enabled_from,
            'enabled_to' => $enabled_to,
            'max_usage' => $max_usage,
        ];

        if ($id > 0) {
            // Update
            $result = $wpdb->update($this->table_name, $payload, ['id' => $id], [
                '%s', '%d', '%s', '%f', '%s', '%s', '%d',
            ], ['%d']);
            
            $this->update_fallback_storage($id, $payload);
            
            return $result !== false ? true : 'Không thể cập nhật cơ sở dữ liệu.';
        } else {
            // Insert
            $inserted = $wpdb->insert($this->table_name, $payload, [
                '%s', '%d', '%s', '%f', '%s', '%s', '%d',
            ]);

            $db_id = $wpdb->insert_id;
            $fallback_saved = $this->save_to_fallback_storage($payload, $db_id);

            if ($inserted === false && !$fallback_saved) {
                return !empty($wpdb->last_error) ? $wpdb->last_error : 'Không thể lưu vào cơ sở dữ liệu.';
            }

            return true;
        }
    }

    /**
     * AJAX: Delete setting
     */
    public function ajax_delete_setting() {
        check_ajax_referer('pqsale_nonce', 'nonce');
        
        if (!current_user_can('manage_options')) {
            wp_send_json_error('Bạn không có quyền thực hiện hành động này.');
        }
        
        $id = (int)($_POST['id'] ?? 0);
        if ($id <= 0) {
            wp_send_json_error('ID không hợp lệ.');
        }
        
        global $wpdb;
        $deleted = $wpdb->delete($this->table_name, ['id' => $id], ['%d']);
        
        // Remove from fallback
        $this->remove_from_fallback_storage($id);
        
        if ($deleted !== false) {
            wp_send_json_success('Xóa thành công.');
        } else {
            wp_send_json_error('Lỗi khi xóa từ cơ sở dữ liệu.');
        }
    }

    private function remove_from_fallback_storage($id) {
        $items = get_option($this->option_key, []);
        if (!is_array($items)) return;
        
        $items = array_filter($items, function($item) use ($id) {
            return (int)($item['id'] ?? 0) !== (int)$id;
        });
        
        update_option($this->option_key, array_values($items), false);
    }

    private function update_fallback_storage($id, $payload) {
        $items = get_option($this->option_key, []);
        if (!is_array($items)) return;
        
        $updated = false;
        foreach ($items as &$item) {
            if ((int)($item['id'] ?? 0) === (int)$id) {
                $item['name'] = $payload['name'];
                $item['min_quantity'] = (int)$payload['min_quantity'];
                $item['discount_type'] = $payload['discount_type'];
                $item['discount_value'] = (float)$payload['discount_value'];
                $item['enabled_from'] = $payload['enabled_from'];
                $item['enabled_to'] = $payload['enabled_to'];
                $item['max_usage'] = (int)$payload['max_usage'];
                $updated = true;
                break;
            }
        }
        
        if ($updated) {
            update_option($this->option_key, $items, false);
        }
    }

    private function get_all_settings() {
        global $wpdb;

        $rows = [];

        if ($this->table_exists()) {
            $order_column = $this->column_exists('created_at') ? 'created_at' : 'id';
            $rows = $wpdb->get_results("SELECT * FROM {$this->table_name} ORDER BY {$order_column} DESC");
        }

        // If DB has rows, return them
        if (!empty($rows)) {
            return $rows;
        }

        // Fallback to options if DB is empty or doesn't exist
        $fallback = get_option($this->option_key, []);
        if (!is_array($fallback) || empty($fallback)) {
            return [];
        }

        usort($fallback, function ($a, $b) {
            return ((int) ($b['id'] ?? 0)) <=> ((int) ($a['id'] ?? 0));
        });

        return array_map(function ($item) {
            return (object) [
                'id' => (int) ($item['id'] ?? 0),
                'name' => (string) ($item['name'] ?? ''),
                'min_quantity' => (int) ($item['min_quantity'] ?? 1),
                'discount_type' => (string) ($item['discount_type'] ?? 'percent'),
                'discount_value' => (float) ($item['discount_value'] ?? 0),
                'enabled_from' => $item['enabled_from'] ?? null,
                'enabled_to' => $item['enabled_to'] ?? null,
                'max_usage' => (int) ($item['max_usage'] ?? 0),
                'usage_count' => (int) ($item['usage_count'] ?? 0),
                'created_at' => $item['created_at'] ?? null,
            ];
        }, $fallback);
    }

    private function save_to_fallback_storage($payload, $db_id = 0) {
        $items = get_option($this->option_key, []);
        if (!is_array($items)) {
            $items = [];
        }

        // If updating an existing item in fallback
        if ($db_id > 0) {
            $found = false;
            foreach ($items as &$item) {
                if ((int)($item['id'] ?? 0) === (int)$db_id) {
                    $item['name'] = $payload['name'];
                    $item['min_quantity'] = (int)$payload['min_quantity'];
                    $item['discount_type'] = $payload['discount_type'];
                    $item['discount_value'] = (float)$payload['discount_value'];
                    $item['enabled_from'] = $payload['enabled_from'];
                    $item['enabled_to'] = $payload['enabled_to'];
                    $item['max_usage'] = (int)$payload['max_usage'];
                    $found = true;
                    break;
                }
            }
            if ($found) {
                return update_option($this->option_key, $items, false);
            }
        }

        // Otherwise add new
        $ids = array_map(function ($row) {
            return (int) ($row['id'] ?? 0);
        }, $items);
        $next_id = !empty($ids) ? (max($ids) + 1) : 1;

        $items[] = [
            'id' => $db_id > 0 ? (int) $db_id : $next_id,
            'name' => $payload['name'],
            'min_quantity' => (int) $payload['min_quantity'],
            'discount_type' => $payload['discount_type'],
            'discount_value' => (float) $payload['discount_value'],
            'enabled_from' => $payload['enabled_from'],
            'enabled_to' => $payload['enabled_to'],
            'max_usage' => (int) $payload['max_usage'],
            'usage_count' => 0,
            'created_at' => current_time('mysql'),
        ];

        return update_option($this->option_key, $items, false);
    }

    private function ensure_table_ready() {
        global $wpdb;

        if (!$this->table_exists()) {
            $main = new Main();
            $main->activate_plugin();
        }
    }

    private function column_exists($column_name) {
        global $wpdb;

        if (!$this->table_exists()) return false;

        $column = $wpdb->get_var($wpdb->prepare(
            "SHOW COLUMNS FROM {$this->table_name} LIKE %s",
            $column_name
        ));

        return !empty($column);
    }

    private function table_exists() {
        global $wpdb;
        $result = $wpdb->get_var($wpdb->prepare('SHOW TABLES LIKE %s', $this->table_name));
        return !empty($result) && strtolower($result) === strtolower($this->table_name);
    }

    private function to_mysql_datetime($datetime_value) {
        $datetime_value = sanitize_text_field($datetime_value);

        if (empty($datetime_value)) {
            return null;
        }

        $normalized = str_replace('T', ' ', $datetime_value);
        $timestamp = strtotime($normalized);

        if ($timestamp === false) {
            return null;
        }

        return date('Y-m-d H:i:s', $timestamp);
    }
}
