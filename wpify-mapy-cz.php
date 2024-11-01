<?php
/*
 * Plugin Name:       WPify Mapy.cz
 * Description:       Easily embed free mapy.cz maps anywhere
 * Version:           3.1.11
 * Requires PHP:      7.4
 * Requires at least: 6.0
 * Tested up to:      6.4
 * Author:            WPify
 * Author URI:        https://www.wpify.io/
 * License:           GPL v2 or later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain:       wpify-mapy-cz
 * Domain Path:       /languages
*/

use WpifyMapyCz\Plugin;
use WpifyMapyCzDeps\DI\Container;
use WpifyMapyCzDeps\DI\ContainerBuilder;
use WpifyMapyCzDeps\Wpify\Asset\AssetFactory;
use WpifyMapyCzDeps\Wpify\PluginUtils\PluginUtils;
use WpifyMapyCzDeps\Wpify\Template\WordPressTemplate;

if ( ! defined( 'WPIFY_MAPY_CZ_MIN_PHP_VERSION' ) ) {
	define( 'WPIFY_MAPY_CZ_MIN_PHP_VERSION', '7.4.0' );
}

/**
 * @return Plugin
 * @throws Exception
 */
function wpify_mapy_cz(): Plugin {
	return wpify_mapy_cz_container()->get( Plugin::class );
}

/**
 * @return Container
 * @throws Exception
 */
function wpify_mapy_cz_container(): Container {
	static $container;

	if ( empty( $container ) ) {
		$definition       = require_once __DIR__ . '/config.php';
		$containerBuilder = new ContainerBuilder();
		$containerBuilder->addDefinitions( $definition );
		$container = $containerBuilder->build();
	}

	return $container;
}

function wpify_mapy_cz_activate( $network_wide ) {
	wpify_mapy_cz()->activate( $network_wide );
}

function wpify_mapy_cz_deactivate( $network_wide ) {
	wpify_mapy_cz()->deactivate( $network_wide );
}

function wpify_mapy_cz_uninstall() {
	wpify_mapy_cz()->uninstall();
}

function wpify_mapy_cz_php_upgrade_notice() {
	$info = get_plugin_data( __FILE__ );

	echo sprintf(
		__( '<div class="error notice"><p>Opps! %s requires a minimum PHP version of %s. Your current version is: %s. Please contact your host to upgrade.</p></div>', 'wpify-mapy-cz' ),
		$info['Name'],
		WPIFY_MAPY_CZ_MIN_PHP_VERSION,
		PHP_VERSION
	);
}

function wpify_mapy_cz_php_vendor_missing() {
	$info = get_plugin_data( __FILE__ );

	echo sprintf(
		__( '<div class="error notice"><p>Opps! %s is corrupted it seems, please re-install the plugin.</p></div>', 'wpify-mapy-cz' ),
		$info['Name']
	);
}

if ( version_compare( PHP_VERSION, WPIFY_MAPY_CZ_MIN_PHP_VERSION ) < 0 ) {
	add_action( 'admin_notices', 'wpify_mapy_cz_php_upgrade_notice' );
} else {
	$deps_loaded   = false;
	$vendor_loaded = false;

	$deps = array_filter( array( __DIR__ . '/deps/scoper-autoload.php', __DIR__ . '/deps/autoload.php' ), function ( $path ) {
		return file_exists( $path );
	} );

	foreach ( $deps as $dep ) {
		include_once $dep;
		$deps_loaded = true;
	}

	if ( file_exists( __DIR__ . '/vendor/autoload.php' ) ) {
		include_once __DIR__ . '/vendor/autoload.php';
		$vendor_loaded = true;
	}

	if ( $deps_loaded && $vendor_loaded ) {
		wpify_mapy_cz();
		register_activation_hook( __FILE__, 'wpify_mapy_cz_activate' );
		register_deactivation_hook( __FILE__, 'wpify_mapy_cz_deactivate' );
		register_uninstall_hook( __FILE__, 'wpify_mapy_cz_uninstall' );
	} else {
		add_action( 'admin_notices', 'wpify_mapy_cz_php_vendor_missing' );
	}
}

function wpify_mapy_cz_render( array $map_data ) {
	$template      = wpify_mapy_cz_container()->get( WordPressTemplate::class );
	$asset_factory = wpify_mapy_cz_container()->get( AssetFactory::class );
	$utils         = wpify_mapy_cz_container()->get( PluginUtils::class );

	$asset_factory->wp_script( $utils->get_plugin_path( 'build/plugin.js' ) )->enqueue();
	return $template->render( 'map', null, $map_data );
}
