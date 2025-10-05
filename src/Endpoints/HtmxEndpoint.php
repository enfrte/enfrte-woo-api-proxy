<?php

namespace Enfrte\WooApiProxy\Endpoints;

use Exception;
use WP_REST_Request;
use WP_REST_Response;
use WP_HTTP_Response;
use WP_Error;
use Enfrte\WooApiProxy\Libs\LatteEngine;
use Enfrte\WooApiProxy\HtmlResponse;

class HtmxEndpoint
{
    public function handle(WP_REST_Request $request) {
        try {
            // return new HtmlResponse('<h1>HTML!</h1>');

            return new HtmlResponse(
                LatteEngine::latteRenderToString('index.latte', ['name' => ' HTMX'])
            );
        } 
        catch (Exception $e) {
            return new WP_Error('endpoint_error', $e->getMessage(), ['status' => 500]);
        }
    }

}
