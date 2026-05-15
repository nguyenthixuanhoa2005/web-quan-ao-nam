<?php
/**
 * Plugin Name: Xuan Hoa Secret Discount
 * Description: Tự động giảm giá 10% khi nhập mã bí mật tại trang thanh toán.
 * Version: 1.0
 * Author: Nguyen Thi Xuan Hoa
 */

// Chặn truy cập trực tiếp vào file
if (!defined('ABSPATH')) exit;

/**
 * 1. Thêm mã giảm giá vào hệ thống WooCommerce mà không cần tạo coupon trong admin
 */
add_action('woocommerce_before_cart_contents', 'xuan_hoa_apply_discount_logic');
add_action('woocommerce_before_checkout_form', 'xuan_hoa_apply_discount_logic');

function xuan_hoa_apply_discount_logic() {
    // Tên mã giảm giá bí mật bạn muốn đặt
    $promo_code = 'XUANHOA_PRO'; 

    // Kiểm tra xem khách hàng có nhập mã này vào ô coupon của Woo không
    if ( isset($_GET['coupon_code']) && $_GET['coupon_code'] == $promo_code ) {
        WC()->session->set('xuan_hoa_custom_discount', true);
    }
}

/**
 * 2. Tính toán lại giá tiền đơn hàng
 */
add_action('woocommerce_cart_calculate_fees', 'xuan_hoa_add_custom_discount_fee');

function xuan_hoa_add_custom_discount_fee() {
    if (is_admin() && !defined('DOING_AJAX')) return;

    // Nếu mã bí mật được kích hoạt (hoặc bạn có thể check trực tiếp coupon trong cart)
    //Check coupon cụ thể
    $applied_coupons = WC()->cart->get_applied_coupons();
    $special_code = 'XUANHOA_PRO';

    if (in_array(strtolower($special_code), array_map('strtolower', $applied_coupons))) {
        
        $discount_percent = 10; // Giảm 10%
        $cart_total = WC()->cart->get_subtotal();
        $discount_amount = ($cart_total * $discount_percent) / 100;

        // Thêm dòng giảm giá vào bảng tổng thanh toán
        WC()->cart->add_fee("Ưu đãi từ Xuan Hoa (10%)", -$discount_amount);
    }
}

/**
 * 3. Hiển thị thông báo chúc mừng cho khách hàng
 */
add_action('woocommerce_before_cart', 'xuan_hoa_display_message');
function xuan_hoa_display_message() {
    $special_code = 'XUANHOA_PRO';
    if (WC()->cart->has_discount($special_code)) {
        wc_print_notice('Chúc mừng! Bạn đã kích hoạt mã giảm giá bí mật của chủ shop Xuân Hoa. Giảm ngay 10%!', 'success');
    }
}