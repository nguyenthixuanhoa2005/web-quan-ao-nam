/* Account Login Toggle - Đơn giản */

(function($) {
	'use strict';

	$(document).ready(function() {
		// Cập nhật trạng thái user
		function updateDisplay() {
			$.ajax({
				type: 'POST',
				url: altData.ajaxurl,
				data: {
					action: 'check_user_status',
					nonce: altData.nonce
				},
				success: function(response) {
					if ( response.success ) {
						var isLoggedIn = response.data.logged_in;
						if ( isLoggedIn ) {
							$('body').removeClass('user-logged-out-alt').addClass('user-logged-in-alt');
							$('.ast-header-account').show();
							$('.alt-login-buttons-wrapper').hide();
						} else {
							$('body').removeClass('user-logged-in-alt').addClass('user-logged-out-alt');
							$('.ast-header-account').hide();
							$('.alt-login-buttons-wrapper').show();
						}
					}
				}
			});
		}

		// Cập nhật khi page load
		updateDisplay();

		// Cập nhật mỗi 5 giây
		setInterval(updateDisplay, 5000);
	});

})(jQuery);
