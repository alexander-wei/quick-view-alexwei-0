<?php

namespace QuickViewForWC\Public;

use function add_action;
use function esc_attr;
use function esc_html__;

/**
 * Adds a "Quick View" overlay button to the product image in the loop.
 */
class ProductLoopQuickViewOverlay
{
    public static function init(): void
    {
        // Add overlay button inside the product image area
        add_action('woocommerce_before_shop_loop_item_title', [__CLASS__, 'render_overlay_button'], 20);
    }

    public static function render_overlay_button(): void
    {
        global $product;
        if (! $product) {
            return;
        }
        echo '<button class="quick-view-overlay-btn" data-product-id="' . esc_attr($product->get_id()) . '">' .
            esc_html__('Quick View', 'quick-view-for-woocommerce') .
            '</button>';
    }
}
