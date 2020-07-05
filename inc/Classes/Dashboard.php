<?php
/**
 * @package  californila-express
 */
namespace Inc\Classes;

use Inc\Base\BaseController;

class Dashboard extends BaseController
{
    public function register()
    {

        add_filter( 'shipment_data_count_', array($this,'select_shipment'),10,1);

    }

    public function select_shipment($status) {
        global $wpdb;
        // $status;
        $packages = $wpdb->get_results('SELECT * FROM '.$wpdb->prefix.'shipment_schedule WHERE order_id != "" ORDER BY  id DESC' );
        $count = 0;
        foreach ($packages as $package) {
            $order = wc_get_order(  $package->order_id );
            try{
                $order_data = $order->get_data();
                echo $order_data['status'];
                if($order_data['status'] == $status){
                    $count++;
                }
              }catch(Exception $ex){
                  
              }

        }
       // echo $count;

    }



    function new_modify_user_table() {

    }


    function new_modify_user_table_row() {




    }


}