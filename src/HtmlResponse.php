<?php

namespace Enfrte\WooApiProxy;

use WP_REST_Response;

/**
 * Override the WP_REST_Response to instead return html
 */
class HtmlResponse extends WP_REST_Response
{
	// We need this just to know when to return html in rest_pre_serve_request. Maybe there is a better way(?)
	public static function respond(WP_REST_Response $result): bool {
		// Set the header with php because we short-circuit the WP_HTTP_Response object - wp never gets the chance to send the headers.
		header('Content-Type: text/html; charset=UTF-8'); 
		echo $result->get_data(); // Data passed to WP_REST_Response - should be a string
		return true; // tells wp: don't touch it anymore, else it will json_encode it.
	}
}
