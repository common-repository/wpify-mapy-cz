<?php

namespace WpifyMapyCz\Blocks;

use WpifyMapyCz\Frontend;
use WpifyMapyCz\Managers\ApiManager;
use WpifyMapyCzDeps\Wpify\Asset\AssetFactory;
use WpifyMapyCzDeps\Wpify\PluginUtils\PluginUtils;

class MapyCzBlock {
	public const NAME = 'wpify/mapy-cz';

	/** @var AssetFactory */
	private $asset_factory;

	/** @var PluginUtils */
	private $plugin_utils;

	/** @var Frontend */
	private $frontend;

	public function __construct( AssetFactory $asset_factory, PluginUtils $plugin_utils, Frontend $frontend ) {
		$this->asset_factory = $asset_factory;
		$this->plugin_utils  = $plugin_utils;
		$this->frontend      = $frontend;

		$this->setup();
	}

	public function setup() {
		add_action( 'init', array( $this, 'register' ) );
	}

	public function register() {
		$script = $this->asset_factory->wp_script(
			$this->plugin_utils->get_plugin_path( 'build/wpify-mapy-cz-block.js' ),
			array(
				'auto_register'    => false,
				'text_domain'      => 'wpify-mapy-cz',
				'translation_path' => $this->plugin_utils->get_plugin_path( 'languages' ),
				'variables'        => array(
					'wpify_mapy_cz_block' => array(
						'maps_api' => ApiManager::NAMESPACE . '/maps',
					),
				)
			)
		)->register();

		$style = $this->asset_factory->wp_script(
			$this->plugin_utils->get_plugin_path( 'build/wpify-mapy-cz-block.css' ),
			array( 'auto_register' => false )
		)->register();

		register_block_type(
			self::NAME,
			array(
				'attributes'      => array(
					'map_id' => array(
						'type'    => 'string',
						'default' => null,
					),
				),
				'render_callback' => array( $this->frontend, 'render_map' ),
				'editor_script'   => $script,
				'editor_style'    => $style,
			)
		);
	}
}
