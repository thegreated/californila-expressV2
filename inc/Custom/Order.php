<?php
/**
 *
 *
 *  @package  AlecadddPlugin
 */

namespace Inc\Custom;

use Inc\CMB2\init;


class Order
{

    public function register()
    {
        add_action( 'cmb2_admin_init', array($this,'package_list'));
    }




    public	function package_list() {

        $cmb = new_cmb2_box( array(
            'id'            => 'order_tracking_info',
            'title'         => 'Tracking Details',
            'object_types'  => array( 'shop_order', ), // Post type
            'context'       => 'side',
            'priority'      => 'high',
            'show_names'    => true, // Show field names on the left
        ) );
        $cmb->add_field( array(
            'name'  => 'Location (City-Country)',
            'id'    => 'tracking_location',
            'default' => 'Manila - PH',
            'type'  => 'text',
        ) );
        $cmb->add_field( array(
            'name'  => 'Piece',
            'id'    => 'tracking_piece',
            //'default' => '',
            'type' => 'text',
            'attributes' => array(
                'type' => 'number',
            ),
        ) );


    }

    function package_limit_rest_view_to_logged_in_users( $is_allowed, $cmb_controller ) {
       return true;
    }
}
