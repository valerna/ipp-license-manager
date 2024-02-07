<?php
/*
Plugin Name: IPP License Manager
Description: Adds a new endpoint to the WooCommerce user dashboard for displaying licenses.
Plugin URI: https://ippmusic.com/
Version: 1.1
Author: RJ Buchanan
Author URI: https://amalgam.design
 */

add_action( 'init', 'custom_licenses_endpoint' );

function custom_licenses_endpoint() {
    add_rewrite_endpoint( 'licenses', EP_ROOT | EP_PAGES );
}

add_filter( 'query_vars', 'custom_licenses_query_vars', 0 );

function custom_licenses_query_vars( $vars ) {
    $vars[] = 'licenses';
    return $vars;
}

add_action( 'woocommerce_account_licenses_endpoint', 'custom_licenses_content' );

function custom_licenses_content() {
    $current_user = wp_get_current_user();
    $customer_orders = wc_get_orders( array(
        'customer' => $current_user->ID,
    ) );

    echo '<table>';
    echo '<tr><th>Order Number</th><th>Download</th></tr>';

    foreach ( $customer_orders as $customer_order ) {
        $order_id = $customer_order->get_id();
        $order_number = $customer_order->get_order_number();

        for ( $i = 1; $i <= 13; $i++ ) {
            $pteam_field = 'pteam_' . $i;
            $license_field = 'license_' . $i;
            $pteam_value = get_post_meta( $order_id, $pteam_field, true );
            $license_value = get_post_meta( $order_id, $license_field, true );

            if ( ! empty( $pteam_value ) && ! empty( $license_value ) ) {
                echo '<tr>';
                echo '<td>' . $order_number . '</td>';
                echo '<td><a href="' . $license_value . '" style="color: #323b61;">' . $pteam_value . '</a></td>';
                echo '</tr>';
            }
        }
    }

    echo '</table>';
}
