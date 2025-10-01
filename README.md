# wp-plugins

## base64-encoder

Example of a simple plugin with some UI elements and JS interaction

## enfrte-woo-api-proxy

My own attempt of a headless WooCommerce shop. Here is the included stack 

* Bootstrap 5
* HTMX
* [wc-api-php](https://github.com/woocommerce/wc-api-php) - wrapper for the woocommerce api although it's also quite easy to build your own.
* AlpineJS with (handlebars) templating extension for handling responce json (still evaluating this).
* Dependencies managed with Composer with PSR4 autoloading. 
* Latte PHP templating (not implemented yet)

## Installation 

Auth key example is in .env.example - Switch out with your own keys. 

Run `composer install`

Zip it up. 

Install it with wordpress.

I recommend https://marketplace.visualstudio.com/items?itemName=humao.rest-client for testing endpoints. It even works with xdebug. 

```

### Standard call to the API. Requires auth. 

GET /wp-json/wc/v3/products
Authorization: Basic <generate base64 token here from the API 2 keys you get from woocommerce>

### Here is my custom api woo-proxy call. The Basic authentication is handled on the backend. 

GET /wp-json/woo-proxy/v1/hello

### Three hashes separate the endpoint calls

GET /wp-json/woo-proxy/v1/products

```

### Example of custom php wrapper

I guess this is only required if you are accessing the endpoints from another domain. 

```php

class My_WC_API {
    private $ck;
    private $cs;
    private $base;

    public function __construct($ck, $cs, $site_url = null) {
        $this->ck   = $ck;
        $this->cs   = $cs;
        $this->base = ($site_url ?: home_url()) . '/wp-json/wc/v3/';
    }

    public function get($endpoint, $params = []) {
        $url = add_query_arg($params, $this->base . ltrim($endpoint, '/'));

        $response = wp_remote_get($url, [
            'headers' => [
                'Authorization' => 'Basic ' . base64_encode("{$this->ck}:{$this->cs}"),
            ],
        ]);

        if (is_wp_error($response)) {
            return $response;
        }

        return json_decode(wp_remote_retrieve_body($response), true);
    }
}


// Register endpoint on REST init
add_action('rest_api_init', function () {
    register_rest_route('myplugin/v1', '/products', [
        'methods'  => 'GET',
        'callback' => 'myplugin_get_products',
        'permission_callback' => '__return_true', // for testing, open access
    ]);
});

function myplugin_get_products(WP_REST_Request $request) {
    $api = new My_WC_API('ck_your_consumer_key', 'cs_your_consumer_secret');

    $products = $api->get('products', [
        'per_page' => 10,
        'page'     => 1,
    ]);

    if (is_wp_error($products)) {
        return new WP_Error('api_error', $products->get_error_message(), ['status' => 500]);
    }

    return rest_ensure_response($products);
}

```