<?php
/**
 * @package  californila-express
 */
namespace Inc\Classes;



class Packages
{
    public function register()
    {

        add_filter( 'verify_address', array($this,'check_default_address',2,1 ));
        add_filter('user_package_budge',array($this,'pakage_user_badge'),10,1);
        add_filter('box_user_badge',array($this,'box_user_badge'),10,1);
        add_filter('view_package',array($this,'view_package_more_detailed'),10,1);
        add_filter('validate_package_cost',array($this,'package_add_cost'));
        add_action('package_button_data',array($this,'package_button_data'));
        add_action('init', array($this,'schedule_package'));
        add_action('init', array($this,'reset_schedule'));

    }
    function schedule_package(){
        global $wpdb;

        if (isset( $_POST['action']) && 'POST' == $_SERVER['REQUEST_METHOD'] && !empty( $_POST['action'] ) && $_POST['action'] == 'add-shipment-schedule' ) {


            $page = get_page_by_title('packages');
            $current_user_id = get_current_user_id();
            $date = $_POST['date_shipped'];
            $type =  isset( $_POST['type'] ) ? sanitize_text_field( $_POST['type'] ) : '';
           if (preg_match("/^[0-9]{1,2}\/[0-9]{1,2}\/[0-9]{4}$/ ",$date)) {
//(0[1-9]|[1-2][0-9]|3[0-1])$

                $today = date('m/d/Y');
                //var_dump($date);
                    if (strtotime($date) < strtotime('now')) {
                    wp_redirect(get_permalink($page->ID) . '?errors=date_invalid');
                    return;

                }
                $havemeta = get_user_meta($current_user_id, 'shipment_schedule_id', false);
                $shipment_id = '';
                if ($havemeta === []) {
                    $wpdb->insert($wpdb->prefix.'shipment_schedule', array('user_id' => $current_user_id, 'date_shipped' => $date, 'date_created' => $today,'type' => $type));
                    $shipment_id = $wpdb->insert_id;
                    add_user_meta($current_user_id, 'shipment_schedule_id', $shipment_id);

                } else {
                    $shipment_id = $havemeta;
                    update_user_meta($current_user_id, 'shipment_schedule_id', $shipment_id);
                }

                //get all product
                $meta_value = get_current_user_id();
                $packages = $wpdb->get_results('SELECT post_id FROM '.$wpdb->prefix.'postmeta WHERE meta_key="package_list_user_id" AND meta_value="'.$meta_value.'"  ORDER BY  meta_id DESC' );

                foreach ($packages as $package) {
                    $status = get_post_meta($package->post_id,'package_list_status',true);
                    if($status == "Ready To Ship") {
                        update_post_meta($package->post_id, 'package_list_shipping_id', $shipment_id);
                        update_post_meta($package->post_id, 'package_list_status', 'Schedule To Ship');
                    }

                }
                $action_success = 'Successfully save the package you can now click Ship selected to continue';

                $_POST = array();

                //sending email to warehouse manager
                $args = array(
                    'role' => 'warehouse_manager',
                    'orderby' => 'ID',
                    'order' => 'ASC'
                );
                $blogusers = get_users($args);
                $user = get_user_by('ID', $current_user_id);
                $user_email = $user->user_email;
                foreach ($blogusers as $user) {

                    $to = $user->user_email;
                    $body = 'Hi Good day <br/>';
                    $body .= 'There is a suggestion of box checkout by : ' . $user_email . ' <br/> Please login your consolidation admin details <br/>  Click this link to view the package <a href="http://team661.com/consolidators/wp-admin/admin.php?page=schedulelist&user="' . get_current_user_id() . '"> Click here</a>';
                    $headers = array('Content-Type: text/html; charset=UTF-8');
                    $subject = "Suggestion for box order by " . $user_email;
                    wp_mail($to, $subject, $body, $headers);
                }

                wp_redirect(get_permalink($page->ID) . '?success=schedule_package');
            }else{

                wp_redirect(get_permalink($page->ID) . '?errors=date_invalid_data');
            }


        }




    }
    public function reset_schedule(){
        global $wpdb;
        if (isset( $_POST['action']) && 'POST' == $_SERVER['REQUEST_METHOD'] && !empty( $_POST['action'] ) && $_POST['action'] == 'reset-schedule-user' ) {

            global $wpdb;
            $id = get_current_user_id();
            $shipment_id = get_user_meta($id,'shipment_schedule_id',true);
            //update
            $packages = $wpdb->get_results('SELECT post_id FROM '.$wpdb->prefix.'postmeta WHERE meta_key="package_list_user_id" AND meta_value="'.$id.'"  ORDER BY  meta_id DESC' );
            $charges = 0;
            foreach ($packages as $package){
                if(get_post_meta($package->post_id,'package_list_status',true) == 'Schedule To Ship') {
                    update_post_meta($package->post_id, 'package_list_status', 'Ready To Ship');
                    delete_post_meta($package->post_id, 'package_list_shipping_id');
                }
            }

            //     $wpdb->update( 'wp_3_packages',array('shipment_id' => '' , 'status' => 'Ready To Ship'), array( 'user_id' => $id, 'status' => 'Schedule To Ship' ));

            $wpdb->delete( $wpdb->prefix.'shipment_schedule', array( 'id' => $shipment_id ) );
            delete_user_meta($id,'shipment_schedule_id');

            $page = get_page_by_title('packages');
            wp_redirect(get_permalink($page->ID) . '?success=delete_schedule');

        }
    }


