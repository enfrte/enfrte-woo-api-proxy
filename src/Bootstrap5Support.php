<?php

class BootStrap5Support {
    public function __construct() {
        add_action( 'wp_enqueue_scripts', [ $this, 'enqueue_assets' ] );
        add_shortcode( 'bs5_products', [ $this, 'render_products' ] );
    }

    public function enqueue_assets() {
        // Bootstrap 5 CSS
        wp_enqueue_style(
            'bootstrap5',
            'https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css',
            [],
            '5.3.3'
        );

        // Bootstrap 5 JS (needs Popper, bundled already)
        wp_enqueue_script(
            'bootstrap5',
            'https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js',
            [],
            '5.3.3',
            true
        );
    }

}
