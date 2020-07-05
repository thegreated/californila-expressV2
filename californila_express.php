<?php
/**
 * @package  californila-express
 */
/*
Plugin Name: Californila Express
Plugin URI: http://californila.com
Description: A plugin for californila express.
Version: 1.0.0
Author: Californila Web dev - Edward Arilla
Author URI: http://californil.com
License: GPLv2 or later
Text Domain: alecaddd-plugin
*/

/*
This program is free software; you can redistribute it and/or
modify it under the terms of the GNU General Public License
as published by the Free Software Foundation; either version 2
of the License, or (at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.

Copyright 2005-2015 Automattic, Inc.
*/

// If this file is called firectly, abort!!!
defined( 'ABSPATH' ) or die( 'Hey, what are you doing here? You silly human!' );

//add_action( 'init' , function(){
//	//packages
//	include dirname(__FILE__) .'/inc/Classes/packages/package_admin_menu.php';
//	include dirname(__FILE__) .'/inc/Classes/packages/class-Package-list-table.php';
//	include dirname(__FILE__) .'/inc/Classes/packages/class-form-handler.php';
//	include dirname(__FILE__) .'/inc/Classes/packages/Package-functions.php';
//	new package_admin_menu();
//});
//
add_action( 'init' , function(){
	include dirname(__FILE__) .'/inc/Classes/shipment/shipment_admin_menu.php';
	include dirname(__FILE__) .'/inc/Classes/shipment/class-schedule-list-table.php';
	include dirname(__FILE__) .'/inc/Classes/shipment/schedule-functions.php';
   
	new shipment_admin_menu();
});
// Require once the Composer Autoload
if ( file_exists( dirname( __FILE__ ) . '/vendor/autoload.php' ) ) {
	require_once dirname( __FILE__ ) . '/vendor/autoload.php';
}

/**
 * The code that runs during plugin activation
 */
function activate_alecaddd_plugin() {
	Inc\Base\Activate::activate();
}
register_activation_hook( __FILE__, 'activate_alecaddd_plugin' );

/**
 * The code that runs during plugin deactivation
 */
function deactivate_alecaddd_plugin() {
	Inc\Base\Deactivate::deactivate();
}
register_deactivation_hook( __FILE__, 'deactivate_alecaddd_plugin' );

/**
 * Initialize all the core classes of the plugin
 */
if ( class_exists( 'Inc\\Init' ) ) {
	Inc\Init::register_services();
}

//------- ADDING COLUMN ON USER ADMIN DASHBOAD

function new_contact_methods( $contactmethods ) {
    $contactmethods['package'] = 'package';

    return $contactmethods;
}
add_filter( 'user_contactmethods', 'new_contact_methods', 10, 1 );


function new_modify_user_table( $column ) {
    $column['package'] = 'Package';

    return $column;
}
add_filter( 'manage_users_columns', 'new_modify_user_table' );

function new_modify_user_table_row( $val, $column_name, $user_id ) {
    return '<a style="background-color:skyblue;padding:5px;" href="post-new.php?post_type=package&user='.$user_id.'"> Add Package </a>';
}
add_filter( 'manage_users_custom_column', 'new_modify_user_table_row', 10, 3 );


//--custom column package ON  ADMIN DASHBOARD

add_filter( 'manage_package_posts_columns', 'set_custom_edit_book_columns' );
add_action( 'manage_package_posts_custom_column' , 'custom_book_column', 10, 2 );

function set_custom_edit_book_columns($columns) {
    unset( $columns['author'] );
    unset( $columns['date'] );
    $columns['image'] = __( 'Image', 'packagelist' );
    $columns['qty'] = __( 'Quntity', 'packagelist' );
    $columns['name'] = __( 'Name of Customer', 'packagelist' );

    return $columns;
}

