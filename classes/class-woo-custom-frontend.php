<?php

if (!defined('ABSPATH')) {
    exit;
}

/**
 * @class       WooCustom Frontend Class
 *
 * @author 		abhirup
 * @package 	woo-custom/classes
 * @version 	1.0.0
 */

class WooCustom_Frontend {
	function __construct(){
		// Show custom input field above Add to Cart
		add_action( 'woocommerce_before_add_to_cart_button', [ $this, 'woo_custom_product_add_on' ], 9 );
		// Throw error if custom input field empty
		add_filter( 'woocommerce_add_to_cart_validation', [ $this, 'woo_custom_product_add_on_validation' ], 10, 3 );

		// Save custom input field value into cart item data
		add_filter( 'woocommerce_add_cart_item_data', [ $this, 'woo_custom_product_add_on_cart_item_data' ], 10, 2 );

		// Display custom input field value @ Cart
		add_filter( 'woocommerce_get_item_data', [ $this, 'woo_custom_product_add_on_display_cart' ], 10, 2 );

		// Save custom input field value into order item meta
		add_action( 'woocommerce_add_order_item_meta', [ $this, 'woo_custom_product_add_on_order_item_meta' ], 10, 2 );

	}
 
	function woo_custom_product_add_on() {
		$value = isset( $_POST['custom_text_add_on'] ) ? sanitize_text_field( $_POST['custom_text_add_on'] ) : '';
		echo '<div><label> __( 'Custom Text Add-On', 'woo-custom' ) <abbr class="required" title="required">*</abbr></label><p><input name="custom_text_add_on" value="' . $value . '"></p></div>';
	}

	function woo_custom_product_add_on_validation( $passed, $product_id, $qty ){
		if( isset( $_POST['custom_text_add_on'] ) && sanitize_text_field( $_POST['custom_text_add_on'] ) == '' ) {
			wc_add_notice( __( 'Custom Text Add-On is a required field', 'woo-custom' ), 'error' );
			$passed = false;
		}
		return $passed;
	}
 
	function woo_custom_product_add_on_cart_item_data( $cart_item, $product_id ){
		if( isset( $_POST['custom_text_add_on'] ) ) {
			$cart_item['custom_text_add_on'] = sanitize_text_field( $_POST['custom_text_add_on'] );
		}
		return $cart_item;
	}

	function woo_custom_product_add_on_display_cart( $data, $cart_item ) {
		if ( isset( $cart_item['custom_text_add_on'] ) ){
			$data[] = array(
				'name' => 'Custom Text Add-On',
				'value' => sanitize_text_field( $cart_item['custom_text_add_on'] )
			);
		}
		return $data;
	}
 
	function woo_custom_product_add_on_order_item_meta( $item_id, $values ) {
		if ( ! empty( $values['custom_text_add_on'] ) ) {
			wc_add_order_item_meta( $item_id, 'Custom Text Add-On', $values['custom_text_add_on'], true );
		}
	}
}
