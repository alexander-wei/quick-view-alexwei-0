jQuery(document).ready(function ($) {

    $('body').append('<div id="quick-view-modal" style="display:none;"><div class="quick-view-overlay"></div><div class="quick-view-content"></div></div>');

    function bindQuickViewTriggers() {
        // Delegate Quick View button clicks
        $('body').off('click', '.quick-view-button').on('click', '.quick-view-button', function (e) {
            console.log('Quick View button clicked, product_id=' + $(this).data('product_id'));
            e.preventDefault();
            var product_id = $(this).data('product_id');
            openQuickView(product_id);
        });

        if (quickViewAjax.triggerImage === '1') {
            // Bind to product image
            $('body').off('click', '.products .product img').on('click', '.products .product img', function (e) {
                console.log('Quick View image clicked, product_id=' + $(this).closest('.product').find('.quick-view-button').data('product_id'));
                e.preventDefault();
                e.stopPropagation();
                // Disable parent link click:
                $(this).closest('a').on('click', function (e) {
                    e.preventDefault();
                });

                var product_id = $(this).closest('.product').find('.quick-view-button').data('product_id');
                openQuickView(product_id);
            });
        }
    }

    function openQuickView(product_id) {
        $.ajax({
            url: quickViewAjax.ajaxurl,
            type: 'POST',
            data: {
                action: 'quick_view_get_product',
                product_id: product_id
            },
            success: function (response) {
                $('#quick-view-modal .quick-view-content').html(response);
                $('#quick-view-modal').fadeIn();
            }
        });
    }

    $('body').on('click', '.quick-view-overlay', function () {
        $('#quick-view-modal').fadeOut();
    });

    // Initial bind
    bindQuickViewTriggers();
});
