<?php

namespace QuickViewForWC\Public;

use function add_action;
use function remove_action;
use function esc_attr;

/**
 * Replace WooCommerce loop product <a> tag with a <div> for JS interception.
 */
class ProductLoopWrapper
{
    public static function init(): void
    {
        // Remove default WooCommerce product link open/close
        add_action('init', [__CLASS__, 'remove_default_product_link']);
        // Add custom wrapper open/close
        add_action('woocommerce_before_shop_loop_item', [__CLASS__, 'custom_product_wrapper_open'], 10);
        add_action('woocommerce_after_shop_loop_item', [__CLASS__, 'custom_product_wrapper_close'], 5);
    }

    public static function remove_default_product_link(): void
    {
        remove_action('woocommerce_before_shop_loop_item', 'woocommerce_template_loop_product_link_open', 10);
        remove_action('woocommerce_after_shop_loop_item', 'woocommerce_template_loop_product_link_close', 5);
    }

    public static function custom_product_wrapper_open(): void
    {
        global $product;
        if (! $product) {
            return;
        }
        echo '<a href="' . get_permalink($product->get_id()) . '" class="custom-loop-product" data-product-id="' . esc_attr($product->get_id()) . '">';
        // echo '<div class="custom-loop-product" data-product-id="' . esc_attr($product->get_id()) . '">';
    }

    public static function custom_product_wrapper_close(): void
    {
        // echo '</div>';
        echo '</a>';
    }
}
