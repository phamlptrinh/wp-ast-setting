<?php
namespace GpcAstra\Includes\Blocks;

class CoreGroup
{
    public function __construct()
    {
        //add_filter( 'render_block_core/group', [ $this, 'render_block_core_group' ], 16, 2 );
    }

    public function gpc_render_block_core_group($block_content, $block) {

        if (isset($block['attrs']['style']['spacing']['blockGap'])) {
            $gap = $block['attrs']['style']['spacing']['blockGap'];

            if (str_starts_with($gap, 'var')) {
                $parts = explode('|', $gap);
                $gap = "var(--wp--preset--spacing--{$parts[count($parts) - 1]})";
            }

            $block_content = preg_replace(
                '/' . preg_quote( 'class="', '/' ) . '/',
                'style="--block-gap:' . $gap . '" class=" ',
                $block_content,
                1
            );
        }
        return $block_content;
    }

}