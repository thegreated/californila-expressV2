<?php
/**
 * 
 *
 *  @package  AlecadddPlugin
 */


namespace Inc\Custom;

use Inc\CMB2\init;


class Address 
{

	
	public function register() 
	{
	
		add_action( 'cmb2_admin_init', array($this,'taskbook_register_rest_api_box'));
	}




	public	function taskbook_register_rest_api_box() {


		$prefix = 'addressList_';

		$cmb_rest = new_cmb2_box( array(
			'id'            => $prefix . 'metabox',
			'title'         => esc_html__( 'Location Details', 'addressList' ),
			'object_types'  => array( 'address' ), // Post type
			'get_box_permissions_check_cb' => 'taskbook_limit_rest_view_to_logged_in_users',
		) );

		$cmb_rest->add_field( array(
			'name' => esc_html__( 'Test Image', 'addressList' ),
			'desc' => esc_html__( 'Upload an image or enter a URL.', 'addressList' ),
			'id'   =>  $prefix . 'image_flag',
			'type' => 'file',
		) );
		
		$cmb_rest->add_field( array(
			'name' => esc_html__( 'Shop Name', 'addressList' ),
			'desc' => esc_html__( 'field description (required)', 'addressList' ),
			'id'   =>  $prefix . 'shop_name',
			'type' => 'text_medium',
			'default' => 'Californila'
		) );
		
		$cmb_rest->add_field( array(
			'name' => esc_html__( 'Shop Address', 'addressList' ),
			'desc' => esc_html__( 'field description (required)', 'addressList' ),
			'id'   => $prefix . 'shop_address',
			'type' => 'textarea_small',
		) );

		$cmb_rest->add_field( array(
			'name' => esc_html__( 'Country', 'addressList' ),
			'desc' => esc_html__( 'field description (required)', 'addressList' ),
			'id'   =>  $prefix . 'shop_country',
			'type' => 'text_medium',
			'default' => 'United States'
		) );

        $cmb_rest->add_field( array(
            'name' => esc_html__( 'User', 'addressList' ),
            'desc' => esc_html__( 'field description (required)', 'addressList' ),
            'id'   =>  $prefix . 'user',
            'type' => 'text_medium',
            'default' => $_GET['user']
        ) );



	}

	function taskbook_limit_rest_view_to_logged_in_users( $is_allowed, $cmb_controller ) {
        return true;
	}
}
