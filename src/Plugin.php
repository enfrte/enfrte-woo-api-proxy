<?php
namespace Enfrte\WooApiProxy;

use Automattic\WooCommerce\Client;
use WP_REST_Request;
use WP_Error;

class Plugin {
	private $woocommerce;

	public function __construct() {
		$this->woocommerce = new Client(
			get_site_url(),
			$_ENV['WC_CONSUMER_KEY'] ?? '',
			$_ENV['WC_CONSUMER_SECRET'] ?? '',
			[
				'version' => 'wc/v3',
			]
		);

		add_action('rest_api_init', [$this, 'register_routes']);
	}

	public function register_routes() {
		register_rest_route('woo-proxy/v1', '/products', [
			'methods'  => 'GET',
			'callback' => [$this, 'get_products'],
			'permission_callback' => '__return_true',
		]);
	}

	public function get_products(WP_REST_Request $request) {
		try {
			$products = $this->woocommerce->get('products');
			return rest_ensure_response($products);
		} catch (\Exception $e) {
			return new WP_Error('api_error', $e->getMessage(), ['status' => 500]);
		}
	}
}
