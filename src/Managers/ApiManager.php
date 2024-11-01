<?php

namespace WpifyMapyCz\Managers;

use WpifyMapyCz\Api\MapyCzApi;

final class ApiManager {
	public const NAMESPACE = 'wpify-mapy-cz/v1';

	public function __construct(
		MapyCzApi $example_api
	) {
	}
}
