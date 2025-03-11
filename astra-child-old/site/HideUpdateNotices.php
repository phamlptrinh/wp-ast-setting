<?php

namespace GpcAstra\Site;

class HideUpdateNotices
{
    public function __construct()
    {        
        add_action( 'init', array( $this, 'hide_update_notices' ) );

    }
    
    function hide_update_notices()
    {
        if ( empty(carbon_get_theme_option('gpc_hide_update_notices')))
        {
            return;
        }

        if( ! current_user_can('update_core') ){return;}
        //add_action('init', create_function('$a',"remove_action( 'init', 'wp_version_check' );"),2);
        add_action( 'init', function () {
                remove_action( 'init', 'wp_version_check' );
            }, 2 );
        add_filter('pre_option_update_core','__return_null');
        add_filter('pre_site_transient_update_core','__return_null');

        remove_action('load-update-core.php','wp_update_plugins');
        add_filter('pre_site_transient_update_plugins','__return_null');

        //chấm hỏi
        add_filter('pre_site_transient_update_core', array( $this, 'remove_theme_updates') );
        add_filter('pre_site_transient_update_plugins', array( $this, 'remove_theme_updates') );
        add_filter('pre_site_transient_update_themes', array( $this, 'remove_theme_updates') );

    }

    function remove_theme_updates()
    {    
        global $wp_version;
        return(object) array('last_checked'=> time(),'version_checked'=> $wp_version,);
    }
}