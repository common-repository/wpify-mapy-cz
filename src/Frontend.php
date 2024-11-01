<?php

namespace WpifyMapyCz;

use WpifyMapyCz\Models\MapModel;
use WpifyMapyCz\PostTypes\MarkerPostType;
use WpifyMapyCz\Repositories\MapRepository;
use WpifyMapyCz\Repositories\MarkerRepository;
use WpifyMapyCzDeps\Wpify\Asset\AssetFactory;
use WpifyMapyCzDeps\Wpify\PluginUtils\PluginUtils;
use WpifyMapyCzDeps\Wpify\Templates\WordPressTemplates;

class Frontend {
	/** @var PluginUtils */
	private $utils;

	/** @var AssetFactory */
	private $asset_factory;

	/** @var WordPressTemplates */
	private $templates;

	/** @var MapRepository */
	private $map_repository;

	/** @var MarkerRepository */
	private $marker_repository;

	private $markers;

	public function __construct(
		PluginUtils $utils,
		AssetFactory $asset_factory,
		WordPressTemplates $templates,
		MapRepository $map_repository,
		MarkerRepository $marker_repository
	) {
		$this->utils             = $utils;
		$this->asset_factory     = $asset_factory;
		$this->templates         = $templates;
		$this->map_repository    = $map_repository;
		$this->marker_repository = $marker_repository;

		$this->setup();
	}

	public function setup() {
		add_shortcode( 'wpify_mapy_cz', array( $this, 'render_map' ) );
		add_shortcode( 'wpify_mapy_cz_marker', array( $this, 'render_marker_map' ) );
	}

	public function render_map( $data = array() ) {
		if ( isset( $data['map_id'] ) && ! isset( $data['id'] ) ) {
			$data['id'] = $data['map_id'];
		}

		/** @var MapModel $map */
		$map = $this->map_repository->get( $data['id'] );

		if ( ! empty( $map ) ) {
			$this->asset_factory->wp_script( $this->utils->get_plugin_path( 'build/plugin.js' ) )->enqueue();

			$map_data = array_merge( $map->_mapy_cz_data, $map->to_array( array(
				'id',
				'auto_center_zoom',
				'description',
				'width',
				'height',
				'latitude',
				'longitude',
				'show_info_window',
				'zoom',
				'layer_type'
			) ) );

			$map_data['markers'] = $this->get_markers( $map_data );

			$map_data = apply_filters( 'wpify_mapy_cz_map_data', $map_data, $map );

			add_filter( 'wpify_mapy_cz_shown', '__return_true' );

			return $this->templates->render( 'map', null, $map_data );
		}

		return '';
	}

