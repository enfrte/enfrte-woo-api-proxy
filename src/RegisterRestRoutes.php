<?php

class RegisterRestRoutes
{
    const ROUTE_PATH = 'woo-proxy/v1';

    private $woocommerce;

    public function __construct()
    {
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

    public function register_routes()
    {
        // Hello endpoint
        $hello = new HelloEndpoint();
        register_rest_route(self::ROUTE_PATH, '/hello', [
            'methods'  => 'GET',
            'callback' => [$hello, 'handle'],
            'permission_callback' => '__return_true',
        ]);

        // AddToCartEndpoint endpoint
        $addToCart = new AddToCartEndpoint( $this->$woocommerce );
        register_rest_route(self::ROUTE_PATH, '/add-to-cart', [
            'methods'  => 'POST',
            'callback' => [$addToCart, 'handle'],
            'permission_callback' => '__return_true',
        ]);
    }
}
