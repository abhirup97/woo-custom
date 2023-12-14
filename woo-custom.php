<?php
/**
 * Plugin Name: Woocommerce Custom
 * Plugin URI: https://github.com/abhirup97
 * Description: A toolkit for Woocommerce custom work.
 * Version: 1.0.0
 * Author: Abhirup Goswami
 * Author URI: https://github.com/abhirup97
 * Text Domain: woo-custom
 * Domain Path: /languages/
 * Requires at least: 6.3
 * Requires PHP: 7.4
 *
 */

require_once 'includes/woo-custom-core-functions.php';
require_once 'config.php';
// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/* Plugin activation hook */
register_activation_hook( __FILE__, [ 'WooCustom', 'activate_woo_custom_plugin' ] );
/* Plugin deactivation hook */
register_deactivation_hook( __FILE__, [ 'WooCustom', 'deactivate_woo_custom_plugin' ] );

if ( ! class_exists( 'WooCustom' ) ) {
    global $WooCustom;
    require_once( 'classes/class-woo-custom.php' );

    /* Initiate plugin main class */
    $WooCustom = new WooCustom(__FILE__);
    $GLOBALS['WooCustom'] = $WooCustom;
}