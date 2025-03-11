<?php

namespace GpcAstra\Includes\Library;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class CustomTaxonomy
{
    public static function regiter($taxonomy, $post_types = [], $singular = '', $plural = '', $options = [])
    {
        if ( !$singular && !$plural ) {
            $singular = self::make_label_from_taxonomy($taxonomy);
            $plural = $singular;
        } else if ( !empty($singular) && empty($plural) ) {
            $plural = $singular;
        } else if ( empty($singular) && !empty($plural) ) {
            $singular = $plural;
        }

        $labels = array(
			'name'                       => $plural,
			'singular_name'              => $singular,
			'menu_name'                  => $plural,
			'all_items'                  => sprintf( __( 'All %s', 'gpc-site-functionality' ), $plural ),
			'edit_item'                  => sprintf( __( 'Edit %s', 'gpc-site-functionality' ), $singular ),
			'view_item'                  => sprintf( __( 'View %s', 'gpc-site-functionality' ), $singular ),
			'update_item'                => sprintf( __( 'Update %s', 'gpc-site-functionality' ), $singular ),
			'add_new_item'               => sprintf( __( 'Add New %s', 'gpc-site-functionality' ), $singular ),
			'new_item_name'              => sprintf( __( 'New %s Name', 'gpc-site-functionality' ), $singular ),
			'parent_item'                => sprintf( __( 'Parent %s', 'gpc-site-functionality' ), $singular ),
			'parent_item_colon'          => sprintf( __( 'Parent %s:', 'gpc-site-functionality' ), $singular ),
			'search_items'               => sprintf( __( 'Search %s', 'gpc-site-functionality' ), $plural ),
			'popular_items'              => sprintf( __( 'Popular %s', 'gpc-site-functionality' ), $plural ),
			'separate_items_with_commas' => sprintf( __( 'Separate %s with commas', 'gpc-site-functionality' ), $plural ),
			'add_or_remove_items'        => sprintf( __( 'Add or remove %s', 'gpc-site-functionality' ), $plural ),
			'choose_from_most_used'      => sprintf( __( 'Choose from the most used %s', 'gpc-site-functionality' ), $plural ),
			'not_found'                  => sprintf( __( 'No %s found', 'gpc-site-functionality' ), $plural ),
		);

		//phpcs:enable
		$args = array(
			'label'                 => $plural,
			'labels'                => apply_filters( $taxonomy . '_labels', $labels ),
			'hierarchical'          => true,
			'public'                => true,
			'show_ui'               => true,
			'show_in_nav_menus'     => true,
			'show_tagcloud'         => true,
			'meta_box_cb'           => null,
			'show_admin_column'     => true,
			'show_in_quick_edit'    => true,
			'update_count_callback' => '',
			'show_in_rest'          => true,
			'query_var'             => $taxonomy,
			'rewrite'               => true,
			'sort'                  => '',
		);

		$args = array_merge( $args, $options );

		register_taxonomy( $taxonomy, $post_types, apply_filters( $taxonomy . '_register_args', $args, $taxonomy, $post_types ) );
    }

    private static function make_label_from_taxonomy($taxonomy)
    {
        $taxonomy = str_replace( ['_', '-'], [' ', ' '], $taxonomy );
        if ( str_starts_with( strtolower($taxonomy), 'gpc') ) {
            $taxonomy = substr($taxonomy, 4);
        }

        return ucfirst($taxonomy);
    }
}