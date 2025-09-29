<?php

class HelloEndpoint
{
    // public function handle(\WP_REST_Request $request)
    // {
    //     return [
    //         'message' => 'Hello from HelloEndpoint class!',
    //     ];
    // }

    public function handle(\WP_REST_Request $request) {
        try {
            return new \WP_REST_Response([
                'message' => 'Hello from HelloEndpoint class!',
                'status' => 'success'
            ], 200);
        } 
        catch (Exception $e) {
            return new \WP_Error('endpoint_error', $e->getMessage(), ['status' => 500]);
        }
    }

}
