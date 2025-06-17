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
        $template_id = get_option('quick_view_template_id', '');

        if ($product_id && $template_id) {
            global $post;
            $original_post = $post;

            // Set product context
            $post = get_post($product_id);
            setup_postdata($post);

            // Render Elementor Template
            echo Elementor\Plugin::instance()->frontend->get_builder_content_for_display($template_id, true);

            wp_reset_postdata();
            $post = $original_post;
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
        register_setting('quick_view_settings_group', 'quick_view_template_id');


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

        add_settings_field(
            'quick_view_template_id',
            'Quick View Modal Template',
            function () {
                $templates = get_posts([
                    'post_type' => 'elementor_library',
                    'posts_per_page' => -1
                ]);

                $selected = get_option('quick_view_template_id', '');

                echo '<select name="quick_view_template_id">';
                echo '<option value="">-- Select Template --</option>';
                foreach ($templates as $template) {
                    echo '<option value="' . esc_attr($template->ID) . '" ' . selected($selected, $template->ID, false) . '>' . esc_html($template->post_title) . '</option>';
                }
                echo '</select>';
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
