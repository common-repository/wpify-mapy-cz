<?php

namespace WpifyMapyCz\Repositories;

use WpifyMapyCz\Models\MarkerModel;
use WpifyMapyCz\PostTypes\MarkerPostType;
use WpifyMapyCzDeps\Wpify\Model\Abstracts\AbstractPostRepository;

/**
 * @method MarkerModel get( $object = null )
 */
class MarkerRepository extends AbstractPostRepository {
	/**
	 * @inheritDoc
	 */
	static function post_type(): string {
		return MarkerPostType::KEY;
	}

	/**
	 * @inheritDoc
	 */
	public function model(): string {
		return MarkerModel::class;
	}
}
