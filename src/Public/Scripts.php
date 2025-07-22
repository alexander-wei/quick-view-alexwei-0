<?php

namespace QuickViewForWC\Public;

use function add_action;
use function wp_enqueue_style;
use function wp_enqueue_script;
use function wp_localize_script;
use QuickViewForWC\QVIEW_PLUGIN_URL;
use QuickViewForWC\QVIEW_PLUGIN_VERSION;

/**
 * Enqueue plugin public-facing CSS and JS.
 */
class Scripts
{
    public static function init(): void
    {
        add_action('wp_enqueue_scripts', [__CLASS__, 'enqueue']);
    }

    public static function enqueue(): void
    {
        wp_enqueue_style(
            'quick-view-style',
            QVIEW_PLUGIN_URL . 'assets/css/quick-view.css',
            [],
            QVIEW_PLUGIN_VERSION
        );

        wp_enqueue_script(
            'quick-view-script',
            QVIEW_PLUGIN_URL . 'assets/js/quick-view.js',
            ['jquery'],
            QVIEW_PLUGIN_VERSION,
            true
        );

        wp_localize_script(
            'quick-view-script',
            'quickViewAjax',
            [
                'ajaxurl'      => admin_url('admin-ajax.php'),
                'triggerImage' => get_option('quick_view_trigger_image', '0'),
            ]
        );
    }
}
