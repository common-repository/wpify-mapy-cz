<?php

namespace WpifyMapyCz;

use WpifyMapyCz\Managers\ApiManager;
use WpifyMapyCz\PostTypes\MapPostType;
use WpifyMapyCz\PostTypes\MarkerPostType;
use WpifyMapyCzDeps\Wpify\Asset\AssetFactory;
use WpifyMapyCzDeps\Wpify\PluginUtils\PluginUtils;

class Admin {
	/** @var PluginUtils */
	private $plugin_utils;

	/** @var AssetFactory */
	private $asset_factory;

	public function __construct(
			PluginUtils $plugin_utils,
			AssetFactory $asset_factory
	) {
		$this->plugin_utils  = $plugin_utils;
		$this->asset_factory = $asset_factory;

		if ( is_admin() ) {
			$this->setup();
		}
	}

	/**
	 * Register hooks
	 * @return bool|void
	 */
	public function setup() {
		add_action( 'admin_menu', array( $this, 'add_menu_pages' ) );
		add_filter( 'manage_' . MapPostType::KEY . '_posts_columns', array( $this, 'add_admin_columns' ), 10 );
		add_action( 'manage_' . MapPostType::KEY . '_posts_custom_column', array( $this, 'admin_columns_content' ), 10, 2 );
		add_filter( 'plugin_action_links_wpify-mapy-cz/wpify-mapy-cz.php', array( $this, 'add_action_links' ) );
		add_action( 'init', array( $this, 'enqueue' ), 0 );
		add_filter( 'wcf_parse_mapycz_marker_value_callback', array( $this, 'get_group_parser' ) );
		add_filter( 'wcf_parse_mapycz_map_value_callback', array( $this, 'get_group_parser' ) );
		add_filter( 'wcf_sanitize_mapycz_marker_value_callback', array( $this, 'get_group_sanitizer' ) );
		add_filter( 'wcf_sanitize_mapycz_map_value_callback', array( $this, 'get_group_sanitizer' ) );
	}

	public function get_group_parser( $callable ) {
		return function ( $value ) {
			if ( is_serialized_string( $value ) ) {
				$value = maybe_unserialize( $value );
			} else if ( is_string( $value ) ) {
				$value = json_decode( $value, true );
			}

			if ( is_array( $value ) ) {
				return $value;
			}

			return array();
		};
	}

	public function get_group_sanitizer( $callable ) {
		return function ( $value ) {
			if ( is_string( $value ) ) {
				$value = json_decode( $value, true );
			}

			if ( is_array( $value ) ) {
				return $value;
			}

			return array();
		};
	}

	public function enqueue() {
		$this->asset_factory->admin_wp_script( $this->plugin_utils->get_plugin_path( 'build/marker-field.js' ) );
		$this->asset_factory->admin_wp_script( $this->plugin_utils->get_plugin_path( 'build/map-field.js' ), array(
				'variables' => array(
						'wpify_mapy_cz' => array(
								'markers_api' => ApiManager::NAMESPACE . '/markers',
						),
				),
		) );
	}

	/**
	 * Add admin menu pages
	 */
	public function add_menu_pages() {
		add_menu_page(
				__( 'Mapy.cz', 'wpify-mapy-cz' ),
				__( 'Mapy.cz', 'wpify-mapy-cz' ),
				apply_filters( 'mapy_cz_menu_capability', 'manage_options' ),
				'wpify-mapy-cz',
				array( $this, 'render_main_menu_page' ),
				'data:image/svg+xml;base64,' . base64_encode( file_get_contents( $this->plugin_utils->get_plugin_path( 'assets/images/mapy-cz.svg' ) ) ),
				30
		);
		add_submenu_page(
				'wpify-mapy-cz',
				__( 'Maps', 'wpify-mapy-cz' ),
				__( 'Maps', 'wpify-mapy-cz' ),
				apply_filters( 'mapy_cz_menu_capability', 'manage_options' ),
				$this->get_admin_url_maps(),
				null
		);
		add_submenu_page(
				'wpify-mapy-cz',
				__( 'Markers', 'wpify-mapy-cz' ),
				__( 'Markers', 'wpify-mapy-cz' ),
				apply_filters( 'mapy_cz_menu_capability', 'manage_options' ),
				$this->get_admin_url_markers(),
				null
		);
	}

	/**
	 * Get admin URL for maps
	 * @return string
	 */
	public function get_admin_url_maps() {
		return sprintf( 'edit.php?post_type=%s', MapPostType::KEY );
	}

	/**
	 * Get admin URL for markers
	 * @return string
	 */
	public function get_admin_url_markers() {
		return sprintf( 'edit.php?post_type=%s', MarkerPostType::KEY );
	}

	/**
	 * Render main admin menu page
	 */
	public function render_main_menu_page() { ?>
		<div class="wrap">
			<h2><?php _e( 'WPify Mapy.cz', 'wpify-mapy-cz' ) ?></h2>
			<p><?php printf(
						__(
								'Welcome! Plugin WPify Mapy.cz by <a href="%s" target="_blank">wpify.io</a> lets you add mapy.cz maps to your site easily!',
								'wpify-mapy-cz'
						),
						'https://wpify.io'
				) ?></p>
			<p><?php printf(
						__(
								'You can start adding <a href="%s">maps</a> and <a href="%s">markers</a> right away.',
								'wpify-mapy-cz'
						),
						$this->get_admin_url_maps(),
						$this->get_admin_url_markers()
				) ?></p>
			<h3><?php _e( 'How to create map and insert it to your page', 'wpify-mapy-cz' ); ?></h3>
			<ol>
				<li><?php printf(
							__( 'Add as many <a href="%s">markers</a> as needed, select it\'s positions and publish them.', 'wpify-mapy-cz' ),
							$this->get_admin_url_markers()
					) ?>
				</li>
				<li><?php printf(
							__(
									'Add a new <a href="%s">map</a>, enter the options, choose markers to display and click Publish.',
									'wpify-mapy-cz'
							),
							$this->get_admin_url_maps()
					) ?>
				</li>
				<li><?php printf(
							__(
									'To insert the map into your page, you can either use <i>Mapy.cz</i> Gutenberg block, or you can use the shortcode provided in the maps <a href="%s">admin listing</a>',
									'wpify-mapy-cz'
							),
							$this->get_admin_url_maps()
					) ?>
				</li>
			</ol>
		</div>
	<?php }

	public function add_admin_columns( $columns ) {
		$columns['shortcode'] = __( 'Shortcode', 'wpify-mapy-cz' );

		return $columns;
	}

	public function admin_columns_content( $column_name, $post_id ) {
		switch ( $column_name ) {
			case 'shortcode':
				printf( '[wpify_mapy_cz id="%s"]', $post_id );
				break;
		}
	}

	public function add_action_links( $links ) {
		$before = array(
				'settings' => sprintf( '<a href="%s">%s</a>', admin_url( 'admin.php?page=wpify-mapy-cz' ), __( 'Settings', 'wpify-mapy-cz' ) )
		);

		$after = array(
				'wpify' => sprintf( '<a href="%s" target="_blank">%s</a>', 'https://wpify.io', __( 'Get more plugins and support', 'wpify-mapy-cz' ) )
		);

		return array_merge( $before, $links, $after );
	}
}
