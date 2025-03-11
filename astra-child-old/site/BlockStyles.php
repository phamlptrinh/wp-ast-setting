<?php
namespace GpcAstra\Site;

class BlockStyles
{
    public function __construct()
    {
        add_action('init', [ $this, 'register_block_styles' ]);
    }

    public function register_block_styles()
    {
        register_block_style('kadence/advancedheading', array(
            'name'         => 'section-title',
            'label'        => 'Section Title',
        ));
    }
}