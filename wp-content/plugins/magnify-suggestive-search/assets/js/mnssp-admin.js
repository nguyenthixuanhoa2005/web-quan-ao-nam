jQuery(document).ready(function($) {
    $('#mnssp_icon_picker').iconpicker({
        placement: 'bottomRight',
        hideOnSelect: true,
        templates: {
            popover: '<div class="iconpicker-popover popover"><div class="arrow"></div><div class="popover-title"></div><div class="popover-content"></div></div>',
            iconpickerItem: '<a role="button" href="#" class="iconpicker-item"><i></i></a>',
        }
    });

    $('#mnssp-form').on('submit', function(e) {
        e.preventDefault();

        var formData = $(this).serialize();

        $.ajax({
            url: mnssp_object.ajaxurl,
            type: 'POST',
            data: {
                action: 'mnssp_save_search_bar',
                form_data: formData,
                mnssp_search_bar_nonce: mnssp_object.nonce
            },
            success: function(response) {
                if (response.success) {
                    window.location.href = mnssp_object.redirect_url;
                } else {
                    alert('Error: ' + response.data.message);
                }
            },
            error: function() {
                alert('An unexpected error occurred.');
            }
        });
    });

    $('.nav-tab').click(function(e) {
        e.preventDefault();
        $('.nav-tab').removeClass('nav-tab-active');
        $(this).addClass('nav-tab-active');

        var tabId = $(this).attr('href');
        $('.tab-content').removeClass('active');
        $(tabId).addClass('active');
    });


    $('.color-field').wpColorPicker();


    $('#mnssp_search_bar_width').change(function() {
        var selectedValue = $(this).val();
        if (selectedValue === 'custom') {
            $('#mnssp_custom_width_row').show();
        } else {
            $('#mnssp_custom_width_row').hide();
        }
    });

    $('#mnssp_search_bar_height').change(function() {
        var selectedValue = $(this).val();
        if (selectedValue === 'custom') {
            $('#mnssp_custom_height_row').show();
        } else {
            $('#mnssp_custom_height_row').hide();
        }
    });
});


