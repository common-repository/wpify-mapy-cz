<?php

namespace WpifyMapyCz\PostTypes;

use WpifyMapyCzDeps\Wpify\CustomFields\CustomFields;
use WpifyMapyCzDeps\Wpify\PostType\AbstractCustomPostType;

class MapPostType extends AbstractCustomPostType {
	const KEY = 'wpify_mapycz_map';

	/** @var CustomFields */
	protected $wcf;

	public function __construct( CustomFields $wcf ) {
		$this->wcf = $wcf;

		parent::__construct();
	}

	public function setup() {
		$this->wcf->create_metabox( array(
			'id'         => 'map-details',
			'title'      => __( 'Map details', 'wpify-mapy-cz' ),
			'post_types' => array( $this->get_post_type_key() ),
			'context'    => 'advanced',
			'priority'   => 'high',
			'items'      => array(
				array(
					'type'  => 'mapycz_map',
					'id'    => '_mapy_cz_data',
					'title' => __( 'Map', 'wpify-mapy-cz' ),
				),
			),
		) );
	}

	public function get_post_type_key(): string {
		return self::KEY;
	}

	public function get_args(): array {
		return array(
			'labels'             => array(
				'name'               => _x( 'Maps', 'post type general name', 'wpify-mapy-cz' ),
				'singular_name'      => _x( 'Map', 'post type singular name', 'wpify-mapy-cz' ),
				'menu_name'          => _x( 'Maps', 'admin menu', 'wpify-mapy-cz' ),
				'name_admin_bar'     => _x( 'Map', 'add new on admin bar', 'wpify-mapy-cz' ),
				'add_new'            => __( 'Add New', 'add new', 'wpify-mapy-cz' ),
				'add_new_item'       => __( 'Add New Map', 'wpify-mapy-cz' ),
				'new_item'           => __( 'New Map', 'wpify-mapy-cz' ),
				'edit_item'          => __( 'Edit Map', 'wpify-mapy-cz' ),
				'view_item'          => __( 'View Map', 'wpify-mapy-cz' ),
				'all_items'          => __( 'All Maps', 'wpify-mapy-cz' ),
				'search_items'       => __( 'Search Maps', 'wpify-mapy-cz' ),
				'parent_item_colon'  => __( 'Parent Maps:', 'wpify-mapy-cz' ),
				'not_found'          => __( 'No Maps found.', 'wpify-mapy-cz' ),
				'not_found_in_trash' => __( 'No Maps found in Trash.', 'wpify-mapy-cz' ),
			),
			'description'        => __( 'Description of Map.', 'wpify-mapy-cz' ),
			'public'             => false,
			'publicly_queryable' => false,
			'show_ui'            => true,
			'show_in_menu'       => false,
			'show_in_rest'       => true,
			'query_var'          => true,
			'rewrite'            => array( 'slug' => __( 'map', 'wpify-mapy-cz' ) ),
			'capability_type'    => 'post',
			'has_archive'        => false,
			'hierarchical'       => false,
			'menu_position'      => null,
			'supports'           => array( 'title' ),
		);
	}
}
