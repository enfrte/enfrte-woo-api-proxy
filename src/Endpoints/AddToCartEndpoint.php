<?php

class AddToCartEndpoint
{
    private $woocommerce;

    public function __construct(Client $woocommerce) {
        $this->woocommerce = $woocommerce;
    }

    public function handle(\WP_REST_Request $request)
    {
        try {
            $product_id = absint( $request['product_id'] ?? 0 );
            $quantity   = absint( $request['quantity'] ?? 1 );

            if ( ! $product_id ) {
                return new WP_Error( 'no_product', 'Product ID is required', [ 'status' => 400 ] );
            }

            // Add to cart code
        } catch (Exception $e) {
            return new WP_Error('endpoint_error', $e->getMessage(), ['status' => 500]);
        }
    }
}
