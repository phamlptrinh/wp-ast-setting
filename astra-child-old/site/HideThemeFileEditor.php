<?php

namespace GpcAstra\Site;

class HideThemeFileEditor
{
    public function __construct()
    {        
        add_action( 'init', array( $this, 'disable_theme_file_editor' ) );
    }

    function disable_theme_file_editor(){
        if ( empty(carbon_get_theme_option('gpc_disable_theme_file_editor')))
        {return;}

        define('DISALLOW_FILE_EDIT', true);
    }
    
}