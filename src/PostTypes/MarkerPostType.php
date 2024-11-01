<?php

namespace WpifyMapyCz\PostTypes;

use WpifyMapyCzDeps\Wpify\CustomFields\CustomFields;
use WpifyMapyCzDeps\Wpify\PostType\AbstractCustomPostType;

class MarkerPostType extends AbstractCustomPostType {
	const KEY = 'wpify_mapycz_marker';

	public function get_post_type_key(): string {
		return self::KEY;
	}

	public function get_args(): array {
		return array(
			'labels'             => array(
				'name'               => _x('Markers', 'post type general name', 'wpify-mapy-cz'),
				'singular_name'      => _x('Marker', 'post type singular name', 'wpify-mapy-cz'),
				'menu_name'          => _x('Markers', 'admin menu', 'wpify-mapy-cz'),
				'name_admin_bar'     => _x('Marker', 'add new on admin bar', 'wpify-mapy-cz'),
				'add_new'            => __('Add New', 'add new', 'wpify-mapy-cz'),
				'add_new_item'       => __('Add New Marker', 'wpify-mapy-cz'),
				'new_item'           => __('New Marker', 'wpify-mapy-cz'),
				'edit_item'          => __('Edit Marker', 'wpify-mapy-cz'),
				'view_item'          => __('View Marker', 'wpify-mapy-cz'),
				'all_items'          => __('All Markers', 'wpify-mapy-cz'),
				'search_items'       => __('Search Markers', 'wpify-mapy-cz'),
				'parent_item_colon'  => __('Parent Markers:', 'wpify-mapy-cz'),
				'not_found'          => __('No Markers found.', 'wpify-mapy-cz'),
				'not_found_in_trash' => __('No Markers found in Trash.', 'wpify-mapy-cz'),
			),
			'description'        => __('Description of Marker.', 'wpify-mapy-cz'),
			'public'             => true,
			'publicly_queryable' => true,
			'show_ui'            => true,
			'show_in_menu'       => false,
			'show_in_rest'       => true,
			'query_var'          => true,
			'rewrite'            => array( 'slug' => __('marker', 'wpify-mapy-cz') ),
			'capability_type'    => 'post',
			'has_archive'        => false,
			'hierarchical'       => false,
			'menu_position'      => null,
			'supports'           => array( 'title' ),
			'template_lock'      => 'all',
		);
	}
}
