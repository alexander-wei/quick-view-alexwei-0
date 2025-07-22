jQuery(document).ready(function ($) {

    // Modal markup (unchanged)
    $('body').append('<div id="quick-view-modal" style="display:none;"><div class="quick-view-overlay"></div><div class="quick-view-content"></div></div>');

    function bindQuickViewTriggers() {
        // No longer assign data-product-id to product image links
        // Only the overlay button needs data-product-id

        // Remove previous click handlers and only bind to the overlay button
        $('body').off('click', '.quick-view-overlay-btn').on('click', '.quick-view-overlay-btn', function (e) {
            e.preventDefault();
            e.stopPropagation();
            var product_id = $(this).attr('data-product-id');
            if (product_id) {
                openQuickView(product_id);
            }
            return false;
        });

        // No interception of product image link clicks; default navigation is preserved
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
