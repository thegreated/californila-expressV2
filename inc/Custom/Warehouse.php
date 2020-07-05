<?php
/**
 *
 *
 *  @package  AlecadddPlugin
 */

namespace Inc\Custom;

use Inc\CMB2\init;


class Warehouse
{


    public function register()
    {

        add_action( 'cmb2_init', array($this,'warehouse_list'));

    }




    public	function warehouse_list() {


        $prefix = 'warehouseList_';

        $cmb_rest = new_cmb2_box( array(
            'id'            => $prefix . 'metabox',
            'title'         => esc_html__( 'Warehouse Details', 'warehouseList' ),
            'object_types'  => array( 'warehouse' ), // Post type
            'get_box_permissions_check_cb' => 'warehouse_limit_rest_view_to_logged_in_users',
        ) );

        $cmb_rest->add_field( array(
            'name' => esc_html__( 'Shop Name', 'warehouseList' ),
            'desc' => esc_html__( 'field description (required)', 'warehouseList' ),
            'id'   =>  $prefix . 'warehouseList_name',
            'type' => 'text_medium',
            'default' => 'Californila'
        ) );

        $cmb_rest->add_field( array(
            'name' => esc_html__( 'Shop Address', 'warehouseList' ),
            'desc' => esc_html__( 'field description (required)', 'warehouseList' ),
            'id'   => $prefix . 'warehouseList_address',
            'type' => 'textarea_small',
        ) );

        $cmb_rest->add_field( array(
            'name' => esc_html__( 'Country', 'warehouseList' ),
            'desc' => esc_html__( 'field description (required)', 'warehouseList' ),
            'id'   =>  $prefix . 'warehouseList_country',
            'type' => 'text_medium',
            'default' => 'United States'
        ) );




    }

    function warehouse_limit_rest_view_to_logged_in_users( $is_allowed, $cmb_controller ) {
        if ( ! is_user_logged_in() ) {
            $is_allowed = false;
        }

        return $is_allowed;
    }
}
