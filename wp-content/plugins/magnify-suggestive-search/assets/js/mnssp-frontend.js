jQuery(document).ready(function($) {
    $(".mnssp-icon").on('click', function(){
        $(".mnssp-wrap, .search-input").toggleClass("active");
        $("input[type='search']").focus();
    });

    $('.mnssp-overlay-template.openBtn').on('click', function(){
        $('#mnssp-overlay-template').show();
    });

    $('#mnssp-overlay-template .closebtn').on('click',  function(){
        $('#mnssp-overlay-template').hide();
    });

    $('#mnssp-autocomplete-input').autocomplete({
        source: function(request, response) {
            $.ajax({
                url: mnssp_frontend_object.ajaxurl,
                dataType: 'json',
                data: {
                    action: 'mnssp_autocomplete_search',
                    term: request.term,
                    post_types: $('#mnssp-autocomplete-form input[name="post_type"]').val(),
                    mnssp_autocomplete_nonce: mnssp_frontend_object.nonce
                },
                success: function(data) {
                    response($.map(data, function(item) {
                        return {
                            label: item.label,
                            value: item.value
                        };
                    }));
                }    
            });
        },
        select: function(event, ui) {            
            if (ui.item.value !== '#') {
                window.location.href = ui.item.value;
            } else {
                event.preventDefault();
            }
        },
        minLength: mnssp_frontend_object.minimum_character,
    });
});