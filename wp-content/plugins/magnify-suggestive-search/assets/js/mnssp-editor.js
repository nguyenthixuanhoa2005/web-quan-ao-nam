(function($) {
    
    wp.data.subscribe(() => {
        appendButton();
    });

    function appendButton() {
        if (!$('.mnssp-bundle-btn-wrap').length) {
            var mnssp_bundle_btn = `<div class="mnssp-bundle-btn-wrap"><span class="mnssp-bundle-btn">Get All Themes</span></div>`;
            $('.components-accessible-toolbar.edit-post-header-toolbar').append(mnssp_bundle_btn);

            $('.mnssp-bundle-btn-wrap').on('click keypress', function(event) {
                if (event.type === 'click' || (event.type === 'keypress' && (event.key === 'Enter' || event.key === ' '))) {
                    let link = document.createElement('a');
                    link.href = "https://www.themagnifico.net/products/wordpress-theme-bundle";
                    link.target = "_blank";
                    link.rel = "noopener noreferrer";
                    document.body.appendChild(link);
                    link.click();
                    document.body.removeChild(link);
                }
            });
        }
    }

})(jQuery);