<?php

namespace WpifyMapyCz\Relations;

use WpifyMapyCz\Repositories\MarkerRepository;
use WpifyMapyCzDeps\Wpify\Model\Interfaces\RelationInterface;

class MapMarkerRelation implements RelationInterface {

	/**
	 * @var MarkerRepository
	 */
	private $marker_repository;
	/**
	 * @var array
	 */
	private $markers;

	public function __construct( MarkerRepository $marker_repository, array $markers ) {
		$this->marker_repository = $marker_repository;
		$this->markers           = $markers;
	}

	public function fetch() {
		return $this->marker_repository->find( array( 'post__in' => $this->markers ) );
	}

	public function assign() {
	}
}
