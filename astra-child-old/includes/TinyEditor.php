<?php
namespace GpcAstra\Includes;

class TinyEditor
{
    public function __construct()
    {
        add_filter( 'mce_buttons', array( $this, 'mce_buttons' ), 999, 2 );
        add_filter( 'tiny_mce_before_init', array( $this, 'mce_options' ), 10, 2 );
        add_filter( 'mce_external_plugins', array( $this, 'mce_external_plugins' ), 999 );
    }

    public function mce_buttons( $buttons )
    {
        //Thêm nút bấm
        array_splice($buttons, 1, 0, [
            'fontsizeselect',
            'forecolor',
            'backcolor',
            'table',
        ]);

        return $buttons;
    }

    public function mce_options( $options )
    {
        // Đổi đơn vị của font-size
        $options['fontsize_formats'] =  'none 8px 10px 12px 14px 16px 20px 24px 28px 32px 36px 48px 60px 72px 96px';
        return $options;
    }

    public function mce_external_plugins( $plugins )
    {
        $plugins['table'] = GPC_ASTRA_DIR_URL . '/assets/mce/table/plugin.min.js';
        return $plugins;
    }
}