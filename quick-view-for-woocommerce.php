<?php

/**
 * Plugin Name: Quick View for WooCommerce
 * Description: Adds a Quick View modal to WooCommerce product listings.
 * Version: 1.0
 * Author: Alexander Wei
 */

if (! defined('ABSPATH')) exit; // Exit if accessed directly

class Quick_View_For_WooCommerce
{

    public function __construct()
    {
        add_action('wp_enqueue_scripts', [$this, 'enqueue_scripts']);
        add_action('woocommerce_after_shop_loop_item', [$this, 'add_quick_view_button']);
        add_action('wp_ajax_quick_view_get_product', [$this, 'ajax_get_product']);
        add_action('wp_ajax_nopriv_quick_view_get_product', [$this, 'ajax_get_product']);

        add_action('admin_menu', [$this, 'add_settings_page']);
        add_action('admin_init', [$this, 'register_settings']);
    }

    public function enqueue_scripts()
    {
        wp_enqueue_style('quick-view-style', plugin_dir_url(__FILE__) . 'quick-view.css');
        wp_enqueue_script('quick-view-script', plugin_dir_url(__FILE__) . 'quick-view.js', ['jquery'], null, true);
        wp_localize_script('quick-view-script', 'quickViewAjax', [
            'ajaxurl' => admin_url('admin-ajax.php'),
            'triggerImage' => get_option('quick_view_trigger_image', '0'),
        ]);
    }

    public function add_quick_view_button()
    {
        global $product;
        echo '<button class="quick-view-button" data-product_id="' . esc_attr($product->get_id()) . '">Quick View</button>';
    }

    public function ajax_get_product()
    {
        $product_id = intval($_POST['product_id']);
        $product = wc_get_product($product_id);

        if ($product) {
            ob_start();
?>
            <div class="quick-view-modal-content">
                <img src="<?php echo wp_get_attachment_image_url($product->get_image_id(), 'large'); ?>" />
                <h2><?php echo $product->get_name(); ?></h2>
                <p><?php echo $product->get_price_html(); ?></p>
                <div><?php echo apply_filters('the_content', $product->get_description()); ?></div>
                <form class="cart" method="post" enctype='multipart/form-data'>
                    <input type="hidden" name="add-to-cart" value="<?php echo $product->get_id(); ?>" />
                    <button type="submit" class="single_add_to_cart_button button alt">Add to cart</button>
                </form>
            </div>
        <?php
            echo ob_get_clean();
        }

        wp_die();
    }

    public function add_settings_page()
    {
        add_options_page(
            'Quick View Settings',
            'Quick View Settings',
            'manage_options',
            'quick-view-settings',
            [$this, 'render_settings_page']
        );
    }

    public function register_settings()
    {
        register_setting('quick_view_settings_group', 'quick_view_trigger_image');

        add_settings_section('quick_view_main_section', 'Main Settings', null, 'quick-view-settings');

        add_settings_field(
            'quick_view_trigger_image',
            'Enable Quick View on Product Image Click',
            function () {
                $option = get_option('quick_view_trigger_image', '0');
                echo '<input type="checkbox" name="quick_view_trigger_image" value="1" ' . checked(1, $option, false) . ' />';
            },
            'quick-view-settings',
            'quick_view_main_section'
        );
    }

    public function render_settings_page()
    {
        ?>
        <div class="wrap">
            <h1>Quick View Settings</h1>
            <form method="post" action="options.php">
                <?php
                settings_fields('quick_view_settings_group');
                do_settings_sections('quick-view-settings');
                submit_button();
                ?>
            </form>
        </div>
<?php
    }
}



new Quick_View_For_WooCommerce();