function custom_book_column( $column, $post_id ) {
    switch ( $column ) {

        case 'image' :
            $files = get_post_meta( $post_id, 'package_list_images', 1 );
                $body = wp_get_attachment_image(  key($files), "medium" );
            echo $body;
            break;
        case 'qty' :
            $qty = get_post_meta($post_id,'package_list_quantity',true);
            echo $qty;
            break;

        case 'name' :
            $user = get_post_meta($post_id,'package_list_user_id',true);
            $userData = get_user_by('id',$user);
            echo '<a href="users.php?s='.$user.'&role=customer&action=-1&new_role&ure_add_role&ure_revoke_role&primary_role=warehouse_manager&paged=1&action2=-1&new_role2&ure_add_role_2&ure_revoke_role_2">'.$userData->first_name .' '.$userData->last_name.'</a>';
            break;

    }
}
add_filter( 'posts_join', 'segnalazioni_search_join' );
function segnalazioni_search_join ( $join ) {
    global $pagenow, $wpdb;

    // I want the filter only when performing a search on edit page of Custom Post Type named "segnalazioni".
    if ( is_admin() && 'edit.php' === $pagenow && 'package' === $_GET['post_type'] && ! empty( $_GET['s'] ) ) {
        $join .= 'LEFT JOIN ' . $wpdb->postmeta . ' ON ' . $wpdb->posts . '.ID = ' . $wpdb->postmeta . '.post_id ';
    }
    return $join;
}

add_filter( 'posts_where', 'segnalazioni_search_where' );
function segnalazioni_search_where( $where ) {
    global $pagenow, $wpdb;

    // I want the filter only when performing a search on edit page of Custom Post Type named "segnalazioni".
    if ( is_admin() && 'edit.php' === $pagenow && 'package' === $_GET['post_type'] && ! empty( $_GET['s'] ) ) {
        $where = preg_replace(
            "/\(\s*" . $wpdb->posts . ".post_title\s+LIKE\s*(\'[^\']+\')\s*\)/",
            "(" . $wpdb->posts . ".post_title LIKE $1) OR (" . $wpdb->postmeta . ".meta_value LIKE $1)", $where );
    }
    return $where;
}
//--custom fees woocomerce
add_action('woocommerce_cart_calculate_fees' , 'add_custom_fees');
function add_custom_fees( WC_Cart $cart ){

    $fees = do_shortcode('[get_total_data_warehouse_chargs]');
    $cart->add_fee( 'Warehouse Charges', $fees);
    $cart->add_fee( 'Other Charges', 0);

}
//---- SENDING EMAIL TO USER AFTER MAKING AN ORDER

add_action('woocommerce_checkout_order_processed', 'send_order_fax');

function send_order_fax($order_id) {
    global $wpdb;
    $id = get_current_user_id();
    $schedule_id =  get_user_meta($id,'shipment_schedule_id', true );
    $date = date('Y-m-d',strtotime(date('m/d/yy')));
    $tracking_code = getTrackingCode(12);
    $wpdb->update( 'wp_3_shipment_schedule', array( 'order_id' => $order_id ,'date_updated' => $date ,'tracking_code' => $tracking_code ),array( 'id' => $schedule_id ) );
    //
    $wpdb->insert( $wpdb->prefix . "order_tracking_details", array( 'tracking_code' => $tracking_code ,'location' => 'California - US' ,'tracking_code' => $tracking_code ));
    //
    delete_user_meta($id,'shipment_schedule_id');
    $packages = $wpdb->get_results('SELECT post_id FROM '.$wpdb->prefix.'postmeta WHERE meta_key="package_list_user_id" AND meta_value="'.$id.'"  ORDER BY  meta_id DESC' );
    foreach ($packages as $package) {
        $status = get_post_meta($package->post_id,'package_list_status',true);
        if($status == "Schedule To Ship") {
            update_post_meta($package->post_id, 'package_list_status', 'On Box');
        }

    }


}

function getTrackingCode($n) {
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $randomString = '';

    for ($i = 0; $i < $n; $i++) {
        $index = rand(0, strlen($characters) - 1);
        $randomString .= $characters[$index];
    }

    return $randomString;
}

