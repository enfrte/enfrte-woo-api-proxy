<?php

namespace Enfrte\WooApiProxy\Enqueued;

class Handlebars {
    public function __construct() {
        add_action( 'wp_enqueue_scripts', [ $this, 'enqueue_assets' ] );
    }

    public function enqueue_assets() {
        wp_enqueue_script(
            'handlebars',
            'https://cdnjs.cloudflare.com/ajax/libs/handlebars.js/4.7.8/handlebars.min.js',
            [],
            null,
            false
        );
    }
}
