jQuery(document).ready(function ($) {

    $('body').append('<div id="quick-view-modal" style="display:none;"><div class="quick-view-overlay"></div><div class="quick-view-content"></div></div>');

    function bindQuickViewTriggers() {
        // Delegate clicks for button and image (now always on [data-product-id])
        $('body').off('click', '[data-product-id]').on('click', '[data-product-id]', function (e) {
            console.log('Quick View clicked: ' + $(this).attr('data-product-id'));
            e.preventDefault();
            e.stopPropagation();
            var product_id = $(this).attr('data-product-id');
            openQuickView(product_id);
            return false;
        });
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
