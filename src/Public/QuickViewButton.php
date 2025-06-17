<?php

namespace QuickViewForWC\Public;

use function add_action;
use function esc_attr;
use function esc_html__;
use function printf;
use function get_option;

/**
 * Output Quick View trigger button.
 */
class QuickViewButton
{
    public static function init(): void
    {
        add_action('woocommerce_after_shop_loop_item', [__CLASS__, 'render_button']);
    }

    public static function render_button(): void
    {
        global $product;
        if (! $product) {
            return;
        }
        // Do not render button when Quick View on image is enabled.
        if (get_option('quick_view_trigger_image', 0)) {
            return;
        }

        printf(
            '<button class="quick-view-button" data-product_id="%1$d">%2$s</button>',
            esc_attr($product->get_id()),
            esc_html__('Quick View', 'quick-view-for-woocommerce')
        );
    }
}
