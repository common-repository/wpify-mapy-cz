# WPify Taxonomy

Abstraction over WordPress Taxonomies.

## Installation

`composer require wpify/taxonomy`

## Usage

```php
class MyCustomTaxonomy extends Wpify\Taxonomy\AbstractCustomTaxonomy {
    const KEY = 'my-custom-taxonomy';
    
    public function setup() {
        add_action( 'init', array( $this, 'do_something' ) );
    }
    
	public function get_taxonomy_key(): string {
		return self::KEY;
	}
	
	public function get_args(): array {
		$singular = _x( 'My Taxonomy', 'post type singular name', 'my-plugin' );
		$plural   = _x( 'My Taxonomies', 'post type name', 'my-plugin' );

		return array(
			'labels'            => $this->generate_labels( $singular, $plural ),
			'description'       => __( 'My custom taxonomy', 'my-plugin' ),
			'public'            => true,
			'hierarchical'      => false,
			'show_in_rest'      => true,
			'show_admin_column' => true,
		);
	}
    
    public function get_post_types(): array {
		return array( 'my-custom-post-type', 'page' );
	}

    public function do_something() {
        // TODO: Do something
    }
}

function my_plugin_init() {
    new MyCustomTaxonomy;
}

add_action( 'plugins_loaded', 'my_plugin_init', 11 );
```