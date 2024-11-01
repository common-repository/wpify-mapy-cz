<?php

namespace WpifyMapyCz\Models;

use WpifyMapyCz\Relations\MapMarkerRelation;
use WpifyMapyCz\Repositories\MapRepository;
use WpifyMapyCzDeps\Wpify\Model\Abstracts\AbstractPostModel;

/**
 * @method MapRepository model_repository()
 */
class MapModel extends AbstractPostModel {
	/** @var int */
	public $id;

	/** @var array */
	public $_mapy_cz_data;

	/** @var bool */
	public $auto_center_zoom;

	/** @var string */
	public $description;

	/** @var string */
	public $width;

	/** @var string */
	public $height;

	/** @var float */
	public $latitude;

	/** @var float */
	public $longitude;

	/** @var bool */
	public $show_info_window;

	/** @var int */
	public $zoom;

	/** @var MarkerModel[] */
	public $markers;

	/** @var string */
	public $layer_type;

	public function get_auto_center_zoom(): bool {
		return $this->_mapy_cz_data['auto_center_zoom'] ?? false;
	}

	public function get_description(): string {
		return $this->_mapy_cz_data['description'] ?? '';
	}

	public function get_width(): string {
		return $this->_mapy_cz_data['width'] ?? '100%';
	}

	public function get_height(): string {
		return $this->_mapy_cz_data['height'] ?? '400px';
	}

	public function get_latitude(): float {
		return $this->_mapy_cz_data['latitude'] ?? 0;
	}

	public function get_longitude(): float {
		return $this->_mapy_cz_data['longitude'] ?? 0;
	}

	public function get_show_info_window(): bool {
		return $this->_mapy_cz_data['show_info_window'] ?? false;
	}

	public function get_zoom(): int {
		return $this->_mapy_cz_data['zoom'] ?? 1;
	}

	public function get_layer_type(): string {
		return $this->_mapy_cz_data['layer_type'] ?? 'DEF_BASE';
	}

	public function markers_relation(): MapMarkerRelation {
		$markers = array( -1 );

		if ( is_array( $this->_mapy_cz_data ) && isset( $this->_mapy_cz_data['markers'] ) ) {
			$markers = $this->_mapy_cz_data['markers'];
		}

		return new MapMarkerRelation(
			$this->model_repository()->get_marker_repository(),
			$markers
		);
	}
}
