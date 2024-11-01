<?php

namespace WpifyMapyCz;

use WpifyMapyCz\Managers\ApiManager;
use WpifyMapyCz\Managers\BlocksManager;
use WpifyMapyCz\Managers\PostTypesManager;
use WpifyMapyCzDeps\Wpify\PluginUtils\PluginUtils;

final class Plugin {
	/**
	 * @var PluginUtils
	 */
	private $plugin_utils;

	public function __construct(
		ApiManager $api_manager,
		BlocksManager $blocks_manager,
		PostTypesManager $post_types_manager,
		Frontend $frontend,
		Admin $admin,
		PluginUtils $plugin_utils
	) {
		$this->plugin_utils = $plugin_utils;
	}

	public function get_icon() {
		return $this->plugin_utils->get_plugin_url( 'assets/images/mapy-cz.svg' );
	}

	/**
	 * @param bool $network_wide
	 */
	public function activate( bool $network_wide ) {
	}

	/**
	 * @param bool $network_wide
	 */
	public function deactivate( bool $network_wide ) {
	}

	/**
	 *
	 */
	public function uninstall() {
	}
}
