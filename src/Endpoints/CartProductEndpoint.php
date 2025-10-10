<?php

namespace Enfrte\WooApiProxy\Endpoints;

// use Automattic\WooCommerce\Client;
use WC_Session_Handler;
use WC_Cart;
use WP_Error;
use WP_REST_Request;
use Exception;
use Enfrte\WooApiProxy\Libs\LatteEngine;
use Enfrte\WooApiProxy\HtmlResponse;
use WP_REST_Response;


/**
 * TODO: Leave handle. Break out the other methods into helper class for other endpoints.
 */
class CartProductEndpoint
{
	/**
	 * @var WP_REST_Request
	 */
	protected $request;

	/**
	 * Entry point for requests
	 * 
	 * @param WP_REST_Request $request
	 */
	public function handle(WP_REST_Request $request)
	{
		try {
			$this->request = $request;
			$method = $request->get_method();
			return $this->$method(); // the handle method needs to return beacuse it's the callback of the register_rest_route
		} 
		catch (Exception $e) {
			return $this->error('endpoint_error', $e->getMessage(), 500);
		}
	}


	function POST()  {
		$data = $this->parseRequest($this->request);

		$product_id = absint($data['product_id'] ?? 0);
		$quantity   = absint($data['quantity'] ?? 1);

		if (!$product_id) {
			return $this->error('no_product', 'Product ID is required', 400);
		}

		$cart_id = $this->resolveCartId($data);

		$this->initWooSession($cart_id);

		WC()->cart->add_to_cart($product_id, $quantity);

		return new HtmlResponse(
			LatteEngine::latteRenderToString(
				'cart_products.latte', 
				[
					'success'    => true,
					'cart_id'    => $cart_id,
					'product_id' => $product_id,
					'quantity'   => $quantity,
					'cart_action_text' => 'Remove from cart',
					'cart_count' => WC()->cart->get_cart_contents_count(),
					'cart_items' => WC()->cart->get_cart(),
					'cart_total' => WC()->cart->get_cart_total(),
				]
			)
		);

		// return rest_ensure_response([
		// 	'success'    => true,
		// 	'cart_id'    => $cart_id,
		// 	'product_id' => $product_id,
		// 	'quantity'   => $quantity,
		// 	'remove_from_cart_text' => 'Remove from cart',
		// 	'cart_count' => WC()->cart->get_cart_contents_count(),
		// 	'cart_items' => WC()->cart->get_cart(),
		// 	'cart_total' => WC()->cart->get_cart_total(),
		// ]);
	}

	/**
	 * @param WP_REST_Request $request
	 * @return array
	 */
	private function parseRequest(WP_REST_Request $request): array
	{
		$params = $request->get_body_params();
		$json   = $request->get_json_params();

		return !empty($json) ? $json : $params;
	}


	/**
	 * @param array $data
	 * @return string
	 */
	private function resolveCartId(array $data): string
	{
		$cookie_name = 'wp_woocommerce_session_' . COOKIEHASH;

		// Priority: explicit cart_id in payload
		if (!empty($data['cart_id'])) {
			return sanitize_text_field($data['cart_id']);
		}

		// Otherwise parse from Woo session cookie
		if (!empty($_COOKIE[$cookie_name])) {
			$parts = explode('||', $_COOKIE[$cookie_name]);
			if (!empty($parts[0])) {
				return sanitize_text_field($parts[0]);
			}
		}

		// Otherwise new id
		return wp_generate_uuid4();
	}


	/**
	 * @param string $cart_id
	 * @return void
	 */
	private function initWooSession(string $cart_id): void
	{
		WC()->session = new WC_Session_Handler();
		WC()->session->init();
		WC()->session->set_customer_session_cookie($cart_id);

		WC()->cart = new WC_Cart();
		WC()->cart->get_cart_from_session();
	}


	/**
	 * @param string $code
	 * @param string $message
	 * @param integer $status
	 * @return WP_Error
	 */
	private function error(string $code, string $message, int $status): WP_Error
	{
		return new WP_Error($code, $message, ['status' => $status]);
	}

}
