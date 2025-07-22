<?php

/**
 * Plugin Name: Quick View for WooCommerce
 * Plugin URI: 
 * Description: Adds a Quick View modal to WooCommerce product listings.
 * Version: 1.0.0
 * Author: Alexander Wei
 * Text Domain: quick-view-for-woocommerce
 * Domain Path: /languages
 * License: GPL-2.0+
 */

if (! defined('ABSPATH')) {
    exit;
}

define('QVIEW_PLUGIN_VERSION', '1.0.0');
define('QVIEW_PLUGIN_URL', plugin_dir_url(__FILE__));
define('QVIEW_PLUGIN_PATH', plugin_dir_path(__FILE__));

// Autoload classes via Composer
if (file_exists(QVIEW_PLUGIN_PATH . 'vendor/autoload.php')) {
    require_once QVIEW_PLUGIN_PATH . 'vendor/autoload.php';
}

// Load translations
load_plugin_textdomain('quick-view-for-woocommerce', false, dirname(plugin_basename(__FILE__)) . '/languages');

// Run the plugin
QuickViewForWC\Plugin::run();
