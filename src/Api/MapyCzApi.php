<?php

namespace WpifyMapyCz\Api;

use WP_REST_Controller;
use WP_REST_Request;
use WP_REST_Server;
use WpifyMapyCz\Managers\ApiManager;
use WpifyMapyCz\Models\MapModel;
use WpifyMapyCz\Models\MarkerModel;
use WpifyMapyCz\Repositories\MapRepository;
use WpifyMapyCz\Repositories\MarkerRepository;

class MapyCzApi extends WP_REST_Controller {

	/** @var MarkerRepository */
	private $marker_repository;

	/** @var MapRepository */
	private $map_repository;

	public function __construct(
		MarkerRepository $marker_repository,
		MapRepository $map_repository
	) {
		$this->marker_repository = $marker_repository;
		$this->map_repository    = $map_repository;

		add_action( 'rest_api_init', array( $this, 'register_routes' ) );
	}

	/**
	 * Register the routes for the objects of the controller.
	 */
	public function register_routes() {
		register_rest_route(
			ApiManager::NAMESPACE,
			'markers',
			array(
				'methods'             => WP_REST_Server::READABLE,
				'callback'            => array( $this, 'get_markers' ),
				'permission_callback' => '__return_true',
			),
		);

		register_rest_route(
			ApiManager::NAMESPACE,
			'maps',
			array(
				'methods'             => WP_REST_Server::READABLE,
				'callback'            => array( $this, 'get_maps' ),
				'permission_callback' => '__return_true',
			),
		);

		register_rest_route(
			ApiManager::NAMESPACE,
			'taxonomies',
			array(
				'methods'             => WP_REST_Server::READABLE,
				'callback'            => array( $this, 'get_taxonomies' ),
				'permission_callback' => '__return_true',
			),
		);
	}

	public function get_markers( $request ) {
		$markers = [];

		/** @var MarkerModel $marker */
		foreach ( $this->marker_repository->all() as $marker ) {
			$markers[] = $marker->to_array();
		}

		return rest_ensure_response( $markers );
	}

	public function get_maps( $request ) {
		$maps = [];

		/** @var MapModel $map */
		foreach ( $this->map_repository->all() as $key => $map ) {
			$maps[ $key ]            = $map->to_array();
			$maps[ $key ]['markers'] = array_map( function ( $marker ) {
				return $marker->to_array();
			}, $map->markers );
		}

		return rest_ensure_response( $maps );
	}

	public function get_taxonomies( WP_REST_Request $request ) {
		$post_types = explode( ',', $request->get_param( 'post_types' ) );
		$taxonomies = array();

		foreach ( $post_types as $post_type ) {
			$post_type_taxonomies = get_object_taxonomies( $post_type, 'objects' );

			foreach ( $post_type_taxonomies as $post_type_taxonomy ) {
				$taxonomies[ $post_type_taxonomy->name ] = $post_type_taxonomy->label;
			}
		}

		return rest_ensure_response( $taxonomies );
	}
}
