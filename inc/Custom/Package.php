<?php
/**
 *
 *
 *  @package  AlecadddPlugin
 */

namespace Inc\Custom;

use Inc\CMB2\init;


class Package
{


    public function register()
    {

        add_action( 'cmb2_init', array($this,'package_list'));

    }




    public	function package_list() {


        $prefix = 'package_list_';

        $cmb_rest = new_cmb2_box( array(
            'id'            => $prefix . 'metabox',
            'title'         => esc_html__( 'Package Details', 'package_list'),
            'object_types'  => array( 'package' ), // Post type
            'get_box_permissions_check_cb' => 'package_limit_rest_view_to_logged_in_users',
        ) );
        if(isset($_GET['user'])) {  $user = $_GET['user']; }

        $cmb_rest->add_field(array(
            'name' => esc_html__('Package For User:', 'package_list'),
            'desc' => esc_html__('Package for  user', 'package_list'),
            'id' => $prefix . 'user_id',
            'type' => 'text_medium',
            'default' => (isset($user))? $user : '',
            'attributes' => array(

                'readonly' => 'readonly',
            ),
            // 'date_format' => 'Y-m-d',
        ));


        $cmb_rest->add_field( array(
            'name' => esc_html__( 'Classification', 'package_list' ),
            'desc' => esc_html__( 'Example: Fashion,Gadget (optional)', 'package_list' ),
            'id'   =>  $prefix . 'classification',
            'type' => 'text_medium'
        ) );

        $cmb_rest->add_field( array(
            'name'             => esc_html__( 'Status', 'package_list' ),
            'desc'             => esc_html__( 'Make sure this is accurate and based on flow given', 'package_list' ),
            'id'               => $prefix . 'status',
            'type'             => 'select',
            'show_option_none' => true,
            'options'          => array(
                'Pick up' => esc_html__( 'Pick up', 'package_list   ' ),
                'Ready To Ship'   => esc_html__( 'Ready To Ship', 'package_list' ),
                'Schedule To Ship'     => esc_html__( 'Schedule To Ship', 'package_list' ),
                'On Box'     => esc_html__( 'On Box', 'cmb2' ),
            ),
        ) );
        $cmb_rest->add_field( array(
            'name'             => esc_html__( 'Warehouse', 'package_list' ),
            'desc'             => esc_html__( 'Warehouse it is received', 'package_list' ),
            'id'               => $prefix . 'warehouse',
            'type'             => 'select',
            'show_option_none' => true,
            'options'          => array(
                'California' => esc_html__( 'California', 'package_list' ),
            ),
        ) );
        $cmb_rest->add_field( array(
            'name'             => esc_html__( 'Package Type', 'package_list' ),
            'desc'             => esc_html__( '', 'package_list' ),
            'id'               => $prefix . 'type',
            'type'             => 'select',
            'show_option_none' => true,
            'options'          => array(
                'Consolidator' => esc_html__( 'Consolidator', 'package_list' ),
                'Personal Shopper' => esc_html__( 'Personal Shopper', 'package_list' )
            ),
        ) );

        $cmb_rest->add_field( array(
            'name' => esc_html__( 'Merchant Name', 'package_list' ),
            'desc' => esc_html__( 'Details is listed on the package', 'package_list' ),
            'id'   =>  $prefix . 'merchant_name',
            'type' => 'text_medium'
        ) );
        $cmb_rest->add_field( array(
            'name' => esc_html__( 'Merchant Order id', 'package_list' ),
            'desc' => esc_html__( 'Details is listed on the package', 'package_list' ),
            'id'   => $prefix . 'merchane_order_id',
            'type' => 'text_medium',
        ) );

        $cmb_rest->add_field( array(
            'name'             => esc_html__( 'Reduction', 'package_list' ),
            'desc'             => esc_html__( 'If you reduce some part of packages', 'package_list' ),
            'id'               =>  $prefix . 'reduction',
            'type'             => 'radio_inline',
            'show_option_none' => 'None',
            'options'          => array(
                'Yes' => esc_html__( 'Yes', 'package_list' ),
            ),
        ) );
        $cmb_rest->add_field( array(
            'name'             => esc_html__( 'Enclosure', 'package_list' ),
            'desc'             => esc_html__( 'If the package have enclosure', 'package_list' ),
            'id'               =>  $prefix . 'enclosure',
            'type'             => 'radio_inline',
            'show_option_none' => 'None',
            'options'          => array(
                'Yes' => esc_html__( 'Yes', 'package_list' ),
            ),
        ) );

        $cmb_rest->add_field( array(
            'name' => esc_html__( 'Package height (CM)', 'package_list' ),
            'desc' => esc_html__( '', 'package_list' ),
            'id'   =>  $prefix . 'height',
            'type' => 'text_small',
            'attributes' => array(
                'type' => 'number',
            ),
        ) );
        $cmb_rest->add_field( array(
            'name' => esc_html__( 'Package width (CM)', 'package_list' ),
            'desc' => esc_html__( '', 'package_list' ),
            'id'   =>  $prefix . 'width',
            'type' => 'text_small',
            'attributes' => array(
                'type' => 'number',
            ),
        ) );
        $cmb_rest->add_field( array(
            'name' => esc_html__( 'Package lenght (CM)', 'package_list' ),
            'desc' => esc_html__( '', 'package_list' ),
            'id'   =>  $prefix . 'lenght',
            'type' => 'text_small',
            'attributes' => array(
                'type' => 'number',
            ),
        ) );
        $cmb_rest->add_field( array(
            'name' => esc_html__( 'Package weight (KG)', 'package_list' ),
            'desc' => esc_html__( 'This is important for calculating the weight charges', 'package_list' ),
            'id'   =>  $prefix . 'weight',
            'type' => 'text_small',
            'attributes' => array(
                'type' => 'number',
            ),
        ) );
        $cmb_rest->add_field( array(
            'name' => esc_html__( 'Package Cost', 'package_list' ),
            'desc' => esc_html__( 'Package value on ($) Dollars ', 'package_list' ),
            'id'   =>  $prefix . 'cost',
            'type' => 'text_medium'
        ) );
        $cmb_rest->add_field( array(
            'name' => esc_html__( 'Quantity', 'package_list' ),
            'desc' => esc_html__( ' ', 'package_list' ),
            'id'   =>  $prefix . 'quantity',
            'type' => 'text_small',
            'attributes' => array(
                'type' => 'number',
            ),
        ) );

        $cmb_rest->add_field( array(
            'name' => esc_html__( 'Date and Time Received', 'package_list' ),
            'desc' => esc_html__( '(Format: mm/dd/yyyy) PST date on California', 'package_list' ),
            'id'   =>  $prefix . 'datetime',
            'type' => 'text_datetime_timestamp',
        ) );



        $cmb_rest->add_field( array(
            'name'         => esc_html__( 'Package Image', 'package_list' ),
            'desc'         => esc_html__( 'Upload or add multiple images/attachments.', 'package_list' ),
            'id'           => $prefix . 'images',
            'type'         => 'file_list',
            'preview_size' => array( 100, 100 ), // Default: array( 50, 50 )
        ) );



    }

    function package_limit_rest_view_to_logged_in_users( $is_allowed, $cmb_controller ) {
        if ( ! is_user_logged_in() ) {
            $is_allowed = false;
        }

        return $is_allowed;
    }
}
