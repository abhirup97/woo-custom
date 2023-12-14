<?php

if (!defined('ABSPATH')) {
    exit;
}

/**
 * @class       WooCustom Admin Class
 *
 * @author 		abhirup
 * @package 	woo-custom/classes
 * @version 	1.0.0
 */

class WooCustom_Admin {
	public function __construct() {
		add_filter( 'woocommerce_general_settings' , [ $this, 'general_settings_fee' ] );
		// Display custom input field value into order table
		add_filter( 'woocommerce_order_item_product', [ $this, 'woo_custom_product_add_on_display_order' ], 10, 2 );
	}
	
	public function general_settings_fee($settings) {
		$key = 0;

		foreach( $settings as $values ){
			$new_settings[$key] = $values;
			$key++;

			// Inserting array just after the post code in "Store Address" section
			if($values['id'] == 'woocommerce_price_num_decimals'){
				$new_settings[$key] = array(
					'title'    => __('Custom fee per order', 'woo-custom' ),
					'desc'     => __('Fee to add every order', 'woo-custom' ),
					'id'       => 'woocommerce_custom_fee', // <= The field ID (important)
					'default'  => '',
					'type'     => 'number',
					'desc_tip' => true, // or false
				);
				$key++;
			}
		}
		return $new_settings;
	}
	
	function woo_custom_product_add_on_display_order( $cart_item, $order_item ){
		if( isset( $order_item['custom_text_add_on'] ) ){
			$cart_item['custom_text_add_on'] = $order_item['custom_text_add_on'];
		}
		return $cart_item;
	}

}
