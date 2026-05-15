<?php
/**
 * Plugin Name: Xuan Hoa Payment Timeout
 * Description: Đếm ngược thời gian thanh toán và tự động hủy đơn sau 15 phút.
 * Version: 1.1
 * Author: Nguyen Thi Xuan Hoa
 */

if (!defined('ABSPATH')) exit;

// 1. Thiết lập thời gian chờ (Ví dụ: 15 phút = 900 giây)
define('XH_PAYMENT_TIMEOUT', 900); 

// 2. Chèn giao diện đồng hồ vào trang Cảm ơn / Thanh toán
add_action('woocommerce_thankyou', 'xh_display_countdown_timer', 10);
function xh_display_countdown_timer($order_id) {
    $order = wc_get_order($order_id);
    
    // Chỉ hiện nếu đơn hàng đang ở trạng thái Chờ thanh toán (Pending)
    if ($order->has_status('pending')) {
        ?>
        <div id="xh-payment-countdown" style="background: #fff3cd; border: 1px solid #ffeeba; padding: 15px; text-align: center; margin-bottom: 20px; border-radius: 5px;">
            <h4 style="color: #856404; margin-bottom: 10px;">⏳ Vui lòng thanh toán trong:</h4>
            <div id="countdown-clock" style="font-size: 24px; font-weight: bold; color: #721c24;">15:00</div>
            <p style="font-size: 13px; margin-top: 5px;">Sau thời gian này, mã QR và đơn hàng sẽ tự động bị hủy.</p>
        </div>

        <script>
        (function($) {
            var seconds = <?php echo XH_PAYMENT_TIMEOUT; ?>;
            var timer = setInterval(function() {
                var mins = Math.floor(seconds / 60);
                var secs = seconds % 60;
                document.getElementById('countdown-clock').innerHTML = 
                    (mins < 10 ? "0" : "") + mins + ":" + (secs < 10 ? "0" : "") + secs;
                
                if (seconds <= 0) {
                    clearInterval(timer);
                    // Gọi AJAX để hủy đơn trên Server
                    $.ajax({
                        url: '<?php echo admin_url('admin-ajax.php'); ?>',
                        type: 'POST',
                        data: {
                            action: 'xh_cancel_order_timeout',
                            order_id: '<?php echo $order_id; ?>'
                        },
                        success: function() {
                            alert('Đơn hàng đã hết hạn thanh toán và bị hủy.');
                            location.reload(); // Load lại trang để mất mã QR
                        }
                    });
                }
                seconds--;
            }, 1000);
        })(jQuery);
        </script>
        <?php
    }
}

// 3. Xử lý hủy đơn hàng phía Server (PHP)
add_action('wp_ajax_xh_cancel_order_timeout', 'xh_cancel_order_timeout_callback');
add_action('wp_ajax_nopriv_xh_cancel_order_timeout', 'xh_cancel_order_timeout_callback');

function xh_cancel_order_timeout_callback() {
    $order_id = $_POST['order_id'];
    if ($order_id) {
        $order = wc_get_order($order_id);
        if ($order->has_status('pending')) {
            $order->update_status('cancelled', 'Đã hết thời gian thanh toán (Auto-timeout).');
            wp_send_json_success('Order cancelled');
        }
    }
    wp_die();
}