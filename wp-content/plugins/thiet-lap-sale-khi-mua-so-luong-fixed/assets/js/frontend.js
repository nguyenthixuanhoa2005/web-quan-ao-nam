/**
 * Frontend JavaScript - Handle cart updates and progress bar
 */
jQuery(document).ready(function($) {
    const PQSALE = {
        isUpdating: false,

        init: function() {
            this.bindEvents();
            // On first load, the PHP-rendered container may be inside a grid column.
            // Move it to the correct position immediately.
            this.repositionExisting();
            this.updateProgress();
        },

        repositionExisting: function() {
            const container = $('.pqsale-progress-container');
            if (!container.length) return;
            PQSALE.placeContainer(container.first());
            if (container.length > 1) container.not(':first').remove();
        },
        
        bindEvents: function() {
            // Standard WooCommerce AJAX events - Astra triggers these
            $(document.body).on('updated_wc_div updated_cart_totals wc_fragments_refreshed', function() {
                PQSALE.updateProgress();
            });

            // Hide progress when items are added to cart via AJAX
            $(document.body).on('added_to_cart', function(event, fragments, cart_hash, $button) {
                $('.pqsale-progress-container').fadeOut(200, function() { $(this).remove(); });
            });
            
            // Listen for checkbox changes (Selection/Unselection)
            $(document).on('change', '.woocommerce-cart-form input[type="checkbox"]', function() {
                const $checkbox = $(this);
                const name = $checkbox.attr('name') || '';
                
                // Identify item selection checkboxes
                if (name.includes('selected') || name.includes('cart_item') || $checkbox.hasClass('cart-item-checkbox') || $checkbox.closest('.product-selection').length) {
                    PQSALE.syncCheckbox($checkbox);
                }
            });

            // Astra quantity changes listener
            $(document).on('change', '.woocommerce-cart-form input.qty', function() {
                // Wait for Astra's AJAX to finish before fetching progress
                setTimeout(function() {
                    PQSALE.updateProgress();
                }, 1200);
            });
        },

        syncCheckbox: function($checkbox) {
            let cartKey = $checkbox.data('cart-key') || 
                          $checkbox.attr('data-cart-key') || 
                          $checkbox.data('cart_item_key') ||
                          $checkbox.val();
            
            // Astra specific row detection
            if (!cartKey || cartKey.length < 10) {
                const $row = $checkbox.closest('.cart_item');
                cartKey = $row.data('cart_id') || $row.attr('data-cart_id') || $row.find('.remove').data('cart_item_key');
            }
                            
            if (!cartKey) return;

            const selected = $checkbox.is(':checked') ? 'yes' : 'no';

            // Immediate visual feedback
            const $row = $checkbox.closest('.cart_item');
            $row.css('opacity', selected === 'no' ? '0.4' : '1');

            $.ajax({
                type: 'POST',
                url: pqsaleData.ajaxurl,
                data: {
                    action: 'update_item_selected_status',
                    nonce: pqsaleData.nonce,
                    cart_key: cartKey,
                    selected: selected
                },
                success: function(response) {
                    if (response.success) {
                        // Refresh cart fragments so totals and progress stay in sync.
                        $(document.body).trigger('wc_fragment_refresh');
                        $(document.body).trigger('update_checkout');
                    }
                }
            });
        },
        
        updateProgress: function() {
            // Only fetch progress on cart page (avoid showing on other pages)
            if (!$('body').hasClass('woocommerce-cart') && $('.woocommerce-cart-form').length === 0) {
                $('.pqsale-progress-container').remove();
                return;
            }

            if (this.isUpdating) return;
            this.isUpdating = true;

            $.ajax({
                type: 'POST',
                url: pqsaleData.ajaxurl,
                data: {
                    action: 'pqsale_get_progress',
                    nonce: pqsaleData.nonce
                },
                success: function(response) {
                    if (response.success && response.data) {
                        PQSALE.renderProgress(response.data);
                    }
                },
                complete: function() {
                    PQSALE.isUpdating = false;
                }
            });
        },
        
        /**
         * Find the best anchor to place the progress bar ABOVE the entire cart layout.
         * Astra/WooCommerce uses a grid/flex row for the two-column layout — we must
         * prepend inside the outermost .woocommerce div to escape that grid.
         */
        getCartWrapper: function() {
            // Walk up from the cart form to find the outermost woo wrapper
            const $form = $('.woocommerce-cart-form');
            if (!$form.length) return null;
            // Try ancestors: .woocommerce inside main content area
            let $wrapper = $form.closest('.woocommerce');
            if ($wrapper.length) return $wrapper.first();
            return null;
        },

        placeContainer: function($container) {
            const $wrapper = PQSALE.getCartWrapper();
            if ($wrapper && $wrapper.length) {
                // Prepend inside the wrapper so it sits above the two-column grid row
                const $first = $wrapper.children(':not(script):not(style)').first();
                if (!$first.hasClass('pqsale-progress-container')) {
                    $wrapper.prepend($container);
                }
            } else {
                // Fallback: before the cart form
                const $form = $('.woocommerce-cart-form');
                if ($form.length) {
                    $form.before($container);
                }
            }
        },

        renderProgress: function(data) {
            // Only render on cart page
            if (!$('body').hasClass('woocommerce-cart') && $('.woocommerce-cart-form').length === 0) {
                $('.pqsale-progress-container').remove();
                return;
            }

            let container = $('.pqsale-progress-container');
            
            if (data.length === 0) {
                container.fadeOut(300, function() { $(this).remove(); });
                return;
            }
            
            let html = '<div class="pqsale-active-rules">';
            
            data.forEach(function(rule) {
                const progressPercent = Math.min(100, (rule.current_quantity / rule.min_quantity) * 100);
                const remaining = rule.min_quantity - rule.current_quantity;
                
                let discountText = rule.discount_type === 'percent' ? 
                    '-' + parseFloat(rule.discount_value).toFixed(0) + '%' : 
                    '-' + PQSALE.formatCurrency(parseFloat(rule.discount_value));
                
                let hintText = rule.is_active ? 
                    '✓ BẠN ĐÃ ĐẠT ĐIỀU KIỆN! GIẢM GIÁ ĐÃ ĐƯỢC ÁP DỤNG.' : 
                    'CHỌN THÊM ' + remaining + ' SẢN PHẨM ĐỂ ĐƯỢC GIẢM GIÁ!';
                
                html += `
                    <div class="pqsale-rule-item ${rule.is_active ? 'active' : 'inactive'}">
                        <div class="pqsale-rule-header">
                            <h4>${PQSALE.escapeHtml(rule.name)}</h4>
                            <span class="pqsale-discount-badge">${discountText}</span>
                        </div>
                        
                        <div class="pqsale-progress-wrapper">
                            <div class="pqsale-progress-bar">
                                <div class="pqsale-progress-fill" style="width: ${progressPercent}%">
                                    <span class="pqsale-progress-text">${rule.current_quantity}/${rule.min_quantity}</span>
                                </div>
                            </div>
                            <p class="pqsale-progress-hint">${hintText}</p>
                        </div>
                    </div>
                `;
            });
            
            html += '</div>';
            
            if (container.length) {
                // Remove duplicates
                if (container.length > 1) {
                    container.not(':first').remove();
                    container = container.first();
                }
                container.html(html).show();
                // Re-check position every render in case WC re-built the DOM
                PQSALE.placeContainer(container);
            } else {
                const newContainer = $('<div class="pqsale-progress-container"></div>').html(html);
                PQSALE.placeContainer(newContainer);
            }
        },
        
        formatCurrency: function(amount) {
            return new Intl.NumberFormat('vi-VN', {
                style: 'decimal',
                minimumFractionDigits: 0,
                maximumFractionDigits: 0
            }).format(amount);
        },
        
        escapeHtml: function(text) {
            const div = document.createElement('div');
            div.textContent = text;
            return div.innerHTML;
        }
    };
    
    PQSALE.init();
});
