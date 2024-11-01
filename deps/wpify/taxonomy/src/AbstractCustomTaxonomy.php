<?php

namespace WpifyMapyCzDeps\Wpify\Taxonomy;

use WP_Taxonomy;
abstract class AbstractCustomTaxonomy
{
    /** @var WP_Taxonomy */
    private $taxonomy;
    public function __construct()
    {
        $this->init();
        $this->setup();
    }
    public function init()
    {
        add_action('init', array($this, 'register_taxonomy'));
    }
    public function setup()
    {
    }
    public function register_taxonomy()
    {
        if (!taxonomy_exists($this->get_taxonomy_key())) {
            $this->taxonomy = register_taxonomy($this->get_taxonomy_key(), $this->get_post_types(), $this->get_args());
        } else {
            $this->taxonomy = get_taxonomy($this->get_taxonomy_key());
        }
        if ($this->taxonomy) {
            foreach ($this->get_post_types() as $post_type) {
                register_taxonomy_for_object_type($this->get_taxonomy_key(), $post_type);
            }
        }
    }
    /** @return string */
    public abstract function get_taxonomy_key() : string;
    /**
     * @return string[]
     */
    public abstract function get_post_types() : array;
    /**
     * @return array
     */
    public function get_args() : array
    {
        return array();
    }
    /**
     * @return WP_Taxonomy
     */
    public function get_taxonomy() : WP_Taxonomy
    {
        return $this->taxonomy;
    }
    /**
     * @param string $singular Singular name of the taxonomy
     * @param string $plural Plural name of the taxonomy
     */
    protected function generate_labels(string $singular, string $plural) : array
    {
        return array(
            'name' => \sprintf(_x('%s', 'taxonomy general name', 'wpify-core'), \ucfirst($plural)),
            'singular_name' => \sprintf(_x('%s', 'taxonomy singular name', 'wpify-core'), \ucfirst($singular)),
            'search_items' => \sprintf(__('Search %s', 'wpify-core'), \ucfirst($plural)),
            'popular_items' => \sprintf(__('Popular %s', 'wpify-core'), \ucfirst($plural)),
            'all_items' => \sprintf(__('All %s', 'wpify-core'), \ucfirst($plural)),
            'parent_item' => \sprintf(__('Parent %s', 'wpify-core'), \ucfirst($singular)),
            'parent_item_colon' => \sprintf(__('Parent %s:', 'wpify-core'), \ucfirst($singular)),
            'edit_item' => \sprintf(__('Edit %s', 'wpify-core'), \ucfirst($singular)),
            'view_item' => \sprintf(__('View %s', 'wpify-core'), \ucfirst($singular)),
            'update_item' => \sprintf(__('Update %s', 'wpify-core'), \ucfirst($singular)),
            'add_new_item' => \sprintf(__('Add New %s', 'wpify-core'), \ucfirst($singular)),
            'new_item_name' => \sprintf(__('New %s Name', 'wpify-core'), \ucfirst($singular)),
            'separate_items_with_commas' => \sprintf(__('Separate %s with commas', 'wpify-core'), \strtolower($plural)),
            'add_or_remove_items' => \sprintf(__('Add or remove %s', 'wpify-core'), \strtolower($plural)),
            'choose_from_most_used' => \sprintf(__('Choose from the most used %s', 'wpify-core'), \strtolower($plural)),
            'not_found' => \sprintf(__('No %s found.', 'wpify-core'), \strtolower($plural)),
            'no_terms' => \sprintf(__('No %s', 'wpify-core'), \strtolower($plural)),
            'filter_by_item' => \sprintf(__('Filter by %s', 'wpify-core'), \strtolower($singular)),
            'items_list_navigation' => \sprintf(__('%s list navigation', 'wpify-core'), \ucfirst($plural)),
            'items_list' => \sprintf(__('%s list', 'wpify-core'), \ucfirst($plural)),
            /* translators: Tab heading when selecting from the most used terms. */
            'most_used' => \sprintf(_x('Most Used', $this->get_taxonomy_key(), 'wpify-core')),
            'back_to_items' => \sprintf(__('&larr; Go to %s', 'wpify-core'), \ucfirst($plural)),
            'item_link' => \sprintf(_x('%s Link', 'navigation link block title', 'wpify-core'), \ucfirst($singular)),
            'item_link_description' => \sprintf(_x('A link to a %s.', 'navigation link block description', 'wpify-core'), \strtolower($singular)),
        );
    }
}