///----- ADDDING CUSTOM ORDER STATUS
add_filter( 'wc_order_statuses', 'rename_order_statuses', 20, 1 );
function rename_order_statuses( $order_statuses ) {
    //   $order_statuses['wc-completed']  = _x( 'Order Received', 'Order status', 'woocommerce' );
    $order_statuses['wc-transit'] = _x( 'Transit', 'Order status', 'woocommerce' );
    $order_statuses['wc-received-manila'] = _x( 'Received Manila', 'Order status', 'woocommerce' );
    $order_statuses['wc-delivery'] = _x( 'Out for delivery', 'Order status', 'woocommerce' );
    $order_statuses['wc-processing'] = _x( 'Ship', 'Order status', 'woocommerce' );
    $order_statuses['wc-on-hold']    = _x( 'Processing', 'Order status', 'woocommerce' );
    $order_statuses['wc-completed']   =_x( 'Delivered', 'Order status', 'woocommerce' );
    return $order_statuses;
}
// add_filter( 'bulk_actions-edit-shop_order', 'custom_dropdown_bulk_actions_shop_order', 20, 1 );
// function custom_dropdown_bulk_actions_shop_order( $actions ) {
//     $actions['mark_processing'] = __( 'Mark paid', 'woocommerce' );
//     $actions['mark_on-hold']    = __( 'Mark pending', 'woocommerce' );
//     //   $actions['mark_completed']  = __( 'Mark order received', 'woocommerce' );

//     return $actions;
// }

function register_shipment_arrival_order_status() {
    register_post_status( 'wc-received-manila', array(
        'label'                     => 'Received Manila',
        'public'                    => true,
        'show_in_admin_status_list' => true,
        'show_in_admin_all_list'    => true,
        'exclude_from_search'       => false,
        'label_count'               => _n_noop( 'Receive manila <span class="count">(%s)</span>', 'Receive manila <span class="count">(%s)</span>' )
    ) );
}
add_action( 'init', 'register_shipment_arrival_order_status' );

function register_shipment_arrival_order_status2() {
    register_post_status( 'wc-delivery', array(
        'label'                     => 'Out for delivery',
        'public'                    => true,
        'show_in_admin_status_list' => true,
        'show_in_admin_all_list'    => true,
        'exclude_from_search'       => false,
        'label_count'               => _n_noop( 'Out for Delivery <span class="count">(%s)</span>', 'Out for Delivery <span class="count">(%s)</span>' )
    ) );
}
add_action( 'init', 'register_shipment_arrival_order_status2' );


function register_shipment_arrival_order_status3() {
    register_post_status( 'wc-transit', array(
        'label'                     => 'Transit',
        'public'                    => true,
        'show_in_admin_status_list' => true,
        'show_in_admin_all_list'    => true,
        'exclude_from_search'       => false,
        'label_count'               => _n_noop( 'Transit <span class="count">(%s)</span>', 'Transit <span class="count">(%s)</span>' )
    ) );
}
add_action( 'init', 'register_shipment_arrival_order_status3' );


function add_awaiting_shipment_to_order_statuses( $order_statuses ) {

    $new_order_statuses = array();

    foreach ( $order_statuses as $key => $status ) {

        $new_order_statuses[ $key ] = $status;

        if ( 'wc-processing' === $key ) {
            // $new_order_statuses['wc-received-manila'] = 'Received Manila';
            // $new_order_statuses['wc-delivery'] = 'Out for deliver';
            // $new_order_statuses['wc-transit'] = 'Transit';
            $new_order_statuses['wc-processing'] = 'Ship';

        }
    }

    return $new_order_statuses;
}
add_filter( 'wc_order_statuses', 'add_awaiting_shipment_to_order_statuses' );




//edit wp-admin/orders

// foreach( array( 'post', 'shop_order' ) as $hook )
//     add_filter( "views_edit-$hook", 'shop_order_modified_views1928' );

// function shop_order_modified_views1928( $views ){
    
//     if( isset( $views['wc-transit'] ) )
//          $views['wc-transit'] = str_replace( 'Transit', __( 'Order Received', 'woocommerce'), $views['wc-transit'] );

//     if( isset( $views['wc-delivery'] ) )
//          $views['wc-delivery'] = str_replace( 'Delivery', __( 'Order Received', 'woocommerce'), $views['wc-delivery'] );

//      if( isset( $views['wc-completed'] ) )
//          $views['wc-completed'] = str_replace( 'Delivered', __( 'Order Received', 'woocommerce'), $views['wc-completed'] );

//     if( isset( $views['wc-processing'] ) )
//         $views['wc-processing'] = str_replace( 'Ship', __( 'Order Received', 'woocommerce'), $views['wc-processing'] );

//     if( isset( $views['wc-on-hold'] ) )
//         $views['wc-on-hold'] = str_replace( 'Processing', __( 'Processing', 'woocommerce'), $views['wc-on-hold'] );



//     return $views;
// }

