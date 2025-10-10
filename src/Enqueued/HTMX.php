<?php

namespace Enfrte\WooApiProxy\Enqueued;

class HTMX {
    public function __construct() {
        add_action( 'wp_enqueue_scripts', [ $this, 'enqueue_assets' ] );
    }

    public function enqueue_assets() {
        // htmx core
        wp_enqueue_script(
            'htmx',
            'https://unpkg.com/htmx.org@2.0.4/dist/htmx.min.js',
            [], // dependencies
            null, // version
            false // false = load in head
        );
        
        // HTMX Extension 
        wp_enqueue_script(
            'htmx-ext-client-side-templates',
            'https://unpkg.com/htmx-ext-client-side-templates@2.0.1/dist/client-side-templates.min.js',
            ['htmx'], // dependency on htmx
            null,
            false
        );
    }

}
