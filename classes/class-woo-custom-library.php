<?php

if (!defined('ABSPATH')) {
    exit;
}

/**
 * @class       WooCustom Library Class
 *
 * @author      abhirup
 * @package     woo-custom/classes
 * @version     1.0.0
 */

class WooCustom_Library {
		public $lib_path;
    	public $lib_url;
    	public $bootstrap_lib_url;

	function __construct() {
        global $WooCustom;
		$this->lib_path = $WooCustom->plugin_path . 'lib/';
        $this->lib_url = $WooCustom->plugin_url . 'lib/';
		$this->bootstrap_lib_url = $this->lib_url . 'bootstrap/';
	}
    //bootstrap style
	public function load_bootstrap_style_lib() {
        wp_register_style( 'woo-custom-bootstrap-style', $this->bootstrap_lib_url . 'css/bootstrap.min.css', array(), '4.6.0' );
        wp_enqueue_style( 'woo-custom-bootstrap-style' );
    }
    //bootstrap script
    public function load_bootstrap_script_lib() {
        wp_register_script( 'woo-custom-bootstrap-script', $this->bootstrap_lib_url . 'js/bootstrap.bundle.min.js', array( 'jquery' ), '4.6.0' );
        wp_enqueue_script( 'woo-custom-bootstrap-script' ); 
    }
}