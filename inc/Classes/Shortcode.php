<?php
/**
 * @package  AlecadddPlugin
 */
namespace Inc\Classes;

use Inc\Api\SettingsApi;
use Inc\Base\BaseController;

/**
 *
 */
class Shortcode extends BaseController
{


    public $settings;
    private $order_data;

    public function register()
    {
        $this->settings = new SettingsApi();
        add_shortcode('dashboard_order_number',array($this,'count_order_dashboard'));
        add_shortcode('dashboard_user_data',array($this,'show_user_data'));
        add_shortcode('dashboard_announcement',array($this,'announcelent_preview'));
        add_shortcode('dashboard_us_address',array($this,'us_warehouse'));
        add_shortcode('dashboard_default_address',array($this,'default_address'));
        add_shortcode('dashboard_address_list',array($this,'address_list'));
        add_shortcode('orders_all',array($this,'get_orders_data'));
        add_shortcode('orders_modified',array($this,'order_data_orders'));
        add_shortcode('test_add',array($this,'us_warehouse_test'));
        add_shortcode('get_user_total_warehouse_fee',array($this,'get_warehose_data'));
        add_shortcode('show_purchase_show_package',array($this,'purchase_show_package'));
        add_shortcode('show_purchase_button',array($this,'purchase_button'));
        //shipment_sent
        add_shortcode('shipment_sent',array($this,'shipment_sent'));
        //shipment_sent_count
        add_shortcode('shipment_sent_count',array($this,'shipment_sent_count'));
        //get_order_status_by_id
        add_shortcode('get_order_status',array($this,'get_order_status_by_id'));
        //tracking_page
        add_shortcode('tracking_data',array($this,'tracking_page'));

        add_shortcode('test22',array($this,'test2222'));
        add_shortcode('user_name', array($this,'getUser_name'));
        add_shortcode('redirect_not_login',array($this,'redirect_not_login'));

        add_shortcode('get_packages', array($this,'get_packages_trio'));
        add_shortcode('get_packages_standby',array($this,'get_packages_standby'));

        add_shortcode('get_total_data_warehouse_chargs',array($this,'all_warehouse_chargers'));

        add_shortcode('admin_total_shipment',array($this,'total_packages'));

        add_filter('send_email_when_change_box',array($this,'send_email_when_change_box_data',10,1));

        add_shortcode('purchase_table_data',array($this,'purchase_table'));
        add_shortcode('purchase_total_cost',array($this,'total_value'));

        add_shortcode('purchase_total_lbs',array($this,'total_kilo'));
    }



    //------------------------------------------------
    /*
    * Dashboard PAGE
    */
    //---------------------------------------------------------

    /*
    ** Dashboard user order count
    */
    public function count_order_dashboard($order){

        $customer_orders = get_posts(array(
            'numberposts' => -1,
            'meta_key' => '_customer_user',
            'orderby' => 'date',
            'order' => 'DESC',
            'meta_value' => get_current_user_id(),
            'post_type' => wc_get_order_types(),
            'post_status' => array_keys(wc_get_order_statuses()), 'post_status' => array( $order['order']),
        ));
        $count_pending = 0;
        $Order_Array = []; //
        foreach ($customer_orders as $customer_order) {
            $orderq = wc_get_order($customer_order);
            $Order_Array[] = [
                "ID" => $orderq->get_id(),
                "Value" => $orderq->get_total(),
                "Date" => $orderq->get_date_created()->date_i18n('Y-m-d'),
            ];
            $count_pending++;

        }
        if(isset($order['orders_page'])){
            if( $count_pending == 0)
            {
                return '';
            }else{
                return '('.$count_pending.')';
            }
        }
        return $count_pending;
    }
    /*
    ** Dashboard user details
    */
    public function show_user_data(){

        $current_user = wp_get_current_user();
        $url = get_stylesheet_directory_uri();
        return ' <div class="statistic-right-area notika-shadow mg-tb-30 sm-res-mg-t-0">
					<img src="'.$url.'/includes/images/profile.jpg" width="150" class="img-circle " style="margin:30px" />
					<div class="text text-center"> <h4>'.$current_user->user_firstname.' '.$current_user->user_lastname.'</h4>
						<p>'.$current_user->user_email.'</p>
					</div>
					 <hr style="width:100%">

					<div class="">
						<div class="past-statistic-ctn text text-center">
							<h3 >Customer id</h3>
							<p>'.$current_user->ID.'</p>
						</div>
					</div>
				</div>';
    }
    /*
    ** Dashboard announcement preview
    */
    public function announcelent_preview() {
        global $wp_query;
        global $post;
        $data = array( 'post_type'   => 'announcement','post_status' => 'publish');
        $loop =  new \WP_Query($data);

        if( ! $loop->have_posts() ) {
            return false;
        }
        $data = '';
        while( $loop->have_posts() ) {

            $loop->the_post();
            $data .= the_excerpt();
        }

        wp_reset_postdata();
        return $data;
    }
    /*
    ** Dashboard united states warehouse
    */
    public function us_warehouse(){
        $args = array( 'post_type'   => 'address','post_status' => 'publish',"s" => 'United States');
        $us_address = new \WP_Query( $args );
        if( $us_address->have_posts() ) :
            $warehouse_data = '';
            while( $us_address->have_posts() ) : $us_address->the_post();

                $image  = get_post_meta( get_the_ID(), 'addressList_image_flag', true );
                $country  = get_post_meta( get_the_ID(), 'addressList_shop_country', true );
                $address  = get_post_meta( get_the_ID(), 'addressList_shop_address', true );
                $name  = get_post_meta( get_the_ID(), 'addressList_shop_name', true );

                $warehouse_data .=' <div class="card-body">
                                        <div class="row">
                                            <div class="col">
                                                <h5 class="card-title text-uppercase text-muted mb-0">US WAREHOUSE | '.the_title().'</h5>
                                                <span class="h2 font-weight-bold mb-0">
                                                '.$name.'<br/> '.$address.' <br/> 
                                                </span>
                                            </div>
                                            <div class="col-auto">
                                                <div class="icon icon-shape bg-orange text-white rounded-circle shadow">
                                                    <i class="ni ni-send"></i>
                                                </div>
                                            </div>
                                        </div>
                                        <p class="mt-3 mb-0 text-sm">
                                            <span class="text-nowrap">'.$country.'</span>
                                        </p>
                                    </div>';


            endwhile;
            wp_reset_postdata();
        else :
            esc_html_e( 'No Address yet', 'text-domain' );
        endif;
        return $warehouse_data;
    }

