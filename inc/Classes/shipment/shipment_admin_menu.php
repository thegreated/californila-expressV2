<?php

/**
 * Admin Menu
 */
class shipment_admin_menu {

    /**
     * Kick-in the class
     */
    public function __construct() {
        add_action( 'admin_menu', array( $this, 'admin_menu' ) );
    }

    /**
     * Add menu items
     *
     * @return void
     */
    public function admin_menu() {

        /** Top Menu **/
        $notification_count  = $this->get_notification();
        add_submenu_page( 'californila_plugin', __( 'Shipment', 'webdevs' ), __( ($notification_count)? sprintf('Shipment <span class="awaiting-mod">%d</span>', $notification_count) : 'Shipment' , 'webdevs' ), 'manage_categories', 'shipment', array( $this, 'plugin_page' ) );


    }

    /**
     * Handles the plugin page
     *
     * @return void
     */
    public function plugin_page() {
        $action = isset( $_GET['action'] ) ? $_GET['action'] : 'list';
        $id     = isset( $_GET['id'] ) ? intval( $_GET['id'] ) : 0;

        switch ($action) {
            case 'view':

                $template = dirname( __FILE__ ) . '/views/schedule-single.php';
                break;

            case 'edit':
                $template = dirname( __FILE__ ) . '/views/shipment-edit.php';
                break;

            case 'new':
                $template = dirname( __FILE__ ) . '/views/shipment-new.php';
                break;
            case 'dashboard':
                $template = dirname( __FILE__ ) . '/views/schedule-list.php';
                break;

            default:
              //  $template = dirname( __FILE__ ) . '/views/schedule-list.php';
                $template = dirname( __FILE__ ) . '/views/schedule-dashboard.php';
                break;
        }

        if ( file_exists( $template ) ) {
            include $template;
        }
    }
    public function get_notification(){
        global $wpdb;
        $items = $wpdb->get_results( 'SELECT * FROM ' . $wpdb->prefix . 'shipment_schedule WHERE tracking_code IS NULL AND product_suggestion IS  NULL ');
        return count($items);

    }
}