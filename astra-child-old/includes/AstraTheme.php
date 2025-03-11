<?php
namespace GpcAstra\Includes;

/**
 * Chỉnh sửa theme Astra cho phù hợp nhu cầu của GPC
 */
class AstraTheme
{
    public function __construct()
    {
        /*---------- Disable astra notices ---------- */
        add_filter('bsf_display_product_activation_notice_astra-addon', '__return_false');
        add_filter('bsf_display_product_activation_notice_astra', '__return_false');
        add_filter('bsf_enable_product_autoupdates_astra-addon', '__return_false');

        /*---------- bỏ padding ở page không có title ---------- */
        add_filter( 'astra_stretched_layout_with_spacing', '__return_false' );

        // override Astra settings
        add_action( 'after_setup_theme', [ $this, 'init_admin_settings' ], 100 );
    }

    public function init_admin_settings()
    {
        // bỏ thông báo cập nhật
        remove_action( 'admin_init', 'Astra_Admin_Settings::minimum_addon_version_notice' );
        remove_action( 'admin_init', 'Astra_Admin_Settings::minimum_addon_supported_version_notice' );
    }
}