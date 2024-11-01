<?php

use WpifyMapyCzDeps\DI\Definition\Helper\CreateDefinitionHelper;
use WpifyMapyCzDeps\Wpify\CustomFields\CustomFields;
use WpifyMapyCzDeps\Wpify\PluginUtils\PluginUtils;
use WpifyMapyCzDeps\Wpify\Templates\WordPressTemplates;

return array(
	CustomFields::class      => ( new CreateDefinitionHelper() )
		->constructor( plugins_url( 'deps/wpify/custom-fields', __FILE__ ) ),
	WordPressTemplates::class => ( new CreateDefinitionHelper() )
		->constructor( array( __DIR__ . '/templates' ) ),
	PluginUtils::class       => ( new CreateDefinitionHelper() )
		->constructor( __DIR__ . '/wpify-mapy-cz.php' ),
);
