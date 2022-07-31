<?php

/**
 * Add a custom text input field to the product page
 */
function plugin_republic_add_text_field() { 
     if ( is_product() && has_term( array( 'custom'), 'product_cat' ) ) {
        echo "<input type='hidden' name='picked_cookies' id='picked_cookies' value=''>";
     }
}
add_action( 'woocommerce_before_add_to_cart_button', 'plugin_republic_add_text_field' );



// Add "picked_cookies" as custom cart item data
add_filter( 'woocommerce_add_cart_item_data', 'add_cart_item_custom_data', 20, 2 );
function add_cart_item_custom_data( $cart_item_data, $product_id ) {
    if( isset($_POST['picked_cookies']) && ! empty($_POST['picked_cookies']) ){
        $cart_item_data['custom_picked_cookies'] = sanitize_text_field( $_POST['picked_cookies'] );
    }
    return $cart_item_data;
}

// Display "picked_cookies" in cart and checkout
add_filter( 'woocommerce_get_item_data', 'display_picked_cookies_in_cart_checkout', 20, 2 );
function display_picked_cookies_in_cart_checkout( $cart_item_data, $cart_item ) {
    if( isset($cart_item['custom_picked_cookies']) ){
        $cart_item_data[] = array(
            'name' => __('Picked Cookies'),
            'value' => $cart_item['custom_picked_cookies'],
        );
    }
    return $cart_item_data;
}

// Save "picked_cookies" as custom order item meta data
add_action( 'woocommerce_checkout_create_order_line_item', 'update_order_item_meta', 20, 4 );
function update_order_item_meta( $item, $cart_item_key, $values, $order ) {
    if( isset($values['custom_picked_cookies']) )
        $item->update_meta_data( 'picked_cookies', $values['custom_picked_cookies'] );
}