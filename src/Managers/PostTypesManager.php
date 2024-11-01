<?php

namespace WpifyMapyCz\Managers;

use WpifyMapyCz\PostTypes\MarkerPostType;
use WpifyMapyCz\PostTypes\MapPostType;
use WpifyMapyCzDeps\Wpify\CustomFields\CustomFields;

final class PostTypesManager {
	/** @var CustomFields */
	private $wcf;

	public function __construct(
		MapPostType $book_post_type,
		MarkerPostType $marker_post_type,
		CustomFields $wcf
	) {
		$this->wcf = $wcf;

		$this->setup();
	}

	public function setup() {
		$marker_post_types = apply_filters( 'wpify_mapy_cz_marker_post_types', array( MarkerPostType::KEY ) );

		foreach ( $marker_post_types as $marker_post_type ) {
			$this->wcf->create_metabox( array(
				'id'         => 'marker-details',
				'title'      => __( 'Marker details', 'wpify-mapy-cz' ),
				'post_types' => array( $marker_post_type ),
				'context'    => 'advanced',
				'priority'   => 'high',
				'items'      => array(
					array(
						'type'  => 'mapycz_marker',
						'id'    => '_mapy_cz_data',
						'title' => __( 'Marker', 'wpify-mapy-cz' ),
					),
				),
			) );
		}
	}
}
