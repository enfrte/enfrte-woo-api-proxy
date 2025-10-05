<?php
/**
 * Plugin Name: WooCommerce API Proxy
 * Description: Adds custom endpoints that proxy a wrapper for the WooCommerce REST API.
 * Version: 1.0
 */

if ( ! defined( 'ABSPATH' ) ) exit;

require __DIR__ . '/vendor/autoload.php';

// Vendor
use Dotenv\Dotenv;
use Enfrte\WooApiProxy\Enqueued\AlpineJS;
// Local
use Enfrte\WooApiProxy\Enqueued\Bootstrap5;
use Enfrte\WooApiProxy\RegisterRestRoutes;

$dotenv = Dotenv::createImmutable(__DIR__);
$dotenv->safeLoad();

new Bootstrap5();
new AlpineJS();
new RegisterRestRoutes();