    /*
    ** Dashboard default address
    */
    public function default_address(){
        global $wpdb;
        $id = get_current_user_id();
        $default_address = $wpdb->get_results( "SELECT * FROM wp_3_user_address WHERE user_id=".$id." AND default_address=1");
        $address = '';

        foreach ( $default_address as $add )   {
            //$address .= $country;
            $address .=' <div class="card-body">
                                <div class="row">
                                    <div class="col">
                                        <h5 class="card-title text-uppercase text-muted mb-0">DEFAULT DELIVERY ADDRESS</h5>
                                        <span class="h2 font-weight-bold mb-0">
                                        '.$add->first_name.' '.$add->last_name.'<br/>'.$add->address_name.'  '.$add->delivery_address.' '.$add->states.' '.$add->city.' '.$add->zipcode.' <br/> 
                                        </span>
                                    </div>
                                    <div class="col-auto">
                                        <div class="icon icon-shape bg-orange text-white rounded-circle shadow">
                                            <i class="ni ni-send"></i>
                                        </div>
                                    </div>
                                </div>
                                <p class="mt-3 mb-0 text-sm">
                                    <span class="text-nowrap">'.$add->country.'</span>
                                </p>
                            </div>';
        }
        return $address;
    }

    public  function address_list(){
        global $wpdb;
        $addressList = '';
        $test_data = 1;
        $user_address = $wpdb->get_results( "SELECT * FROM wp_3_user_address WHERE user_id=".get_current_user_id());
        //var_dump($user_address);
        foreach ( $user_address as $address )   {
          
                $addressList .= '<tr>
				   <th scope="row">'.$address->first_name.'  '.$address->last_name.'</th>
				  <td>'.$address->address_name.'  '.$address->delivery_address.' '.$address->states.'  '.$address->city.' '.$address->zipcode.'</td>
		
                  <td> <label><input type="radio" id="defaultAddress" name="defaultAddress" value="'.$address->id.'" onclick="javascript:makeDefaultAddress('.$address->id.');" ';
                  
                if($address->default_address) {
                    $addressList .=  'checked';
                }
                $addressList .= '><span class="lbl padding-8"></span></label> </td>';

                 if(!$address->default_address) {
                    $addressList .= '<td><input type="button" class="btn btn-danger"  onclick="javascript:ConfirmDelete('.$address->id.','.$test_data.');" value="delete" /></td>';
                 }  
                  
				 $addressList .='</tr>';


         

        }

        return $addressList;

    }

    //------------------------------------------------
    /*
    * ORDERR PAGE
    */
    //---------------------------------------------------------


