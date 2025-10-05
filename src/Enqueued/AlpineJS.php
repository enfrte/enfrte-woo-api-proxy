<?php

namespace Enfrte\WooApiProxy\Enqueued;

class AlpineJS {
    public function __construct() {
        add_action( 'wp_enqueue_scripts', [ $this, 'enqueue_assets' ] );
    }

    public function enqueue_assets() {
        wp_enqueue_script(
            'alpinejs',
            'https://cdn.jsdelivr.net/npm/alpinejs@3.15.0/dist/cdn.min.js',
            [],
            '3.15.0',
            true
        );
    }

}
