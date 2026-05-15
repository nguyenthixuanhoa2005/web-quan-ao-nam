/**
 * Admin JavaScript - Handle settings management
 */
jQuery(document).ready(function($) {
    const PQSaleAdmin = {
        init: function() {
            this.bindEvents();
        },
        
        bindEvents: function() {
            // Edit setting button
            $(document).on('click', '.edit-setting', this.editSetting.bind(this));
            
            // Delete setting button
            $(document).on('click', '.delete-setting', this.deleteSetting.bind(this));
            
            // Form reset
            $('.pqsale-settings-form').on('reset', this.resetForm.bind(this));
        },
        
        editSetting: function(e) {
            e.preventDefault();
            const btn = $(e.currentTarget);
            const data = btn.data();
            
            // Fill form with data from button
            $('#setting_id').val(data.id);
            $('#setting_name').val(data.name);
            $('#min_quantity').val(data.min_quantity);
            $('#discount_type').val(data.discount_type);
            $('#discount_value').val(data.discount_value);
            $('#enabled_from').val(data.enabled_from);
            $('#enabled_to').val(data.enabled_to);
            $('#max_usage').val(data.max_usage);
            
            // Update UI
            $('#form-title').text('Sửa Cài Đặt: ' + data.name);
            $('#submit-button').text('Cập Nhật Cài Đặt');
            $('.discount_value_label').text(data.discount_type === 'percent' ? 'Phần Trăm (%)' : 'Số Tiền (đ)');
            
            // Scroll to form
            $('html, body').animate({
                scrollTop: $("#pqsale-form").offset().top - 100
            }, 500);
        },
        
        deleteSetting: function(e) {
            e.preventDefault();
            
            if (!confirm('Bạn chắc chắn muốn xóa cài đặt này?')) {
                return;
            }
            
            const id = $(e.currentTarget).data('id');
            
            $.ajax({
                type: 'POST',
                url: ajaxurl,
                data: {
                    action: 'pqsale_delete_setting',
                    id: id,
                    nonce: pqsaleAdmin.nonce
                },
                success: function(response) {
                    if (response.success) {
                        location.reload();
                    } else {
                        alert('Lỗi: ' + response.data);
                    }
                },
                error: function() {
                    alert('Lỗi khi xóa cài đặt');
                }
            });
        },
        
        resetForm: function() {
            $('#setting_id').val('');
            $('#form-title').text('Thêm Cài Đặt Sale');
            $('#submit-button').text('Lưu Cài Đặt');
            $('.discount_value_label').text('Phần Trăm (%)');
        }
    };
    
    PQSaleAdmin.init();
});
