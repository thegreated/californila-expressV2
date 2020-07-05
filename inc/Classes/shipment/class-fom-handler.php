<?php

/**
 * Handle the form submissions
 *
 * @package Package
 * @subpackage Sub Package
 */
class Form_Handler {

    /**
     * Hook 'em all
     */
    public function __construct() {
        add_action( 'admin_init', array( $this, 'handle_form' ) );
    }

    /**
     * Handle the package new and edit form
     *
     * @return void
     */
    public function handle_form() {
        if ( ! isset( $_POST['submit_package'] ) ) {
            return;
        }

        if ( ! wp_verify_nonce( $_POST['_wpnonce'], 'package-new' ) ) {
            wp_die( __( 'Are you cheating?', 'webdevs' ) );
        }

        if ( ! current_user_can( 'read' ) ) {
            wp_die( __( 'Permission Denied!', 'webdevs' ) );
        }

        $errors   = array();

        $field_id = isset( $_POST['field_id'] ) ? intval( $_POST['field_id'] ) : 0;

        $warehouse_id = isset( $_POST['warehouse_id'] ) ? sanitize_text_field( $_POST['service_type'] ) : '';
        $service_type = isset( $_POST['service_type'] ) ? sanitize_text_field( $_POST['service_type'] ) : '';
        $status = isset( $_POST['status'] ) ? sanitize_text_field( $_POST['status'] ) : '';
        $merchant_order = isset( $_POST['merchant_order'] ) ? sanitize_text_field( $_POST['merchant_order'] ) : '';
        $reduction = isset( $_POST['reduction'] ) ? sanitize_text_field( $_POST['reduction'] ) : '';
        $enclosure = isset( $_POST['enclosure'] ) ? sanitize_text_field( $_POST['enclosure'] ) : '';
        $qty = isset( $_POST['qty'] ) ? intval( $_POST['qty'] ) : 0;
        $description = isset( $_POST['description'] ) ? sanitize_text_field( $_POST['description'] ) : '';
        $classification = isset( $_POST['classification'] ) ? sanitize_text_field( $_POST['classification'] ) : '';
        $unit_cost = isset( $_POST['unit_cost'] ) ? intval( $_POST['unit_cost'] ) : 0;
        $date_received = isset( $_POST['date_received'] ) ? sanitize_text_field( $_POST['date_received'] ) : '';
        $user_id =	isset( $_POST['user_id'] ) ? sanitize_text_field( $_POST['user_id'] ) : '';


        $resized_dimention_lenght =	isset( $_POST['resized_dimention_lenght'] ) ? sanitize_text_field( $_POST['resized_dimention_lenght'] ) : '';
        $resized_dimention_height =	isset( $_POST['resized_dimention_height'] ) ? sanitize_text_field( $_POST['resized_dimention_height'] ) : '';
        $resized_dimention_width =	isset( $_POST['resized_dimention_width'] ) ? sanitize_text_field( $_POST['resized_dimention_width'] ) : '';
        $resized_dimention_weight =	isset( $_POST['resized_dimention_weight'] ) ? sanitize_text_field( $_POST['resized_dimention_weight'] ) : '';

        $page_url = admin_url( 'admin.php?page=packagelist&user='.$user_id );

        // some basic validation
        if ( ! $warehouse_id ) {
            $errors[] = __( 'Error: Warehouse is required', 'webdevs' );
        }

        if ( ! $service_type ) {
            $errors[] = __( 'Error: Service type is required', 'webdevs' );
        }

        if ( ! $status ) {
            $errors[] = __( 'Error: Status is required', 'webdevs' );
        }

        if ( ! $merchant_order ) {
            $errors[] = __( 'Error: Merchant Order is required', 'webdevs' );
        }

        if ( ! $qty ) {
            $errors[] = __( 'Error: Quantity is required', 'webdevs' );
        }

        if ( ! $description ) {
            $errors[] = __( 'Error: Description is required', 'webdevs' );
        }

        $img_files  = '';
        if ( $_FILES ) {
            $files = $_FILES["my_file_upload"];
            foreach ($files['name'] as $key => $value) {
                if ($files['name'][$key]) {
                    $file = array(
                        'name' => $files['name'][$key],
                        'type' => $files['type'][$key],
                        'tmp_name' => $files['tmp_name'][$key],
                        'error' => $files['error'][$key],
                        'size' => $files['size'][$key]
                    );
                    $_FILES = array ("my_file_upload" => $file);
                    foreach ($_FILES as $file => $array) {
                        $image_id = my_handle_attachment($file,0);
                        $img =  wp_get_attachment_image_src(  $image_id, 'full' );
                        $img_files .= $img[0].',';
                    }


                }
            }
        }

        //  $img_files =  substr($img_files, 0, -1);



        // bail out if error found

        if ( $errors ) {
            $first_error = reset( $errors );
            $redirect_to = add_query_arg( array( 'error' => $first_error ), $page_url );
            wp_safe_redirect( $redirect_to );
            exit;
        }


        $fields = array(
            'warehouse_id' => $warehouse_id,
            'service_type' => $service_type,
            'status' => $status,
            'merchant_order' => $merchant_order,
            'reduction' => $reduction,
            'enclosure' => $enclosure,
            'qty' => $qty,
            'description' => $description,
            'classification' => $classification,
            'unit_cost' => $unit_cost,
            'date_received' => $date_received,
            'user_id' =>$user_id,
            'resized_dimention_lenght' =>$resized_dimention_lenght,
            'resized_dimention_height' =>$resized_dimention_height,
            'resized_dimention_width' =>$resized_dimention_width,
            'resized_dimention_weight' =>$resized_dimention_weight,
            'image_groups' => $img_files,
        );




        // New or edit?
        if ( ! $field_id ) {

            $insert_id = packagelist_insert_package( $fields );

        } else {

            $fields['id'] = $field_id;

            $insert_id = packagelist_insert_package( $fields );
        }

        if ( is_wp_error( $insert_id ) ) {
            $redirect_to = add_query_arg( array( 'message' => 'error' ), $page_url );
        } else {
            $redirect_to = add_query_arg( array( 'message' => 'success' ), $page_url );
        }

        wp_safe_redirect( $redirect_to );
        exit;
    }
}

new Form_Handler();