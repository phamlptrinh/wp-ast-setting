<?php

namespace GpcAstra\Includes\Library;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class CustomPostType
{
    public static function register( $post_type, $singular = '', $plural = '', $options = [] )
    {
        if ( !$singular && !$plural ) {
            $singular = self::make_label_from_post_type($post_type);
            $plural = $singular;
        } else if ( !empty($singular) && empty($plural) ) {
            $plural = $singular;
        } else if ( empty($singular) && !empty($plural) ) {
            $singular = $plural;
        }

        $labels = array(
			'name'               => $plural,
			'singular_name'      => $singular,
			'name_admin_bar'     => $singular,
			'add_new'            => _x( 'Add New', $post_type, 'gpc-site-functionality' ),
			'add_new_item'       => sprintf( __( 'Add New %s', 'gpc-site-functionality' ), $singular ),
			'edit_item'          => sprintf( __( 'Edit %s', 'gpc-site-functionality' ), $singular ),
			'new_item'           => sprintf( __( 'New %s', 'gpc-site-functionality' ), $singular ),
			'all_items'          => sprintf( __( 'All %s', 'gpc-site-functionality' ), $plural ),
			'view_item'          => sprintf( __( 'View %s', 'gpc-site-functionality' ), $singular ),
			'search_items'       => sprintf( __( 'Search %s', 'gpc-site-functionality' ), $plural ),
			'not_found'          => sprintf( __( 'No %s Found', 'gpc-site-functionality' ), $plural ),
			'not_found_in_trash' => sprintf( __( 'No %s Found In Trash', 'gpc-site-functionality' ), $plural ),
			'parent_item_colon'  => sprintf( __( 'Parent %s' ), $singular ),
			'menu_name'          => $plural,
		);

        $args = array(
			'labels'                => apply_filters( $post_type . '_labels', $labels ),
			'public'                => true,
			'publicly_queryable'    => true,
			'exclude_from_search'   => false,
			'show_ui'               => true,
			'show_in_menu'          => true,
			'show_in_nav_menus'     => true,
			'query_var'             => true,
			'can_export'            => true,
			'rewrite'               => true,
			'capability_type'       => 'post',
			'has_archive'           => true,
			'hierarchical'          => false,
			'show_in_rest'          => true,
			'supports'              => array( 'title', 'editor', 'excerpt', 'comments', 'thumbnail' ),
			'menu_position'         => 5,
			'menu_icon'             => 'dashicons-admin-post',
		);

		$args = array_merge( $args, $options );

        register_post_type( $post_type, apply_filters( $post_type . '_register_args', $args, $post_type ) );
    }

    private static function make_label_from_post_type($post_type)
    {
        $post_type = str_replace( ['_', '-'], [' ', ' '], $post_type );
        if ( str_starts_with( strtolower($post_type), 'gpc') ) {
            $post_type = substr($post_type, 4);
        }

        return ucfirst($post_type);
    }
}