    function get_orders_data(){
        $customer_orders = get_posts(array(
            'numberposts' => -1,
            'meta_key' => '_customer_user',
            'orderby' => 'date',
            'order' => 'DESC',
            'numberposts' => 5,
            'meta_value' => get_current_user_id(),
            'post_type' => wc_get_order_types(),
            'post_status' => array_keys(wc_get_order_statuses()),
        ));
        $count_pending = 0;
        $Order_Array = []; //
        foreach ($customer_orders as $customer_order) {
            $orderq = wc_get_order($customer_order);
            $Order_Array[] = [
                "ID" => $orderq->get_id(),
                "Value" => $orderq->get_total(),
                "Date" => $orderq->get_date_created()->date_i18n('Y-m-d'),
                "Status" => $orderq->get_status(),
            ];
            $count_pending++;
        }

        $pachage = $Order_Array;
        $orderData = '';
        foreach($pachage as $pak){
            $class = '';
            $id = $pak['ID'];
            switch($pak['Status'])
            {
                case 'arrival-shipment':
                    $class = 'on_the_way';
                    $status = 'On the way';
                    break;
                case 'on-hold' :
                    $class = 'pending_payment';
                    $status = 'Pending payment';
                    break;
                case 'processing' :
                    $class = 'preparing_shipment';
                    $status = 'Preparing for shipment';
                    break;
                default:
                    $class = 'delivered';
                    $status = 'Delivered';
                    break;


            }


            $orderData .= '	<tr>
								 <td><a href="../my-account/view-order/'.$id.'/" target="_blank">'.$id.' </a></td>
								
								 <td>'.$pak['Date'].'</td>
							 
								 <td><span class="'.$class.'" style="padding:10px;color:white; text-transform: uppercase;font-size:10px"><strong> '.$status.' </strong> </span></td>
								 </tr>';


        }
        return $orderData;
    }

    public function order_data_orders($order){

        $customer_orders = get_posts(array(
            'numberposts' => -1,
            'meta_key' => '_customer_user',
            'orderby' => 'date',
            'order' => 'DESC',

            'meta_value' => get_current_user_id(),
            'post_type' => wc_get_order_types(),
            'post_status' => array_keys(wc_get_order_statuses()), 'post_status' => array( $order['order']),
        ));
        $count_pending = 0;
        $Order_Array = []; //
        foreach ($customer_orders as $customer_order) {
            $orderq = wc_get_order($customer_order);
            $Order_Array[] = [
                "ID" => $orderq->get_id(),
                "Value" => $orderq->get_total(),
                "Date" => $orderq->get_date_created()->date_i18n('Y-m-d'),
                "Status" => $orderq->get_status(),
            ];
            $count_pending++;

        }
        $orderData = '';
        foreach($Order_Array as $pak){
            $id = $pak['ID'];
            $class = '';
            $status = '';
            switch($pak['Status'])
            {
                case 'arrival-shipment':
                    $class = 'on_the_way';
                    $status = 'On the way';
                    break;
                case 'wc-on-hold' :
                    $class = 'pending_payment';
                    $status = 'Pending payment';
                    break;
                case 'processing' :
                    $class = 'preparing_shipment';
                    $status = 'Preparing for shipment';
                    break;
                default:
                    $class = 'delivered';
                    $status = 'Delivered';
                    break;


            }

            $orderData .= '	<tr>
							 <td><a href="../my-account/view-order/'.$id.'/" target="_blank">'.$id.' </a></td>
							
							 <td>'.$pak['Date'].'</td>
						 
							 <td><span class="'.$class.'" style="padding:10px;color:white; text-transform: uppercase;font-size:10px"><strong> '.$status.' </strong> </span></td>
							 </tr>';

        }
        return $orderData;
        //return $Order_Array;
    }
    //-------packages

    public function get_warehose_data(){
        $user_id = get_current_user_id();
        global $wpdb;
        $items = $wpdb->get_results( 'SELECT * FROM wp_3_packages WHERE user_id='.$user_id.' AND status="Schedule To Ship"' );
        $warehouse_charges = 0;
        foreach ($items as $result) {
            $warehouse_days = $this->get_num_of_date( $result->date_received);
            $warehouse_charges += $this->get_warehouse_charges($warehouse_days,$result->resized_dimention_weight);
        }
        return $warehouse_charges;
    }
    private function get_num_of_date($date_received){
        date_default_timezone_set('America/Chicago');
        $now = time(); // or your date as well
        $your_date = strtotime($date_received);
        $datediff = $now - $your_date;

        return round($datediff / (60 * 60 * 24));
    }

    public function all_warehouse_chargers(){
        global $wpdb;
        $id = get_current_user_id();
        $items = $wpdb->get_results( 'SELECT * FROM wp_3_packages WHERE user_id='.$id.' AND status = "Schedule To Ship" ' );

        $warehouse_charges = 0;

        foreach ($items as $result) {
            $warehouse_days = $this->get_num_of_date( $result->date_received);
            $warehouse_charges += $this->get_warehouse_charges($warehouse_days);
        }
        return $warehouse_charges;



    }
    private function get_warehouse_charges($days){

            $days = (int)$days;

            $charge = 0;
            // 31-60days
            if($days >= 31 && $days <= 60){
                return  floatval(50.32 );
            }
            // 61-90 days
            else if($days >= 61 && $days <= 90 ){

                return  floatval(100.65 );

            }
            // 91 days or greater than
            else if($days >= 91){
                return  floatval( 150.97 );
            }
            return $charge;


        }

