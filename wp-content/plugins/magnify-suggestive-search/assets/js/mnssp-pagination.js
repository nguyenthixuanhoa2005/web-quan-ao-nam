jQuery(document).ready(function($) {
    var page = 1;
    var isLoading = false;

    $(window).scroll(function () {
        if ($(window).scrollTop() + $(window).height() >= $(document).height() - 200 && !isLoading) {            
            loadMoreProducts();
        }
    });

    function productsAjax( endCursor, templateSearch, collection, actionValue ) {

        var progress = 0;
        var progressInterval = setInterval(function() {
            progress += 10;
            if (progress >= 100) {
                clearInterval(progressInterval);
            }
        }, 300);

        $.ajax({
            url: mnssp_pagination_object.ajaxurl,
            type: 'POST',
            data: {
                action: 'mnssp_get_filtered_products',
                cursor: endCursor,
                search: templateSearch,
                collection: collection,
                mnssp_pagination_nonce: mnssp_pagination_object.nonce
            },
            success: function (response) {

                clearInterval(progressInterval);
                jQuery('.mnssp-loader').hide();
                jQuery('.mnssp-loader-overlay').hide();

                if (response.content) {

                    isLoading = false;

                    if ( actionValue != 'load' ) {
                        jQuery('.mnssp-templates-grid.mnssp-main-grid').empty();
                    }
                    jQuery('.mnssp-templates-grid.mnssp-main-grid').append(response.content);

                    const hasNextPage = response?.pagination?.hasNextPage;
                    const endCursor = response?.pagination?.endCursor;

                    jQuery('[name="mnssp-end-cursor"]').val(endCursor);
                    if (!hasNextPage) {
                        jQuery('[name="mnssp-end-cursor"]').val('');
                        isLoading = true
                    }
                }
            },
            error: function () {
                
                clearInterval(progressInterval);
                jQuery('.mnssp-loader').hide();
                jQuery('.mnssp-loader-overlay').hide();

                console.log('Error loading products');
            }
        });
    }

    function loadMoreProducts() {
        isLoading = true;
        page++;

        const endCursor = jQuery('[name="mnssp-end-cursor"]').val();
        const templateSearch = jQuery('[name="mnssp-templates-search"]').val();
        const collection = jQuery('[name="mnssp-collections"]').val();

        productsAjax( endCursor, templateSearch, collection, 'load' );
    }

    function debounce(func, delay) {
        let timeoutId;
        return function() {
            const context = this;
            const args = arguments;
            clearTimeout(timeoutId);
            timeoutId = setTimeout(() => {
                func.apply(context, args);
            }, delay);
        };
    }

    jQuery('#mnssp-collections').on('change', function() {

        jQuery('.mnssp-loader').show();
        jQuery('.mnssp-loader-overlay').show();

        productsAjax( '', '', jQuery(this).val(), 'category' );
    });

    $('body').on("input", '[name="mnssp-templates-search"]', debounce(function (event) {

        const templateSearch = $('[name="mnssp-templates-search"]').val();

        jQuery('.mnssp-loader').show();
        jQuery('.mnssp-loader-overlay').show();
        
        productsAjax( '', templateSearch, '', 'search' );
        
    }, 1000));
});