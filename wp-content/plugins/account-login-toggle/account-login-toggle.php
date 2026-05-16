<?php
/**
 * Plugin Name: Account Login Toggle
 * Plugin URI: http://shopthoitrang.local
 * Description: Chuyển đổi icon tài khoản thành nút đăng nhập/đăng kí khi đăng xuất
 * Version: 1.0.0
 * Author: Quỳnh Trăng Shop
 * License: GPL v2 or later
 * Text Domain: account-login-toggle
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Nhúng CSS
add_action( 'wp_enqueue_scripts', function() {
	wp_enqueue_style(
		'alt-style',
		plugin_dir_url( __FILE__ ) . 'css/account-toggle.css',
		array(),
		'1.0.0'
	);
} );

// Nhúng JavaScript
add_action( 'wp_enqueue_scripts', function() {
	wp_enqueue_script(
		'alt-script',
		plugin_dir_url( __FILE__ ) . 'js/account-toggle.js',
		array( 'jquery' ),
		'1.0.0',
		true
	);

	wp_localize_script( 'alt-script', 'altData', array(
		'ajaxurl' => admin_url( 'admin-ajax.php' ),
		'nonce' => wp_create_nonce( 'alt_nonce' ),
	) );
} );

// Thêm body class
add_filter( 'body_class', function( $classes ) {
	$classes[] = is_user_logged_in() ? 'user-logged-in-alt' : 'user-logged-out-alt';
	return $classes;
} );

// Hiển thị nút login khi chưa đăng nhập
add_action( 'wp_footer', function() {
	if ( ! is_user_logged_in() ) {
		?>
		<div class="alt-login-buttons-wrapper">
			<div class="alt-login-buttons">
				<a href="<?php echo esc_url( wp_login_url() ); ?>" class="alt-login-btn">
					🔐 <span>Đăng nhập</span>
				</a>
				<a href="<?php echo esc_url( wp_registration_url() ); ?>" class="alt-register-btn">
					👤 <span>Đăng kí</span>
				</a>
			</div>
		</div>
		<?php
	}
} );

// AJAX check user status
add_action( 'wp_ajax_check_user_status', function() {
	check_ajax_referer( 'alt_nonce', 'nonce' );
	wp_send_json_success( array( 'logged_in' => is_user_logged_in() ) );
} );

add_action( 'wp_ajax_nopriv_check_user_status', function() {
	check_ajax_referer( 'alt_nonce', 'nonce' );
	wp_send_json_success( array( 'logged_in' => is_user_logged_in() ) );
} );