  public function user_package_list() {


    }
    public function check_default_address($status2)
    {
        global $wpdb;
        $user_id = get_current_user_id();
        $address = $wpdb->get_results('SELECT  * FROM ' . $wpdb->prefix . 'user_address WHERE user_id = ' . $user_id . ' AND default_address = 1');
        $meta_value = get_current_user_id();
        $packages = $wpdb->get_results('SELECT post_id FROM '.$wpdb->prefix.'postmeta WHERE meta_key="package_list_user_id" AND meta_value="'.$meta_value.'" ' );

        if (!empty($address)) {
            echo '<table class="table align-items-center table-flush">
                        <thead class="thead-light">
                        <tr>
                            <th scope="col"></th>
                            <th scope="col">Date Received</th>
                            <th scope="col">Warehouse</th>

                            <th scope="col">Merchant</th>
                            <th scope="col">Value</th>
                            <th scope="col">Weight</th>
                            <th scope="col">Delivery Address</th>
                            <th scope="col">Status</th>
                            <th scope="col">Modify</th>
                        </tr>
                        </thead>
                        <tbody id="">';
            foreach ($packages as $package){
                $datetime_format = get_post_meta( $package->post_id, 'package_list_datetime', true );
                $status = get_post_meta($package->post_id,'package_list_status',true);
                $warehouse = get_post_meta($package->post_id,'package_list_warehouse',true);
                $merchant = get_post_meta($package->post_id,'package_list_merchant_name',true);
                $type = get_post_meta($package->post_id,'package_list_type',true);

                $datetime = date("F j, Y, g:i a",$datetime_format);
                if($status == "On Box"){

                }else if($type == "Consolidator"){
                    echo '<tr style="background-color:' . $this->package_color($status) . '">';
                    echo '<td></td>';
                    echo '<td>' . $datetime . '</td>';
                    echo '<td>' . $warehouse . '</td>';
                    echo '<td>' . $merchant . '</td>';
                    echo '<td>' . $datetime . '</td>';
                    echo '<td>' . $datetime . '</td>';
                    echo '<td>' . $status . '</td>';
                    echo '<td>' . $status . '</td>';
                    echo '<td> <a href="../view-package/?packages=' . $package->post_id. '" class="btn btn-icon btn-primary" type="button"><span class="btn-inner--icon"><i class="ni ni-settings-gear-65"></i></span></a></td>';

                    echo '</tr>';
                }
            }
             echo   '</tbody></table>';
            return;
        }else{
            echo '  <div class="row" style="margin:20px">
                            <div class="alert alert-warning" role="alert">
                                <strong>Warning!</strong> You need to have a default address in order to proceed on package list.
                                                            click <a href="#">Click here </a> to add an new default address.
                            </div>
                         </div>';
        }

    }

