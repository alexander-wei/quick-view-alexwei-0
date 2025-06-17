<?php

namespace QuickViewForWC;

use QuickViewForWC\Admin\SettingsPage;
use QuickViewForWC\Public\Scripts;
use QuickViewForWC\Public\QuickViewButton;
use QuickViewForWC\Public\AjaxHandler;

/**
 * Main plugin bootstrap class.
 */
class Plugin
{

    /**
     * Initialize plugin: register hooks.
     */
    public static function run(): void
    {
        $instance = new self();
        $instance->register_hooks();
    }

    /**
     * Register all action/filter hooks.
     */
    private function register_hooks(): void
    {
        // Public-facing functionality
        Scripts::init();
        QuickViewButton::init();
        AjaxHandler::init();

        // Admin settings
        SettingsPage::init();
    }
}
