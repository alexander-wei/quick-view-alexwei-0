<?php

namespace QuickViewForWC\Public;

use function add_action;
use function remove_action;
use function esc_attr;

/**
 * Replace WooCommerce loop product <a> tag with a <div> for JS interception.
 * https://woocommerce.github.io/code-reference/files/woocommerce-templates-content-product.html#source-view.34
 * 
 * https://stackoverflow.com/questions/56177919/how-does-woocommerce-loop-add-to-cart-link-filter-hook-work-in-depth
 */
class ProductLoopWrapper
{
    public static function init(): void
    {
        // Remove default WooCommerce product link open/close
        // add_action('init', [__CLASS__, 'remove_default_product_link']);

        // Remove Add to Cart button from product loop after WooCommerce has registered its hooks
        // add_action('init', [__CLASS__, 'remove_add_to_cart_from_loop'], 20);
        add_action('template_redirect', [__CLASS__, 'remove_add_to_cart_from_loop'], 20);

        // Add custom wrapper open/close
        add_action('woocommerce_before_shop_loop_item', [__CLASS__, 'custom_product_wrapper_open'], 10);
        add_action('woocommerce_after_shop_loop_item', [__CLASS__, 'custom_product_wrapper_close'], 5);
    }

    public static function remove_add_to_cart_from_loop(): void
    {
        error_log('QuickViewForWC: remove_add_to_cart_from_loop executed');
        remove_action('woocommerce_after_shop_loop_item', 'woocommerce_template_loop_add_to_cart', 10);
        // remove_action('woocommerce_after_shop_loop_item', 'woocommerce_loop_add_to_cart_link', 20);
    }

    public static function remove_default_product_link(): void
    {
        // remove_action('woocommerce_before_shop_loop_item', 'woocommerce_template_loop_product_link_open', 10);
        // remove_action('woocommerce_after_shop_loop_item', 'woocommerce_template_loop_product_link_close', 5);
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
