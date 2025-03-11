<?php
namespace GpcAstra\Includes\Blocks;

/**
 * Dummy block chỉ để block editor không chạy ở chế độ iframe
 * Ở chế độ iframe thì block Cổ điển (tinymce) chuyển sang edit bằng popup
 *
 * @see https://github.com/WordPress/gutenberg/issues/53511#issuecomment-1672785421
 * @see https://github.com/WordPress/gutenberg/issues/53258
 */
class GpcDummy
{
    public function __construct()
    {
        add_action('enqueue_block_editor_assets', [$this, 'enqueue_block_editor_assets']);
    }

    function enqueue_block_editor_assets() {
        wp_enqueue_script(
            'gpc-block-dummy',
            get_stylesheet_directory_uri() . '/blocks/gpc-dummy.js',
            array('wp-blocks', 'wp-element', 'wp-editor'),
            true
        );
    }
}