<?php
/**
 * Plugin Name: WooCommerce API Proxy
 * Description: Adds custom endpoints that proxy a wrapper for the WooCommerce REST API.
 * Version: 1.0
 */

if ( ! defined( 'ABSPATH' ) ) exit;

require __DIR__ . '/vendor/autoload.php';

use Dotenv\Dotenv;

$dotenv = Dotenv::createImmutable(__DIR__);
$dotenv->safeLoad();

new Bootstrap5Support();
new RegisterRestRoutes();
