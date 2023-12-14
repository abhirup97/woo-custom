<?php

/**
 * woo-custom Main Class
 *
 * @version		1.0.0
 * @package		woo-custom
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

final class WooCustom {

    private $file;
    public $plugin_url;
    public $plugin_path;
    public $version;
    public $token;
    public $admin;
    public $frontend;
    public $template;
    public $install;
    public $library;
    public $ajax;
    
	/**
     * Class construct
     * @param object $file
     */
    public function __construct( $file ) {
        $this->file = $file;
        $this->plugin_url = trailingslashit( plugins_url( '', $plugin = $file ) );
        $this->plugin_path = trailingslashit( dirname( $file ) );
        $this->token = WOOCUSTOM_PLUGIN_TOKEN;
        $this->version = WOOCUSTOM_PLUGIN_VERSION;

        add_action( 'init', [ $this, 'init' ] );
    }

     /**
     * Initialize plugin on WP init
     */
    public function init() {

        $this->load_class( 'library' );
        $this->library = new WooCustom_Library();

        if ( defined( 'DOING_AJAX' ) ) {
            $this->load_class( 'ajax' );
            $this->ajax = new WooCustom_Ajax();
        }

    	if ( is_admin() ) {
    		$this->load_class( 'admin' );
        	$this->admin = new WooCustom_Admin();
    	}
        if ( ! is_admin() || defined( 'DOING_AJAX' ) ) {
            // Init main frontend action class
            $this->load_class( 'frontend' );
            $this->frontend = new WooCustom_Frontend();
        }
        // Init templates
        $this->load_class( 'template' );
        $this->template = new WooCustom_Template();

        // Add a Fee conditionally in checkout
		add_action( 'woocommerce_cart_calculate_fees', [ $this, 'add_selected_card_type_fee' ] );

    }

	function add_selected_card_type_fee() {	
		$extra_charge = get_option( 'woocommerce_custom_fee' );
		if ( $extra_charge && ! empty( $extra_charge ) ) {
			WC()->cart->add_fee( __('Extra Fee', 'woocommerce'), $extra_charge );
		}
	}

    /**
     * Helper method to load other class
     * @param type $class_name
     * @param type $dir
     */
    public function load_class( $class_name = '', $dir = '' ) {
        if ('' != $class_name && '' != $this->token) {
            if (!$dir)
                require_once ( 'class-' . esc_attr( $this->token ) . '-' . esc_attr( $class_name ) . '.php' );
            else
                require_once ( trailingslashit( $dir ) . 'class-' . esc_attr( $this->token ) . '-' . strtolower( $dir ) . '-' . esc_attr( $class_name ) . '.php' );
        }
    }

    /**
     * On activation install option and load install class that will create a page
     */
    public static function activate_woo_custom_plugin() {
    	global $WooCustom;
        update_option( 'woo_custom_plugin_installed', 1 );
        // Init install
        $WooCustom->load_class( 'install' );
        $WooCustom->install = new WooCustom_Install();
    }

    /**
     * On deactivation delete install option
     */
    public static function deactivate_woo_custom_plugin() {
        delete_option( 'woo_custom_plugin_installed' );
    }

    /**
     * Sets a constant preventing some caching plugins from caching a page. Used on dynamic pages
     *
     * @access public
     * @return void
     */
    public function nocache() {
        if ( ! defined( 'DONOTCACHEPAGE' ) )
            define( "DONOTCACHEPAGE", "true" );
        // WP Super Cache constant
    }
}