    /*
     * purchase page
     */
    public  function  purchase_show_package(){
        global $wpdb;
        $id = get_current_user_id();
        $items = $wpdb->get_results('SELECT * FROM wp_3_packages WHERE user_id=' . $id.' AND status != "On Box" ' );
        $add = $wpdb->get_results('SELECT adds.address_name, adds.delivery_address, adds.states, adds.city,adds.city,adds.zipcode , c.name FROM wp_3_user_address as adds INNER JOIN wp_3_countrylist as c ON adds.country_id = c.id   WHERE adds.user_id=' . $id . ' AND ' . 'adds.default_address = 1');

        $counter = 0;
        $checkDataAllchecked = '';

        foreach ($items as $result) {

            $title = $wpdb->get_results('SELECT post_title FROM wp_3_posts WHERE ID=' . $result->warehouse_id);

            $items[$counter]->warehouse_name = $title[0]->post_title;

            if ($items->address_id == '') {
                $fullAddress = $add[0]->address_name . '  ' . $add[0]->delivery_address . ' ' . $add[0]->states . ' ' . $add[0]->city . ' ' . $add[0]->zipcode . ' ' . $add[0]->name;
            }


            $background = '';
            switch ($result->status) {
                case 'Schedule To Ship':
                    $background = '#98f669';

                    break;
                case 'Ready To Ship':
                    $background = '#ffff7d';
                    break;
                default:
                    $background = '#ff989e';
                    break;

            }
            echo '<tr style="background-color:' . $background . '">';
            if ($result->status == 'Ready To Ship' && $result->shipment_id == '') {
                echo '<td> <input type="checkbox" name="list_package[]" value="' . $result->id . '" /> </td>';

            } else if (!empty($result->shipment_id)) {
                echo '<td>   <span class="badge badge-info"><i class="ni ni-bag-17"></i> </span> </td>';
            } else {
                echo '<td></td>';
            }
            echo '<td> ' . $result->date_received . ' </td>';
            echo '<td>' . $title[0]->post_title . '</td>';
            echo '<td> ' . $result->merchant_order . ' </td>';
            echo '<td> P' . $result->unit_cost . ' </td>';
            echo '<td> ' . $result->resized_dimention_weight . ' kg </td>';
            echo '<td> ' . $fullAddress . ' </td>';
            echo '<td> ' . $result->status . ' </td>';
            if ($result->status == 'On Hold' || $result->status == 'Ready To Ship' && $result->shipment_id == '') {
                echo '<td> <a href="../view-package/?packages=' . $result->id . '" class="btn btn-icon btn-primary" type="button"><span class="btn-inner--icon"><i class="ni ni-settings-gear-65"></i></span></a></td>';
            } else {
                echo '<td></td>';
            }
            echo '</tr>';

        }


    }

    public function purchase_button(){
        $id = get_current_user_id();
        global $wpdb;
        $items = $wpdb->get_results('SELECT * FROM wp_3_packages WHERE user_id=' . $id .' AND  status != "On Box"');
        if(empty($items[0]->shipment_id)){
            echo '<input type="button" id="selected_package" class="btn btn-danger"    data-toggle="modal" data-target="#shipment_modal" value="Ship Selected" >';
        }
        if(!empty($items[0]->shipment_id)){
            echo '   <a href="./purchase/" id="pay_package" class="btn btn-success">Select box </a>';
            echo '<input type="button" id="ship_more_package" class="btn btn-danger"  onclick="javascript:reset_schedule('.$id.')" value="I will Ship more package" >';
        }
    }

