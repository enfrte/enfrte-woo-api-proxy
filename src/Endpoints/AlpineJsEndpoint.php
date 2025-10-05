<?php

namespace Enfrte\WooApiProxy\Endpoints;

use Exception;
use WP_REST_Request;
use WP_REST_Response;
use WP_Error;

class AlpineJsEndpoint
{
    public function handle(WP_REST_Request $request) {
        try {
            return new WP_REST_Response([
                'message' => 'Hello from AlpineJsEndpoint class!',
                'status' => 'success'
            ], 200);
        } 
        catch (Exception $e) {
            return new WP_Error('endpoint_error', $e->getMessage(), ['status' => 500]);
        }
    }

}
