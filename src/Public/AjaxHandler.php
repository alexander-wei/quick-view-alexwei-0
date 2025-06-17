<?php

namespace QuickViewForWC\Public;

use function add_action;
use function get_option;
use function get_post;
use function setup_postdata;
use function wp_reset_postdata;
use function wp_die;

/**
 * Handles AJAX request for rendering quick view content.
 */
class AjaxHandler
{
    public static function init(): void
    {
        add_action('wp_ajax_quick_view_get_product', [__CLASS__, 'handle']);
        add_action('wp_ajax_nopriv_quick_view_get_product', [__CLASS__, 'handle']);
    }

    public static function handle(): void
    {
        $product_id  = isset($_POST['product_id']) ? intval($_POST['product_id']) : 0;
        $template_id = get_option('quick_view_template_id', 0);

        if ($product_id && $template_id) {
            global $post;
            $original_post = $post;

            $post = get_post($product_id);
            setup_postdata($post);

            echo \Elementor\Plugin::instance()->frontend->get_builder_content_for_display($template_id, true);

            wp_reset_postdata();
            $post = $original_post;
        }

        wp_die();
    }
}
