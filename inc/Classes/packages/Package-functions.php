<?php

/**
 * Get all Package
 *
 * @param $args array
 *
 * @return array
 */
 
 
function package_get_all_Package( $args = array() ) {
    global $wpdb;

    $defaults = array(
        'number'     => 5,
        'offset'     => 0,
        'orderby'    => 'id',
        'order'      => 'DESC',
		
		
    );

    $args      = wp_parse_args( $args, $defaults );
    $cache_key = 'Package-all';
    $items     = wp_cache_get( $cache_key, 'webdevs' );

	
    if ( false === $items ) {
        $table_name = $wpdb->prefix . "_californila_packages";
		if(!isset( $args['user_id'])){
			
			$items = $wpdb->get_results( 'SELECT * FROM '.$table_name.' ORDER BY ' . $args['orderby'] .' ' . $args['order'] .' LIMIT ' . $args['offset'] . ', ' . $args['number'] );
		}else {
			
			$items = $wpdb->get_results( 'SELECT * FROM '.$table_name.' WHERE user_id = '.$args['user_id'].'   ORDER BY ' . $args['orderby'] .' ' . $args['order'] .' LIMIT ' . $args['offset'] . ', ' . $args['number'] );

		}
        wp_cache_set( $cache_key, $items, 'webdevs' );
    }

    return $items;
}

/**
 * Fetch all Package from database
 *
 * @return array
 */
function package_get_Package_count() {
    global $wpdb;
    $table_name = $wpdb->prefix . "_californila_packages";
    return (int) $wpdb->get_var( 'SELECT COUNT(*) FROM '. $table_name );
}

/**
 * Fetch a single Package from database
 *
 * @param int   $id
 *
 * @return array
 */
function package_get_package( $id = 0 ) {
    global $wpdb;
    $table_name = $wpdb->prefix . "_californila_packages";
    return $wpdb->get_row( $wpdb->prepare( 'SELECT * FROM '. $table_name.' WHERE id = %d', $id ) );
}


function packagelist_insert_package( $args = array() ) {
    
    global $wpdb;

    $defaults = array(
        'id'         => null,
        'warehouse_id' => '',
        'service_type' => '',
        'status' => '',
        'merchant_order' => '',
        'reduction' => '',
        'enclosure' => '',
        'qty' => '',
        'description' => '',
        'classification' => '',
        'unit_cost' => '',
        'date_received' => '',
		'user_id'		=> '',
        'resized_dimention_lenght'		=> '',
        'resized_dimention_height'		=> '',
        'resized_dimention_width'		=> '',
        'resized_dimention_weight'		=> '',
        'image_groups' =>  '',

    );

    $args       = wp_parse_args( $args, $defaults );


    // some basic validation
    if ( empty( $args['warehouse_id'] ) ) {
        return new WP_Error( 'no-warehouse_id', __( 'No Warehouse provided.', 'webdevs' ) );
    }
    if ( empty( $args['service_type'] ) ) {
        return new WP_Error( 'no-service_type', __( 'No Service type provided.', 'webdevs' ) );
    }
    if ( empty( $args['status'] ) ) {
        return new WP_Error( 'no-status', __( 'No Status provided.', 'webdevs' ) );
    }
    if ( empty( $args['merchant_order'] ) ) {
        return new WP_Error( 'no-merchant_order', __( 'No Merchant Order provided.', 'webdevs' ) );
    }
    if ( empty( $args['qty'] ) ) {
        return new WP_Error( 'no-qty', __( 'No Quantity provided.', 'webdevs' ) );
    }
    if ( empty( $args['description'] ) ) {
        return new WP_Error( 'no-description', __( 'No Description provided.', 'webdevs' ) );
    }
	

    // remove row id to determine if new or update
    $row_id = (int) $args['id'];
    unset( $args['id'] );
		
		
    if ( ! $row_id ) {

      

        // insert a new
        $table_name = $wpdb->prefix . "_californila_packages";
        if ( $wpdb->insert( $table_name, $args ) ) {


            return $wpdb->insert_id;
        }

    } else {
       // wp_die( __( var_dump($args), 'webdevs' ) );
        // do update method here
        $table_name = $wpdb->prefix . "_californila_packages";
        if ( $wpdb->update( $table_name,$args, array( 'id' => $row_id ) ) ) {
            return $row_id;
        }
    }

    return false;
}

function my_handle_attachment($file_handler,$post_id,$set_thu=false) {
    // check to make sure its a successful upload
    if ($_FILES[$file_handler]['error'] !== UPLOAD_ERR_OK) __return_false();

    require_once(ABSPATH . "wp-admin" . '/includes/image.php');
    require_once(ABSPATH . "wp-admin" . '/includes/file.php');
    require_once(ABSPATH . "wp-admin" . '/includes/media.php');

    $attach_id = media_handle_upload( $file_handler, $post_id );

    return $attach_id;
}