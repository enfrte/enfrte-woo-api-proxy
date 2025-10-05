<?php

namespace Enfrte\WooApiProxy;

use Enfrte\WooApiProxy\Endpoints\HelloEndpoint;
use Enfrte\WooApiProxy\Endpoints\AddToCartEndpoint;

class RegisterRestRoutes
{
	const ROUTE_PATH = 'woo-proxy/v1';

	public function __construct()
	{
		add_action('rest_api_init', [$this, 'register_routes']);

		add_filter('rest_pre_serve_request', function ($served, $result) {
			// The $served param is a boolean flag that indicates whether the request has already been served. By returning true, you’re explicitly saying:
			// Yes, I’ve served this response myself. Stop here.
			// If you return false or $served, wp continues and tries to serve from HtmlResponse parent

			if ( $result instanceof HtmlResponse ) {
				return HtmlResponse::respond($result);
			}
		
			return $served;
		}, 10, 2);

	}

	public function register_routes()
	{
		register_rest_route(self::ROUTE_PATH, '/hello', [
			'methods'  => 'GET',
			'callback' => [new HelloEndpoint(), 'handle'],
			'permission_callback' => '__return_true',
		]);

		register_rest_route(self::ROUTE_PATH, '/add-to-cart', [
			'methods'  => 'POST',
			'callback' => [new AddToCartEndpoint(), 'handle'],
			'permission_callback' => '__return_true',
		]);
	}

}
