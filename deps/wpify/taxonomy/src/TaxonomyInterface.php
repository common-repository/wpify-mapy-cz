<?php

namespace WpifyMapyCzDeps\Wpify\Taxonomy;

use WP_Taxonomy;
interface TaxonomyInterface
{
    public function setup();
    public function get_taxonomy_key() : string;
    public function get_taxonomy() : ?WP_Taxonomy;
    public function get_args() : array;
}
