<?php

namespace Enfrte\WooApiProxy;

use Enfrte\WooApiProxy\Endpoints\AlpineJsEndpoint;
use Enfrte\WooApiProxy\Endpoints\HtmxEndpoint;
use Enfrte\WooApiProxy\Endpoints\CartProductEndpoint;
use Enfrte\WooApiProxy\Endpoints\HtmxClientSideTemplatesEndpoint;
use Enfrte\WooApiProxy\HtmlResponse;

class RegisterRestRoutes
{
	const ROUTE_PATH = 'woo-proxy/v1';

	public function __construct()
	{
		add_action('rest_api_init', [$this, 'register_routes']);

		add_filter('rest_pre_serve_request', function ($served, $result) {
			// The $served param is a boolean flag that indicates whether the request has already been served. 
			// By returning true, you’re explicitly saying: Yes, I’ve served this response myself. Stop here.
			// If you return false or $served, wp continues and tries to serve from HtmlResponse parent
			if ( $result instanceof HtmlResponse ) {
				return HtmlResponse::respond($result);
			}

			return $served;
		}, 10, 2);
	}

	public function register_routes()
	{
		register_rest_route(self::ROUTE_PATH, '/hello-alpine', [
			'methods'  => ['GET'],
			'callback' => [new AlpineJsEndpoint(), 'handle'],
			'permission_callback' => '__return_true',
		]);

		register_rest_route(self::ROUTE_PATH, '/hello-htmx', [
			'methods'  => ['GET'],
			'callback' => [new HtmxEndpoint(), 'handle'],
			'permission_callback' => '__return_true',
		]);

		register_rest_route(self::ROUTE_PATH, '/hello-htmx-client-side-templates', [
			'methods'  => ['GET'],
			'callback' => [new HtmxClientSideTemplatesEndpoint(), 'handle'],
			'permission_callback' => '__return_true',
		]);

		register_rest_route(self::ROUTE_PATH, '/cart-products', [
			'methods'  => ['POST', 'DELETE'],
			'callback' => [new CartProductEndpoint(), 'handle'],
			'permission_callback' => '__return_true',
		]);
	}

}
