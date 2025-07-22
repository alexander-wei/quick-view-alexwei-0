<?php

namespace QuickViewForWC\Admin;

/**
 * Settings page for Quick View plugin.
 */
class SettingsPage
{
    public static function init(): void
    {
        add_action('admin_menu', [__CLASS__, 'add_settings_page']);
        add_action('admin_init', [__CLASS__, 'register_settings']);
    }

    public static function add_settings_page(): void
    {
        add_options_page(
            __('Quick View Settings', 'quick-view-for-woocommerce'),
            __('Quick View Settings', 'quick-view-for-woocommerce'),
            'manage_options',
            'quick-view-settings',
            [__CLASS__, 'render_settings_page']
        );
    }

    public static function register_settings(): void
    {
        register_setting(
            'quick_view_settings_group',
            'quick_view_trigger_image',
            [
                'sanitize_callback' => 'absint',
                'default'           => 0,
            ]
        );
        register_setting(
            'quick_view_settings_group',
            'quick_view_template_id',
            [
                'sanitize_callback' => 'absint',
                'default'           => '',
            ]
        );

        add_settings_section(
            'quick_view_main_section',
            __('Main Settings', 'quick-view-for-woocommerce'),
            null,
            'quick-view-settings'
        );

        add_settings_field(
            'quick_view_trigger_image',
            __('Enable Quick View on Product Image Click', 'quick-view-for-woocommerce'),
            [__CLASS__, 'render_trigger_image_field'],
            'quick-view-settings',
            'quick_view_main_section'
        );

        add_settings_field(
            'quick_view_template_id',
            __('Quick View Modal Template', 'quick-view-for-woocommerce'),
            [__CLASS__, 'render_template_id_field'],
            'quick-view-settings',
            'quick_view_main_section'
        );
    }

    public static function render_trigger_image_field(): void
    {
        $option = get_option('quick_view_trigger_image', 0);
        printf(
            '<input type="checkbox" name="quick_view_trigger_image" value="1" %s />',
            checked(1, $option, false)
        );
    }

    public static function render_template_id_field(): void
    {
        $templates = get_posts([
            'post_type'      => 'elementor_library',
            'posts_per_page' => -1,
        ]);
        $selected = get_option('quick_view_template_id', '');
        echo '<select name="quick_view_template_id">';
        echo '<option value="">' . esc_html__('-- Select Template --', 'quick-view-for-woocommerce') . '</option>';
        foreach ($templates as $template) {
            printf(
                '<option value="%1$s" %2$s>%3$s</option>',
                esc_attr($template->ID),
                selected($selected, $template->ID, false),
                esc_html($template->post_title)
            );
        }
        echo '</select>';
    }

    public static function render_settings_page(): void
    {
?>
        <div class="wrap">
            <h1><?php esc_html_e('Quick View Settings', 'quick-view-for-woocommerce'); ?></h1>
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
