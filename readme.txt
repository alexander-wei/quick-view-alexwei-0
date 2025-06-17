=== Quick View for WooCommerce ===
Contributors: Alexander Wei
Tags: woocommerce, quick view, modal
Requires at least: 5.0
Tested up to: 6.5
Stable tag: 1.0.0
License: GPL-2.0+
License URI: https://www.gnu.org/licenses/gpl-2.0.html

== Description ==
Adds a customizable Quick View modal to WooCommerce product listings. Administrators can choose to trigger quick view on button click or product image click, and select an Elementor template for rendering product details.

== Installation ==
1. Upload the `quick-view-for-woocommerce` folder to the `/wp-content/plugins/` directory.
2. Install dependencies via Composer:
   ```
   cd wp-content/plugins/quick-view-for-woocommerce
   composer install
   ```
3. Activate the plugin through the ‘Plugins’ screen in WordPress.
4. Go to **Settings » Quick View Settings** to configure trigger and template.

== Screenshots ==
1. Quick View button on product listing.
2. Quick View modal displaying Elementor template.

== Changelog ==
= 1.0.0 =
* Initial refactor to PSR-4 structure
* Added Composer autoloading
* Separated admin and public classes
* Moved assets to `assets/` directory
* Added readme, license headers, and translation support

== Upgrade Notice ==
= 1.0.0 =
Initial release.