    public function shipment_sent($status){ 
       
        global $wpdb;
        $meta_value = get_current_user_id();

        $packages = $wpdb->get_results('SELECT * FROM '.$wpdb->prefix.'shipment_schedule WHERE user_id="'.$meta_value.'"  ORDER BY  id DESC' );
        foreach ($packages as $package) {
            if($package->order_id != "") {
                
                    $order = wc_get_order( $package->order_id );
                    $order_data = $order->get_data();
                   // echo $status['status'].' = '.$order_data['status'].'<br/>';
                    if($status['status'] == $order_data['status'] ){
                        $shipment = $wpdb->get_results('SELECT post_id FROM ' . $wpdb->prefix . 'postmeta WHERE meta_value="' . $package->id . '"  AND meta_key="package_list_shipping_id" ');;
                        $total_package = count($shipment);
                        echo '<tr>';
                        echo '<td>' . $package->date_shipped . '</td>';

                        echo '<td>' . $total_package . ' Packages</td>';

                        echo '<td>' . $package->tracking_code . ' </td>';
                        echo '<td>' . $result->delivery_address . ' ' . $result->states . ' ' . $result->city . ' ' . $result->zipcode . '</td>';
                        echo '<td>' .  $this->get_status_data($order_data['status']). '</td>';
                        if ($package->tracking_code != "") {
                            echo '<td><a  class="btn btn-icon btn-primary" type="button" href="../track-package/?tracking=' . $package->tracking_code . '"> Track </a></td>';
                        } else {

                        }
                        echo '</tr>';
                  }

                
            }

        }

    }
    public function shipment_sent_count($status){ 
       
        global $wpdb;
        $meta_value = get_current_user_id();
        $packages = $wpdb->get_results('SELECT * FROM '.$wpdb->prefix.'shipment_schedule WHERE user_id="'.$meta_value.'"  ORDER BY  id DESC' );
        $count = 0;
        foreach ($packages as $package) {
            if($package->order_id != "") {
                $order = wc_get_order( $package->order_id );
                $order_data = $order->get_data();
                if($status['status'] == $order_data['status'] ){
                    $count++;
                }
                
            }
        }
        echo $count;
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

    public function get_order_status_by_id($id){

        $order = wc_get_order( $id );
        return $order->post_status;;
    }

    public function tracking_page($data){
        $id = get_current_user_id();
        global $wpdb;
        $sql  =  "SELECT s.order_id , s.date_shipped, p.service_type as type , COUNT(p.id) as package, SUM(p.resized_dimention_weight) as weight, s.tracking_code as tracking , ad.delivery_address, ad.states, ad.city, ad.zipcode  ";
        $sql  .=  "FROM wp_3_shipment_schedule AS s ";
        $sql  .=  "INNER JOIN wp_3_packages  as p  ON s.id   =  p.shipment_id ";
        $sql  .=  "INNER JOIN wp_3_user_address AS ad  ON ad.user_id =  s.user_id ";
        $sql  .= "WHERE p.status = 'On Box' AND ad.default_address = 1 AND ad.user_id =".$id." AND s.tracking_code = '".$data."' ";
        $items = $wpdb->get_results($sql);

        echo '<ul class="list-group">';
        echo '  <li class="list-group-item"> <h3>Tracking Code: bxA3dPz7oN6ZZB6JkJPw </h3> </li>';
        echo ' <li class="list-group-item"><h4>Status: Processing </h4></li>';
        echo ' <li class="list-group-item">Date ship: 6/30/2020 </li>';
        echo ' <li class="list-group-item"> type: Consolidation</li>';
        echo ' <li class="list-group-item"> Payment Method: Bank transfer</li>';
        echo ' </ul>';

        echo  '<table class="table align-items-center table-flush">';
        echo '              <thead class="thead-light">';
        echo '              <tr>';
        echo '                   <th scope="col">Product image</th>';
        echo '                    <th scope="col">Description</th>';
        echo '                     <th scope="col">Quantity</th>';
        echo '                    </tr>';
        echo '                   </thead>';
        echo '                   <tbody>';
        echo '                   <tr>';
        echo '                   <td> <img src="http://team661.com/consolidators/wp-content/uploads/sites/3/2020/06/b2.jpg"  height="100px" /> </td>';
        echo '                   <td>Books of Almanac</td>';
        echo '                   <td> x1 </td>';
        echo '                   </tr>';
        echo '                   <tr>';
        echo '                   <td> <img src="http://team661.com/consolidators/wp-content/uploads/sites/3/2020/06/4.jpg"  height="100px"   /> </td>';
        echo '                   <td>Vivo Phone</td>';
        echo '                   <td> x1 </td>';
        echo '                   </tr>';
        echo '                   <tr>';
        echo '                  <td> <img src="http://team661.com/consolidators/wp-content/uploads/sites/3/2020/06/a1.jpg"  height="100px"   /> </td>';
        echo '                   <td>Jacket</td>';
        echo '                   <td> x1 </td>';
        echo '                   </tr>';
        echo '                   </tbody>';
        echo '                   </table>';






    }

    public function test2222(){

        $args = array(
            'role'    => 'warehouse_manager',
            'orderby' => 'ID',
            'order'   => 'ASC'
        );
        $users = get_users( $args );

        echo var_dump($users[0]->user_email);
    }
    public function getUser_name($id){
        $user = get_user_by( 'id', $id );
        $user = $user->first_name .' '.$user->last_name;
        echo $id;

    }


    public function redirect_not_login(){
        if (!is_user_logged_in()) {
            $page = get_page_by_title('login');
            wp_redirect(get_permalink($page->ID));

        }
    }
    //--- package

    public function get_packages_trio($order)
    {
        global $wpdb;

        $user_id = get_current_user_id();
        $address = $wpdb->get_results('SELECT  * FROM ' . $wpdb->prefix . 'user_address WHERE user_id = ' . $user_id . ' AND default_address = 1');
        $meta_value = get_current_user_id();
        $packages = $wpdb->get_results('SELECT post_id FROM '.$wpdb->prefix.'postmeta WHERE meta_key="package_list_user_id" AND meta_value="'.$meta_value.'"  ORDER BY  meta_id DESC' );

        if (!empty($address)) {
            echo '<table class="table align-items-center table-flush">
                        <thead class="thead-light">
                        <tr>
                            <th scope="col">Days</th>
                            <th scope="col">Date Received</th>
                            <th scope="col">Warehouse</th>
                            <th scope="col">Merchant</th>
           
                            <th scope="col">Delivery Address(Default)</th>
                            <th scope="col">Status</th>
                            <th scope="col"> Quantity </th>
                            <th scope="col">Modify</th>
                        </tr>
                        </thead>
                        <tbody>';


            foreach ($packages as $package){

                $datetime_format = get_post_meta( $package->post_id, 'package_list_datetime', true );
                $status = get_post_meta($package->post_id,'package_list_status',true);
                $warehouse = get_post_meta($package->post_id,'package_list_warehouse',true);
                $merchant = get_post_meta($package->post_id,'package_list_merchant_name',true);
                $type = get_post_meta($package->post_id,'package_list_type',true);
                
                $datetime = date("F j, Y, g:i a",$datetime_format);
                $number_of_date = $this->get_number_of_dates($datetime);
                $color = ($number_of_date <= 30) ? 'default' : 'danger';
                $modsta = "Ready To Ship";
                $quantity = get_post_meta($package->post_id,'package_list_quantity',true);

                if($status == "On Box" || $status == ""){

                }elseif($type == $order['order']){

                    echo '<tr style="background-color:' . $this->package_color($status) . '">';
                    echo '<td><span class="badge badge-pill badge-'.$color.'">'.$number_of_date.'</span></td>';
                    echo '<td>' . $datetime . '</td>';
                    echo '<td>' . $warehouse . '</td>';
                    echo '<td>' . $merchant . '</td>';

                    echo '<td>' . $address[0]->address_name.' '.$address[0]->delivery_address.' '.$address[0]->states. '  '.$address[0]->city. '</td>';
                    echo '<td>' . $status . '</td>';
                    echo '<td>x' . $quantity . '</td>';
                    echo '<td>
';
                    if($status == "Ready To Ship") {
                        echo ' <button onclick="javascript:change_to_reserve(' . $package->post_id . ',1);" class="btn btn-icon btn-primary" type="button"><span class="btn-inner--icon"><i class="ni ni-bold-down"></i></span></button>';
                    }
                     echo ' <a href="../view-package/?packages=' . $package->post_id. '" class="btn btn-icon btn-primary" type="button"><span class="btn-inner--icon"><i class="ni ni-settings-gear-65"></i></span></a>
                                </td>';

                    echo '</tr>';
                   }

                 }
                     echo   '</tbody></table>';
              }

    }
    public function get_packages_standby($order)
    {
        global $wpdb;

        $user_id = get_current_user_id();
        $address = $wpdb->get_results('SELECT  * FROM ' . $wpdb->prefix . 'user_address WHERE user_id = ' . $user_id . ' AND default_address = 1');
        $meta_value = get_current_user_id();
        $packages = $wpdb->get_results('SELECT post_id FROM '.$wpdb->prefix.'postmeta WHERE meta_key="package_list_user_id" AND meta_value="'.$meta_value.'"  ORDER BY  meta_id DESC' );

        if (!empty($address)) {
            echo '<table class="table align-items-center table-flush">
                        <thead class="thead-light">
                        <tr>
                            <th scope="col">Days</th>
                            <th scope="col">Date Received</th>
                            <th scope="col">Warehouse</th>
                            <th scope="col">Merchant</th>
           
                            <th scope="col">Delivery Address(Default)</th>
                            <th scope="col">Status</th>
                            <th scope="col">Modify</th>
                        </tr>
                        </thead>
                        <tbody>';
            $ja = $this->check_status_for_validation();

            foreach ($packages as $package){


                $datetime_format = get_post_meta( $package->post_id, 'package_list_datetime', true );
                $status = get_post_meta($package->post_id,'package_list_status',true);
                $warehouse = get_post_meta($package->post_id,'package_list_warehouse',true);
                $merchant = get_post_meta($package->post_id,'package_list_merchant_name',true);
                $type = get_post_meta($package->post_id,'package_list_type',true);
                $datetime = date("F j, Y, g:i a",$datetime_format);
                $number_of_date = $this->get_number_of_dates($datetime);
                $color = ($number_of_date <= 30) ? 'default' : 'danger';


                if($status == ""){
                       $page = 1;
                    if($order['order'] == "Personal Shopper")
                        $page = 2;

                    echo '<tr">';
                    echo '<td><span class="badge badge-pill badge-'.$color.'">'.$number_of_date.'</span></td>';
                    echo '<td>' . $datetime . '</td>';
                    echo '<td>' . $warehouse . '</td>';
                    echo '<td>' . $merchant . '</td>';

                    echo '<td>' . $address[0]->address_name.' '.$address[0]->delivery_address.' '.$address[0]->states. '  '.$address[0]->city. '</td>';
                    echo '<td>' . $status . '</td>';

                    echo '<td>';
                    if($ja!=0) {
                        echo '<button onclick="javascript:change_to_reserve(' . $package->post_id . ',2,'.$page.');" class="btn btn-icon btn-primary" type="button"><span class="btn-inner--icon"><i class="ni ni-bold-up"></i></span></button>';
                    }


                    echo '</td></tr>';
                }
            }
            echo   '</tbody></table>';
        }else{
            echo '  <div class="row" style="margin:20px">
                            <div class="alert alert-warning" role="alert">
                                <strong>Warning!</strong> You need to have a default address in order to proceed on package list.
                                                            click <a href="#">Click here </a> to add an new default address.
                            </div>
                         </div>';
        }

    }
    function check_status_for_validation(){
        global $wpdb;
        $meta_value = get_current_user_id();
        $packages = $wpdb->get_results('SELECT post_id FROM '.$wpdb->prefix.'postmeta WHERE meta_key="package_list_user_id" AND meta_value="'.$meta_value.'"  ORDER BY  meta_id DESC' );
        $data = 0;
        $data2 = 0;
        foreach ($packages as $package) {
            $status = get_post_meta($package->post_id,'package_list_status',true);

            if($status == "Ready To Ship" ) {$data++;}
            if($status == "Pick up" ) {$data2++;}
            if($status == "Schedule To Ship" ) {$data2++;}

        }
        if($data == 0 && $data2 == 0 ){
            return 1;
        }

        return $data;

    }
    function package_color($status){

        switch ($status) {
            case 'Schedule To Ship':
                return  '#98f669';

                break;
            case 'Ready To Ship':
                return  '#ffff7d';
                break;
            default:
                return  '#ff989e';
                break;

        }
    }

    public function get_number_of_dates($date){
        date_default_timezone_set('America/Los_Angeles');
        $now = time(); // or your date as well
        $your_date = strtotime($date);
        $datediff = $now - $your_date;

        return round($datediff / (60 * 60 * 24));
    }
   public function total_packages($shipment_id){
        global $wpdb;
;
        $packages = $wpdb->get_results('SELECT post_id FROM '.$wpdb->prefix.'postmeta WHERE meta_key="package_list_shipping_id" AND meta_value="'.$shipment_id['shipment'].'"  ORDER BY  meta_id DESC' );
        $data = 0;
       // echo var_dump($packages);
        foreach ($packages as $package){
            $status = get_post_meta($package->post_id,'package_list_status',true);
            $qty = get_post_meta($package->post_id,'package_list_quantity',true);
            if($status == "Schedule To Ship" ) {
                $data += (int)$qty;
            }


        }
        return $data ;
    }
    public function total_value($shipment_id){
        global $wpdb;
        ;
        $packages = $wpdb->get_results('SELECT post_id FROM '.$wpdb->prefix.'postmeta WHERE meta_key="package_list_shipping_id" AND meta_value="'.$shipment_id['shipment'].'"  ORDER BY  meta_id DESC' );
        $data = 0;
        // echo var_dump($packages);
        foreach ($packages as $package){
            $status = get_post_meta($package->post_id,'package_list_status',true);
            $qty = get_post_meta($package->post_id,'package_list_quantity',true);
            $cost = get_post_meta($package->post_id,'package_list_cost',true);
            if($status == "" ) {
                $data += ($cost*(int)$qty);
            }


        }
        return $data ;
    }
    public function total_kilo($shipment_id){
        global $wpdb;
        ;
        $packages = $wpdb->get_results('SELECT post_id FROM '.$wpdb->prefix.'postmeta WHERE meta_key="package_list_shipping_id" AND meta_value="'.$shipment_id['shipment'].'"  ORDER BY  meta_id DESC' );
        $data = 0;
        // echo var_dump($packages);
        foreach ($packages as $package){
            $status = get_post_meta($package->post_id,'package_list_status',true);
            $qty = get_post_meta($package->post_id,'package_list_quantity',true);
            $kilo = get_post_meta($package->post_id,'package_list_weight',true);
            if($status == "Schedule To Ship" ) {
                $data += ($kilo*(int)$qty) * floatval(2.20462);
            }


        }
        return $data ;
    }
    public function total_packages_sent($shipment_id){
        global $wpdb;
        ;
        $packages = $wpdb->get_results('SELECT post_id FROM '.$wpdb->prefix.'postmeta WHERE meta_key="package_list_shipping_id" AND meta_value="'.$shipment_id['shipment'].'"  ORDER BY  meta_id DESC' );
        $data = 0;
        // echo var_dump($packages);
        foreach ($packages as $package){
            $status = get_post_meta($package->post_id,'package_list_status',true);
            $qty = get_post_meta($package->post_id,'package_list_quantity',true);
            if($status == "On Box" ) {
                $data += (int)$qty;
            }


        }
        return $data ;
    }
    public function send_email_when_change_box_data($to){
        $body = 'Hi Good day <br/><br/>';
        $body .= 'Your Package has has been set by the warehouse manager. You can now pay the box to continue transaction <br/> <br/> <hr/><br/> ';
        $body .= 'Login and visit this link to continue. <a href="http://team661.com/consolidators/purchase/"> Click here</a>';
        $headers = array('Content-Type: text/html; charset=UTF-8');
        $subject = 'Package box has been set by the warehouse manager';
        wp_mail( $to['to'], $subject, $body, $headers );
    }

    public function purchase_table(){
        $id  = get_current_user_id();
        global $wpdb;
        $packages = $wpdb->get_results('SELECT post_id FROM '.$wpdb->prefix.'postmeta WHERE meta_key="package_list_user_id" AND meta_value="'.$id.'"  ORDER BY  meta_id DESC' );
        $add = $wpdb->get_results( 'SELECT adds.address_name, adds.delivery_address, adds.states, adds.city,adds.city,adds.zipcode , c.name FROM wp_3_user_address as adds INNER JOIN wp_3_countrylist as c ON adds.country_id = c.id   WHERE adds.user_id='.$id .' AND '.'adds.default_address = 1' );

        $counter = 0;
        $checkDataAllchecked = '';
        $total_package = 0;
        $total_weight = 0;
        $total_value = 0;
        $warehouse_charges = 0;
        foreach ($packages as $package) {
            $status = get_post_meta($package->post_id, 'package_list_status', true);

            if ($status == "Schedule To Ship") {
                $quantity = get_post_meta($package->post_id,'package_list_quantity',true);
                $datetime_format = get_post_meta($package->post_id, 'package_list_datetime', true);
                $datetime = date("F j, Y, g:i a", $datetime_format);
                $number_of_date = $this->get_number_of_dates($datetime);
                $warehouse = get_post_meta($package->post_id, 'package_list_warehouse', true);
                $merchant = get_post_meta($package->post_id, 'package_list_merchant_name', true);
                $cost = get_post_meta($package->post_id, 'package_list_cost', true);
                $weight = get_post_meta($package->post_id, 'package_list_weight', true);
                $shipping_id = get_post_meta($package->post_id, 'package_list_shipping_id', true);
              //  $items[$counter]->warehouse_name = $title[0]->post_title;

             //   if ($items->address_id . '' == '') {
            //        $fullAddress = $add[0]->address_name . '  ' . $add[0]->delivery_address . ' ' . $add[0]->states . ' ' . $add[0]->city . ' ' . $add[0]->zipcode . ' ' . $add[0]->name;
            //    }
                $background = '#98f669';

                echo '<tr style="background-color:' . $background . '">';
                if ($status == 'Ready To Ship' && $shipping_id == '') {
                    echo '<td> <input type="checkbox" name="list_package[]" value="' . $package->post_id. '" /> </td>';

                } else if (!empty($shipping_id)) {
                    echo '<td>   <span class="badge badge-info"><i class="ni ni-bag-17"></i> </span> </td>';
                } else {
                   echo '<td></td>';
                }
                echo '<td> ' . $datetime. ' </td>';
                echo '<td>' . $warehouse. '</td>';
                echo '<td> ' . $merchant. ' </td>';
                echo '<td> $' . $cost . ' </td>';
                echo '<td> ' . $weight . ' kg </td>';
                echo '<td> x' . $quantity . ' </td>';
                echo '<td> <a href="#" class="badge badge-pill badge-success">' . $number_of_date . '</a>
                                        </td>';

                echo '</tr>';
                //  $warehouse_charges += get_warehouse_charges($warehouse_days,$result->resized_dimention_weight);

            }
        }
    }

}
