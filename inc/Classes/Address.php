<?php
/**
 * @package  AlecadddPlugin
 */
namespace Inc\Classes;

use Inc\Base\BaseController;


class Address extends BaseController
{



    public function register()
    {
        add_action('init', array($this,'dashboard_add_address'));
    }


    function dashboard_add_address(){
        global $wpdb;
        global $errors;
        global $success;
        if (isset( $_POST['action']) && 'POST' == $_SERVER['REQUEST_METHOD'] && !empty( $_POST['action'] ) && $_POST['action'] == 'add-address' ) {
            $new_address_fname = isset( $_POST['new_address_fname'] ) ? sanitize_text_field( $_POST['new_address_fname'] ) : '';
            $new_address_lname = isset( $_POST['new_address_lname'] ) ? sanitize_text_field( $_POST['new_address_lname'] ) : '';
            $new_address = isset( $_POST['new_address'] ) ? sanitize_text_field( $_POST['new_address'] ) : '';
            $new_company = isset( $_POST['new_company'] ) ? sanitize_text_field( $_POST['new_company'] ) : '';
            $new_deliver_address = isset( $_POST['new_deliver_address'] ) ? sanitize_text_field( $_POST['new_deliver_address'] ) : '';
            $delivery_address_two   = isset( $_POST['delivery_address_two'] ) ? sanitize_text_field( $_POST['delivery_address_two'] ) : '';
            $states = isset( $_POST['new_state'] ) ? sanitize_text_field( $_POST['new_state'] ) : '';
            $city = isset( $_POST['new_city'] ) ? sanitize_text_field( $_POST['new_city'] ) : '';
            $new_zipcode = isset( $_POST['new_zipcode'] ) ? sanitize_text_field( $_POST['new_zipcode'] ) : '';
            $new_primary_code = isset( $_POST['new_primary_code'] ) ? sanitize_text_field( $_POST['new_primary_code'] ) : '';
            $new_primary_phone = isset( $_POST['new_primary_phone'] ) ? sanitize_text_field( $_POST['new_primary_phone'] ) : '';
            $new_secodary_code = isset( $_POST['new_secodary_code'] ) ? sanitize_text_field( $_POST['new_secodary_code'] ) : '';
            $new_secodary_phone = isset( $_POST['new_secodary_phone'] ) ? sanitize_text_field( $_POST['new_secodary_phone'] ) : '';
            $type = isset( $_POST['new_type'] ) ? sanitize_text_field( $_POST['new_type'] ) : '';
        //    country_id = isset( $_POST['country_id'] ) ? sanitize_text_field( $_POST['country_id'] ) : '';


            // some basic validation
            if (! $new_address_fname)  {
                $errors[] = __( 'Error: First name is required', 'webdevs' );
            }
            if ( ! $new_address_lname ) {
                $errors[] = __( 'Error: Last Name is required', 'webdevs' );
            }
            if ( ! $new_address ) {
                $errors[] = __( 'Error: Address is required', 'webdevs' );
            }

            if ( ! $states ) {
                $errors[] = __( 'Error: states is required', 'webdevs' );
            }
            if ( ! $city ) {
                $errors[] = __( 'Error: city is required', 'webdevs' );
            }
            if ( ! $new_zipcode ) {
                $errors[] = __( 'Error: zipcode is required', 'webdevs' );
            }
            if ( ! $new_primary_phone ) {
                $errors[] = __( 'Error: primary phone is required', 'webdevs' );
            }
            if (!empty($errors )) {

              //  $first_error = reset( $errors );
              //  $page_url = home_url('add-delivery-address');
              //  $redirect_to = add_query_arg( array( 'error' => $first_error ), $page_url );

             //  wp_safe_redirect( $redirect_to );
            //    exit;
            }if(empty($errors)) {

                $table_name = $wpdb->prefix . "user_address";
                $wpdb->insert($table_name, array(
                    'first_name' => $new_address_fname,
                    'last_name' => $new_address_lname,
                    'address_name' => $new_address,
                    'company' => $new_company,
                    'delivery_address' => $new_deliver_address,
                    'delivery_address_two' => $delivery_address_two,
                    'states' => $states,
                    'city' => $city,
                    'zipcode' => $new_zipcode,
                    'primary_phone_id' => (int)$new_primary_code,
                    'primary_phone' => (int)$new_primary_phone,
                    'second_phone_id' => (int)$new_secodary_code,
                    'second_phone' => (int)$new_secodary_phone,
                    'type' => $type,
                    'country_id' => 3,   //static
                    'user_id' => get_current_user_id(),
                    'date' => date("F j, Y, g:i a"),
                    'default_address' => 0
                ));
                $success =  "You successfully add the new address";
                $page = get_page_by_title('dashboard');
                wp_redirect(get_permalink($page->ID).'?success=address');

            }



        }
    }




}