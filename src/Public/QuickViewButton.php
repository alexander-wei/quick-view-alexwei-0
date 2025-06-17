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

        // Remove default WooCommerce link open/close if image trigger is enabled, then add our own
        if (get_option('quick_view_trigger_image', 0)) {
            remove_action('woocommerce_template_loop_product_link_open', 'woocommerce_template_loop_product_link_open', 10);
            remove_action('woocommerce_template_loop_product_link_close', 'woocommerce_template_loop_product_link_close', 10);

            add_action('woocommerce_template_loop_product_link_open', [__CLASS__, 'product_link_open'], 9);
            add_action('woocommerce_template_loop_product_link_close', [__CLASS__, 'product_link_close'], 9);
        } else {
            add_action('woocommerce_template_loop_product_link_open', [__CLASS__, 'product_link_open'], 9);
            add_action('woocommerce_template_loop_product_link_close', [__CLASS__, 'product_link_close'], 9);
        }
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
            '<button class="quick-view-button" data-product-id="%1$d">%2$s</button>',
            esc_attr($product->get_id()),
            esc_html__('Quick View', 'quick-view-for-woocommerce')
        );
    }

    /**
     * Output product link open tag with data-product-id if image trigger is enabled.
     */
    public static function product_link_open()
    {
        if (! get_option('quick_view_trigger_image', 0)) {
            // Fallback to default WooCommerce output
            wc_get_template('loop/product-link-open.php');
            return;
        }
        global $product;
        if (! $product) {
            return;
        }
        printf(
            '<a class="woocommerce-LoopProduct-link" data-product-id="%2$d">',
            esc_attr($product->get_id())
        );
        // printf(
        //     '<a href="%1$s" class="woocommerce-LoopProduct-link" data-product-id="%2$d">',
        //     esc_url(get_permalink($product->get_id())),
        //     esc_attr($product->get_id())
        // );
    }

    /**
     * Output product link close tag.
     */
    public static function product_link_close()
    {
        if (! get_option('quick_view_trigger_image', 0)) {
            // Fallback to default WooCommerce output
            echo '</a>';
            return;
        }
        echo '</a>';
    }
}
