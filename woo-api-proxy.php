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

// Local
use Enfrte\WooApiProxy\Bootstrap5Support;
use Enfrte\WooApiProxy\RegisterRestRoutes;

$dotenv = Dotenv::createImmutable(__DIR__);
$dotenv->safeLoad();

new Bootstrap5Support();
new RegisterRestRoutes();
