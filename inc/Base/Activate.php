<?php
/**
 * @package  AlecadddPlugin
 */
namespace Inc\Base;

class Activate
{
    public static function activate() {

        $Activate = new Activate();
        $Activate->my_plugin_create_db();;
        flush_rewrite_rules();


    }



    public function my_plugin_create_db() {

        global $jal_db_version;
        $jal_db_version = "1.0";
        require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
        global $wpdb;
        global $jal_db_version;

        $table_name = $wpdb->prefix . "countrylist";

        $sql ="CREATE TABLE ".$table_name." (
					  id mediumint(9) NOT NULL AUTO_INCREMENT,
					  name VARCHAR(255),
					  phone_code INT(10),
					  date VARCHAR(255),
					  UNIQUE KEY id (id)
					);";


        dbDelta($sql);

        $table_name = $wpdb->prefix . "user_address";
        $sql ="CREATE TABLE ".$table_name." (
					  id mediumint(9) NOT NULL AUTO_INCREMENT,
					  first_name VARCHAR(255),
					  last_name VARCHAR(255),
					  address_name VARCHAR(255),
					  company VARCHAR(255),
					  delivery_address VARCHAR(255),
					  delivery_address_two VARCHAR(255),
					  states VARCHAR(255),
					  city VARCHAR(255),
					  zipcode VARCHAR(255),
					  primary_phone_id int,
					  primary_phone int(25),
					  second_phone_id int,
					  second_phone  int(25),
					  type VARCHAR(255),
					  country_id int,
					  date VARCHAR(255),
					  user_id mediumint(9),
					  post_id mediumint(9),
					  default_address int(2),
					  UNIQUE KEY id (id)
					);";
        dbDelta($sql);
        $table_name = $wpdb->prefix . "packages";
        $sql ="CREATE TABLE ".$table_name." (
                id mediumint(9) NOT NULL AUTO_INCREMENT,
                warehouse_id mediumint(9),
                address_id mediumint(9),
				user_id mediumint(9),
                service_type varchar(255),
                status varchar(255),
                merchant_order varchar(255),
                original_dimention_lenght int(10),
                original_dimention_width int(10),
                original_dimention_height int(10),
                original_dimention_weight int(10),
                resized_dimention_lenght int(10),
                resized_dimention_height int(10),
                resized_dimention_width int(10), 
                resized_dimention_weight int(10),
                reduction varchar(255),
                enclosure varchar(255),
                qty int(10),
                description varchar(255),
                classification varchar(255),
                unit_cost DOUBLE,
                date_received VARCHAR(255),
                image_groups MEDIUMTEXT ,
                shipment_id MEDIUMTEXT ,
                UNIQUE KEY id (id)
              );";
        dbDelta($sql);
        $table_name = $wpdb->prefix . "shipment_schedule";
        $sql ="CREATE TABLE ".$table_name." (
					  id mediumint(9) NOT NULL AUTO_INCREMENT,
					  date_shipped VARCHAR(255),
					  order_id     mediumint(9),
					  tracking_code  VARCHAR(255),
					  user_id     mediumint(9),
					  product_suggestion mediumint(9),
					  type VARCHAR(20),
					  date_created VARCHAR(255),
					  date_updated VARCHAR(255),
					  UNIQUE KEY id (id)
					);";
        dbDelta($sql);

        $table_name = $wpdb->prefix . "order_tracking_details";
        $sql ="CREATE TABLE ".$table_name." (
                      id mediumint(9) NOT NULL AUTO_INCREMENT,
                      tracking_code VARCHAR(255),
                      step int,
                      location VARCHAR(255),
                      datetime 	datetime,
                      piece int,
                      UNIQUE KEY id (id)
					);";
        dbDelta($sql);

        add_option("jal_db_versio    n", $jal_db_version);


    }




}