jQuery(document).ready(function ($) {

    $('body').append('<div id="quick-view-modal" style="display:none;"><div class="quick-view-overlay"></div><div class="quick-view-content"></div></div>');

    function bindQuickViewTriggers() {
        if (quickViewAjax.triggerImage === '1') {
            // Bind to product image
            $('.products .product img').css('cursor', 'pointer').off('click').on('click', function (e) {
                e.preventDefault();
                e.stopPropagation();
                // Disable parent link click:
                $(this).closest('a').on('click', function (e) {
                    e.preventDefault();
                });

                var product_id = $(this).closest('.product').find('.quick-view-button').data('product_id');
                openQuickView(product_id);
            });
        } else {
            // Bind to button
            $('.quick-view-button').off('click').on('click', function (e) {
                e.preventDefault();
                var product_id = $(this).data('product_id');
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