	public function get_markers( array $args ) {
		global $wpdb;

		$id = $args['id'] ?? null;

		if ( empty( $this->markers[ $id ] ) && ! empty( $id ) ) {
			$post_types          = $this->get_post_types_sql( $args );
			$marker_post_type    = MarkerPostType::KEY;
			$selected_marker_ids = join( ',', $args['markers'] ?? array( - 1 ) );

			if ( isset( $args['show_only_current_marker'] ) && $args['show_only_current_marker'] && is_single() && in_array( get_post_type(), $args['post_types'] ?? array( MarkerPostType::KEY ) ) ) {
				$selected_marker_ids = get_the_ID();
			}

			if ( empty( $selected_marker_ids ) ) {
				$selected_marker_ids = - 1;
			}

			$sql = <<<SQL
			select
			       post.post_title as title,
			       post.ID as id,
			       post.post_excerpt as excerpt,
			       post.post_content as content,
			       meta.meta_value as map_data,
			       thumbnail_id.meta_value as thumbnail_id
			from $wpdb->posts as post
			inner join $wpdb->postmeta as meta on meta.post_id = post.ID
			left outer join $wpdb->postmeta as thumbnail_id on thumbnail_id.post_id = post.ID and thumbnail_id.meta_key = '_thumbnail_id'
			where post.post_type in ($post_types)
			and post.post_status = 'publish'
			and meta.meta_key = '_mapy_cz_data'
			and ((post.post_type = '$marker_post_type' and post.id in ($selected_marker_ids)) or (post.post_type <> '$marker_post_type'))
			SQL;

			$markers = $wpdb->get_results( $sql, ARRAY_A );
			$markers = array_values( array_filter( array_map( function ( $marker ) use ( $args ) {
				$map_data            = maybe_unserialize( $marker['map_data'] ?? '' );
				$marker['map_data']  = $map_data;
				$marker['longitude'] = $map_data['longitude'] ?? $marker['longitude'] ?? null;
				$marker['latitude']  = $map_data['latitude'] ?? $marker['latitude'] ?? null;
				$marker['address']   = $map_data['address'] ?? $marker['address'] ?? null;
				$marker['content']   = wp_strip_all_tags( $marker['content'] );

				if ( empty( $marker['longitude'] ) || empty( $marker['latitude'] ) ) {
					return null;
				}

				$description = $map_data['description'] ?? '';

				if ( empty( $description ) ) {
					$description = $marker['excerpt'] ?? '';
				}

				if ( empty( $description ) ) {
					$description = get_the_excerpt( $marker['marker_id'] ?? '' );
				}

				if ( empty( $description ) ) {
					$description = wp_strip_all_tags( $marker['content'] ?? '' );
				}

				$marker['description']  = $description;
				$marker['marker_id']    = intval( $marker['id'] );
				$marker['permalink']    = get_permalink( $marker['id'] );
				$marker['thumbnail_id'] = $marker['thumbnail_id'] ?? null;

				if ( $args['show_info_window'] ) {
					$marker['card'] = array(
						'header' => $this->templates->render( 'card', 'header', array( 'marker' => $marker, 'map' => $args ) ),
						'body'   => $this->templates->render( 'card', 'body', array( 'marker' => $marker, 'map' => $args ) ),
						'footer' => $this->templates->render( 'card', 'footer', array( 'marker' => $marker, 'map' => $args ) ),
					);
				}

				return $marker;
			}, $markers ) ) );

			$this->markers[ $id ] = $markers;
		}

		return $this->markers[ $id ] ?? null;
	}

	public function get_post_types_sql( array $args ) {
		$post_types   = $args['post_types'] ?? array();
		$post_types[] = MarkerPostType::KEY;
		$post_types   = apply_filters( 'wpify_mapy_cz_post_types', array_unique( $post_types ), $args );

		return join( ',', array_map( function ( $post_type ) {
			return '\'' . $post_type . '\'';
		}, $post_types ) );
	}

	public function render_marker_map( $atts ) {
		$marker = null;

		if ( ! is_array( $atts ) ) {
			$atts = array();
		}

		if ( ! empty( $atts['id'] ) ) {
			$marker_id = intval( $atts['id'] );
		} else {
			$marker_id = get_the_ID();
		}

		if ( $marker_id ) {
			$marker = $this->marker_repository->get( $marker_id );
		}

		if ( $marker ) {
			$this->asset_factory->wp_script( $this->utils->get_plugin_path( 'build/plugin.js' ) )->enqueue();

			$marker_data = is_array( $marker->_mapy_cz_data ) ? $marker->_mapy_cz_data : array();

			$map_data = array_merge( $marker_data, $marker->to_array( array(
				'id',
				'auto_center_zoom',
				'description',
				'width',
				'height',
				'latitude',
				'longitude',
				'show_info_window',
				'zoom',
				'layer_type'
			) ), $atts );

			$map_data['markers'] = array(
				array_merge(
					$marker_data,
					$marker->to_array( array( 'address', 'description', 'latitude', 'longitude', 'title', 'id', 'permalink' ) )
				)
			);

			$map_data = apply_filters( 'wpify_mapy_cz_marker_data', $map_data, $marker );

			add_filter( 'wpify_mapy_cz_shown', '__return_true' );

			return $this->templates->render( 'map', null, $map_data );
		}

		return '';
	}
}
