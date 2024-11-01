<?php

namespace WpifyMapyCz\Models;

use WpifyMapyCz\Repositories\MarkerRepository;
use WpifyMapyCzDeps\Wpify\Model\Abstracts\AbstractPostModel;

/**
 * @method MarkerRepository model_repository()
 */
class MarkerModel extends AbstractPostModel {
	/** @var array */
	public $_mapy_cz_data;

	/** @var string */
	public $address;

	/** @var string */
	public $description;

	/** @var float */
	public $latitude;

	/** @var float */
	public $longitude;

	/** @var int */
	public $zoom;

	/** @var string */
	public $permalink;

	/**
	 * @return string
	 */
	public function get_address(): string {
		return $this->_mapy_cz_data['address'] ?? '';
	}

	/**
	 * @return string
	 */
	public function get_description(): string {
		return $this->_mapy_cz_data['description'] ?? '';
	}

	/**
	 * @return float
	 */
	public function get_latitude(): float {
		return $this->_mapy_cz_data['latitude'] ?? 0;
	}

	/**
	 * @return float
	 */
	public function get_longitude(): float {
		return $this->_mapy_cz_data['longitude'] ?? 0;
	}

	/**
	 * @return int
	 */
	public function get_zoom(): int {
		return $this->_mapy_cz_data['zoom'] ?? 0;
	}

	/**
	 * @return string|null
	 */
	public function get_permalink(): ?string {
		$permalink = get_permalink( $this->id );

		if ( is_string( $permalink ) ) {
			return $permalink;
		}

		return null;
	}
}
