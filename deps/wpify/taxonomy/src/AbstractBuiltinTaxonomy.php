<?php

namespace WpifyMapyCzDeps\Wpify\Taxonomy;

use WP_Taxonomy;
abstract class AbstractBuiltinTaxonomy implements TaxonomyInterface
{
    /** @var WP_Taxonomy */
    private $taxonomy;
    public function __construct()
    {
        $this->init();
        $this->setup();
    }
    private function init()
    {
        add_action('init', array($this, 'resolve_taxonomy'), \PHP_INT_MAX);
    }
    public function setup()
    {
    }
    public function resolve_taxonomy()
    {
        $this->taxonomy = get_taxonomy($this->get_taxonomy_key());
        if (!$this->taxonomy || is_wp_error($this->taxonomy)) {
            throw new TaxonomyException("Cannot resolve taxonomy " . $this->get_taxonomy_key());
        }
    }
    public abstract function get_taxonomy_key() : string;
    public function get_args() : array
    {
        return \json_decode(\json_encode($this->get_taxonomy()), \true);
    }
    public function get_taxonomy() : ?WP_Taxonomy
    {
        return $this->taxonomy;
    }
}