    public function user_package_table(){

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

    public function view_package_more_detailed($post_id){

        if(get_post_meta( $post_id, 'package_list_user_id', 1 ) != get_current_user_id()){
            echo 'You are not allowed to view to package';
            return;
            exit;
        }
       $title =  get_the_title( $post_id );
        $qty =  get_post_meta( $post_id, 'package_list_quantity', true );
        $status =  get_post_meta( $post_id, 'package_list_status', true );
        $warehouse =  get_post_meta( $post_id, 'package_list_warehouse', true );
        $order_id =  get_post_meta( $post_id, 'package_list_merchane_order_id', true );
        $merchant_name =  get_post_meta( $post_id, 'package_list_merchant_name', true );
        $reduction = (  get_post_meta( $post_id, 'package_list_reduction', 1 )) ? "YES" : "NO";
        $enclosure = (get_post_meta( $post_id, 'package_list_enclosure', 1 )) ? "YES" : "NO";
        $files = get_post_meta( $post_id, 'package_list_images', 1 );
        $datetime_format = get_post_meta( $post_id, 'package_list_datetime', true );
            $datetime = date("F j, Y, g:i a",$datetime_format);
        $height = get_post_meta( $post_id, 'package_list_height', 1 );
        $width = get_post_meta( $post_id, 'package_list_width', 1 );
        $lenght = get_post_meta( $post_id, 'package_list_lenght', 1 );
            $cost= get_post_meta( $post_id, 'package_list_cost', 1 );



       echo ' <div class="wrapper row">
                                    <div class="preview col-md-6">
            
                                        <div class="preview-pic tab-content">
                                        ';
       $i = 0;
        foreach ( $files as $attachment_id => $img_full_url ) {
            if($i == 0)
            echo '<div class="tab-pane active" id="pic-'.$i.'">'.wp_get_attachment_link($attachment_id, 'medium',false).'</div> ';
            else
                echo '<div class="tab-pane" id="pic-'.$i.'">'.wp_get_attachment_link($attachment_id, 'medium').'</div> ';
        }
            echo '</div>
                    </div>
                    <div class="details col-md-6">
                        <h3 class="product-title">'.$title.'<small> x '.$qty.'</small></h3>
                        <table class="table table-striped">
                        <tr>
                        <th>Recieved Date</th> <td>'.$datetime.' PST</td>
                        </tr><tr>
                        <th>Warehouse</th> <td>'.$warehouse.'</td>
                        </tr><tr>
                        <th>Status</th> <td><button type="button" class="btn btn-sm" style="background-color:'.$this->package_color($status).'" > '.$status.' </button></td>
                        </tr> <tr>
                        <th>Order id</th> <td>'.$order_id.'</td>
                        </tr><tr>
                         <th>Merchant name</th> <td>'.$merchant_name.'</td>
                        </tr><tr>
                         <th>Reduction </th> <td>'.$reduction.'</td>
                        </tr><tr>
                         <th>Enclosure</th> <td>'.$enclosure.'</td>
                        </tr><tr>
                        <th>Quantity</th> <td>'.$qty.'</td>
                        </tr><tr>
                        <th>Package Price </th> <td>$'.$cost.'</td>
                        </tr><tr>
                        <th>Sizes (HxLxW ) (cm)</th> <td>  
        
                            <span class="color orange" style="padding:5px;color:white">'.$height.'</span>
                            <span class="color green" style="padding:5px;color:white">'.$lenght.'</span>
                            <span class="color blue" style="padding:5px;color:white">'.$width.'</span>
                        </tr><tr>
                        </table>';
        if($status == "Pick up" || $status == "Ready To Ship") {
            echo '
                        <form action="POST">
                                <div class="form-control">
                                  <label for=""> Package cost $</label> &nbsp;
                                <input type="text" class="from-control" name="unit_cost" value="' . $cost . '" />
                                </div>';

            echo '    
                    
                       <hr/>
                        <div class="action">
                            <input type="hidden" name="status" value="' . $status . '" />
                            <input type="hidden" name="update-address-user" />
                            <button class="add-to-cart btn btn-default" type="submit">Update</button>
                     
                            <button class="add-to-cart btn btn-default" type="button">Discard</button>
                        </div>
                        </form>';
        }
                    echo '
                    </div>
                </div>';


    }

    public function pakage_user_badge($type){
        global $wpdb;
        $meta_value = get_current_user_id();
        $packages = $wpdb->get_results('SELECT post_id FROM '.$wpdb->prefix.'postmeta WHERE meta_key="package_list_user_id" AND meta_value="'.$meta_value.'" ' );
        $count = 0;

        foreach ($packages as $package) {
            $status = get_post_meta($package->post_id,'package_list_status',true);
            $user_id = get_post_meta($package->post_id,'package_list_user_id',true);
            $typedb = get_post_meta($package->post_id,'package_list_type',true);

            if($status != 'On Box' && $user_id == get_current_user_id() && $typedb == $type)
                $count++;
        }

        if($count != 0)
        echo ' <span class="badge " style="background-color:red;color:white">'.$count.'</span>';
    }
    public function box_user_badge($type){
        global $wpdb;
        $meta_value = get_current_user_id();
        $packages = $wpdb->get_results('SELECT * FROM '.$wpdb->prefix.'shipment_schedule WHERE user_id ='.$meta_value.' ORDER BY  id DESC ' );
        $count = 0;

        foreach ($packages as $package) {

            $packagesData = $wpdb->get_results('SELECT post_id FROM '.$wpdb->prefix.'postmeta WHERE meta_key="package_list_shipping_id" AND meta_value="'.$package->id.'" ' );
            $user_id = get_post_meta($packagesData[0]->post_id,'package_list_type',true);
            if($package->order_id != "" &&  $user_id == $type ) {

                    $count++;
            }
        }

        if($count != 0)
            echo ' <span class="badge " style="background-color:red;color:white">'.$count.'</span>';
    }

    public function package_button_data(){
        global $wpdb;
        $user_id = get_current_user_id();
        $packages = $wpdb->get_results('SELECT post_id FROM '.$wpdb->prefix.'postmeta WHERE meta_key="package_list_user_id" AND meta_value="'.$user_id.'"  ' );
        $countpakage = count($packages);
        $i = 0;
        $j = 0;
        $k = 0;
        $l == 0;
        foreach ($packages as $package){
            if(get_post_meta( $package->post_id, 'package_list_status', true ) == "Pick up" ){$i++;}
            if(get_post_meta( $package->post_id, 'package_list_status', true ) == "Ready To Ship"){$j++;}
            if(get_post_meta( $package->post_id, 'package_list_status', true ) == "On Box"){$k++;}
            if(get_post_meta( $package->post_id, 'package_list_status', true ) == "Schedule To Ship"){$l++;}

        }
        if(!empty($packages)){
       // echo $i .' '.$j;
            if($i == 0 && $j != 0){
                echo '<button type="button" class="btn btn-outline-warning" data-toggle="modal" data-target="#shipment_modal">
                Ship the packages
                </button>';
            }elseif($i == 0 ) {
                
                echo '
                <a class="btn btn-primary"   href="../purchase">Proceed to purchase</a>
                <button type="button"  class="btn btn-outline-success" data-toggle="modal" data-target="#reset_shipment_valid" >
                Reset Shipment Schedule
                </button>';
            }
        }

    }

    public function package_add_cost(){

    }

}