//adding pagination
function modify_product_cat_query( $query ) {
   // if (!is_admin() && $query->is_tax("product_cat")){
         $query->set('posts_per_page', 10);
  //  }
  }
  add_action( 'pre_get_posts', 'modify_product_cat_query' );


  //
// ADDING 1 NEW COLUMNS WITH THEIR TITLES
add_filter( 'manage_edit-shop_order_columns', 'custom_shop_order_column',11);
function custom_shop_order_column($columns)
{
    $reordered_columns = array();

    foreach( $columns as $key => $column){
        $reordered_columns[$key] = $column;
        if( $key ==  'order_total' ){
            $reordered_columns['my-column1'] = __( 'Title1','theme_slug');
        }
    }
    return $reordered_columns;
}

// Adding the data for the additional column (example)
add_action( 'manage_shop_order_posts_custom_column' , 'custom_orders_list_column_content', 10, 2 );
function custom_orders_list_column_content( $column, $post_id )
{
    if( 'my-column1' == $column )
    {
        // Get custom post meta data 1
        $my_var_one = get_post_meta( $post_id, '_the_meta_key1', true );
        if(!empty($my_var_one))
            echo $my_var_one;

        // Get custom post meta data 2
        $my_var_two = get_post_meta( $post_id, '_the_meta_key2', true );
        if(!empty($my_var_two))
            echo $my_var_two;

        // Testing (to be removed) - Empty value case
        if( empty($my_var_one) && empty($my_var_two) )
            echo '<small>(<em>no value</em>)</small>';
    }
}

add_action("woocommerce_order_status_changed", "my_awesome_publication_notification");

function my_awesome_publication_notification($order_id, $checkout=null) {
    date_default_timezone_set('America/Los_Angeles');
    $date =  date('Y-m-d');

   global $woocommerce;
   global $wpdb;
   $order = new WC_Order( $order_id );
    $status = get_status_data($order->status);
    $old_date = date('l, F d y'); 
    $time = date('h:i');

    $wpdb->insert($wpdb->prefix.'order_tracking_details', array('tracking_code' => '', 'location' => $date, 'date_created' => $today,'type' => $type));

     $header = "Your order is now change to  ".$status."";

  
      // Create a mailer
      $mailer = $woocommerce->mailer();
     
      $message_body = __( 'Hello world!!! '.$date.' '.$time  );

      $message = $mailer->wrap_message(
        // Message head and message body.
      sprintf( __( 'Order %s received' ), $order->get_order_number() ), $message_body );

      // Cliente email, email subject and message.
      $mailer->send( $order->billing_email,  $header , $message );

     

   }

   function  get_status_data($status){
       
    switch ($status){
        case 'on-hold' :
            return "Processing";
        case 'processing':
            return "Ship";
        case 'received-manila';
            return "Received Manila";
        case 'delivery':
            return "Out for deliver";
        case 'completed':
            return "Delivered";
        case 'transit':
            return "Transit";

            break;
    }
}


add_action( 'woocommerce_admin_order_data_after_order_details', 'misha_editable_order_meta_general' );
 
function misha_editable_order_meta_general( $order ){  ?>
        <style>
        .table {
            width: 100%;
            max-width: 100%;
            margin-bottom: 1rem;
            background-color: transparent;
        }
        .table td, .table th {
            padding: .75rem;
            vertical-align: top;
        }
        .table-bordered td, .table-bordered th {
            border: 1px solid #dee2e6;
        }
        </style>
		<br class="clear" />
		<h4>Tracking Details</h4>
        <?php 
             global $wpdb;
             $packages = $wpdb->get_results('SELECT * FROM '.$wpdb->prefix.'shipment_schedule AS s INNER JOIN '.$wpdb->prefix.'order_tracking_details as o ON s.tracking_code = o.tracking_code WHERE s.order_id = "'. $order->id.'"' );
             
		?>
		<div class="address">
            <table class="table table-bordered">
                <thead>
                    <tr>
                    <th scope="col">Step</th>
                    <th scope="col">Date</th>
                    <th scope="col">Location</th>
                    <th scope="col">Time</th>
                    <th scope="col">Piece</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                    <th scope="row">1</th>
                    <td>Mark</td>
                    <td>Otto</td>
                    <td>@mdo</td>
                    <td>@mdo</td>
                    </tr>
                 </tbody>
            </table>
		</div>
	
 
 
<?php }
 