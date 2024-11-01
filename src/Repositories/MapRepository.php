<?php

namespace WpifyMapyCz\Repositories;

use WpifyMapyCz\Models\MapModel;
use WpifyMapyCz\Models\MarkerModel;
use WpifyMapyCz\PostTypes\MapPostType;
use WpifyMapyCzDeps\Wpify\Model\Abstracts\AbstractPostRepository;

/**
 * @method MarkerModel get( $object = null )
 */
class MapRepository extends AbstractPostRepository {
	/** @var MarkerRepository */
	private $marker_repository;

	public function __construct(
		MarkerRepository $marker_repository
	) {
		$this->marker_repository = $marker_repository;
	}

	/**
	 * @inheritDoc
	 */
	static function post_type(): string {
		return MapPostType::KEY;
	}

	/**
	 * @inheritDoc
	 */
	public function model(): string {
		return MapModel::class;
	}

	public function get_marker_repository() {
		return $this->marker_repository;
	}
}
