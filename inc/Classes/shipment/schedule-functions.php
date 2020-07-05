<?php

/**
 * Get all schedule
 *
 * @param $args array
 *
 * @return array
 */
function wd_get_all_schedule( $args = array() ) {
    global $wpdb;

    $defaults = array(
        'number'     => 20,
        'offset'     => 0,
        'orderby'    => 'id',
        'order'      => 'DESC',
    );

    $args      = wp_parse_args( $args, $defaults );
    $cache_key = 'schedule-all';
    $items     = wp_cache_get( $cache_key, ' webdevs' );
    //var_dump($items);
    if ( false === $items ) {
        $items = $wpdb->get_results( 'SELECT * FROM ' . $wpdb->prefix . 'shipment_schedule WHERE product_suggestion IS NULL  ORDER BY ' . $args['orderby'] .' ' . $args['order'] .' LIMIT ' . $args['offset'] . ', ' . $args['number'] );

        wp_cache_set( $cache_key, $items, ' webdevs' );
    }

    return $items;
}

function wd_get_all_schedule_boxes( $args = array() ) {


    global $wpdb;

    $defaults = array(
        'number'     => 20,
        'offset'     => 0,
        'orderby'    => 'id',
        'order'      => 'DESC',
    );

    $args      = wp_parse_args( $args, $defaults );
    $cache_key = 'schedule-all2';
    $items     = wp_cache_get( $cache_key, ' webdevs' );
    if ( false === $items ) {

        $items = $wpdb->get_results( 'SELECT * FROM ' . $wpdb->prefix . 'shipment_schedule WHERE tracking_code IS NULL AND product_suggestion IS NOT NULL  ORDER BY ' . $args['orderby'] .' ' . $args['order'] .' LIMIT ' . $args['offset'] . ', ' . $args['number'] );

        wp_cache_set( $cache_key, $items, ' webdevs' );
    }

    return $items;
}

/**
 * Fetch all schedule from database
 *
 * @return array
 */
function wd_get_schedule_count() {
    global $wpdb;

    return (int) $wpdb->get_var( 'SELECT COUNT(*) FROM ' . $wpdb->prefix . 'shipment_schedule  WHERE product_suggestion IS NULL' );
}

function wd_get_schedule_count_boxes() {
    global $wpdb;

    return (int) $wpdb->get_var( 'SELECT COUNT(*) FROM ' . $wpdb->prefix . 'shipment_schedule  WHERE tracking_code IS NULL AND product_suggestion IS NOT NULL' );
}


/**
 * Fetch a single schedule from database
 *
 * @param int   $id
 *
 * @return array
 */
function wd_get_schedule( $id = 0 ) {
    global $wpdb;

    return $wpdb->get_row( $wpdb->prepare( 'SELECT * FROM ' . $wpdb->prefix . 'shipment_schedule WHERE id = %d', $id ) );
}