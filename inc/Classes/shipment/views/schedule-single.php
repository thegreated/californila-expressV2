<style>
    .list-group-item {
        position: relative;
        display: block;
        padding: .75rem 1.25rem;
        margin-bottom: -1px;
        background-color: #fff;
        border: 1px solid rgba(0,0,0,.125);
    }
    tr,td,th {
        border:1px solid black;
        padding:20px;
    }
</style>
<?php
if (isset( $_POST['action']) && 'POST' == $_SERVER['REQUEST_METHOD'] && !empty( $_POST['action'] ) && $_POST['action'] == 'save_charges' ) {
    global $wpdb;
   $pakgeid =  $_POST['package_data'];
   if($pakgeid != ""){
       $product = $pakgeid;
       //  echo var_dump($shipment_data[1]);
       $wpdb->update( 'wp_3_shipment_schedule',array('product_suggestion' => $product), array( 'id' => $_GET['id'] ));
       $user = get_user_by( 'ID', $_POST['user_id']);
       $to = $user->user_email;
       $subject = 'Package box has been set by the warehouse manager';
       $body = 'Hi Good day <br/><br/>';
       $body .= 'Your Package has has been set by the warehouse manager. You can now pay the box to continue transaction <br/> <br/> <hr/><br/> ';
       $body .= 'Login and visit this link to continue. <a href="http://team661.com/consolidators/purchase/"> Click here</a>';
       $headers = array('Content-Type: text/html; charset=UTF-8');
       wp_mail( $to, $subject, $body, $headers );
   }else{

   }
}
global $wpdb;
$items = $wpdb->get_results( 'SELECT * FROM ' . $wpdb->prefix . 'shipment_schedule WHERE id='.$_GET['id'] );
$products_query = $wpdb->get_results( "
                                SELECT  p.ID AS id,
                                        p.post_title AS name,
                                        Max(CASE WHEN pm.meta_key = '_price' AND  p.ID = pm.post_id THEN pm.meta_value END) AS price
                                FROM    {$wpdb->prefix}posts p
                                        INNER JOIN {$wpdb->prefix}postmeta pm
                                            ON p.ID = pm.post_id
                                WHERE   p.post_type = 'product_variation'
                                        AND p.post_status = 'publish'
                                        AND p.post_parent != 0
                                        
                                GROUP BY p.ID
                                ORDER BY p.ID ASC;
                                
                            " );

?>
<div id="postbox-container-2" class="postbox-container">
    <div id="normal-sortables" class="meta-box-sortables ui-sortable">
        <div id="package_list_metabox" class="postbox  cmb2-postbox"  style="padding:20px;">
            <h2 class="hndle ui-sortable-handle"><span>Shipment Schedule Details</span></h2>
            <div class="inside" style="padding:20px;">
                <form method="POST">
                    <ul class="list-group">
                        <li class="list-group-item">Date Request: </li>
                        <li class="list-group-item"> <strong><?=$items[0]->date_shipped?></strong></li>
                        <li class="list-group-item">Date Shipped: </strong></li>
                        <li class="list-group-item"><strong><?=$items[0]->date_shipped?></strong></li>
                        <li class="list-group-item">Box Type: <small> Request by user</small> </li>
                        <li class="list-group-item"><strong><?=$items[0]->type?></strong> </li>
                        <li class="list-group-item">Tracking code <strong><?=$items[0]->tracking_code?></strong></li>
                        <li class="list-group-item">Suggestions </li>
                        <li class="list-group-item">
                            <?php
                            $data= '';
                            $data .=  '<select class="package_data_admin" id="package_data_admin" name="package_data">';
                            if($items[0]->product_suggestion == ''){

                                $data .=  '  <option value="">Select Package</option>';
                                foreach($products_query as $product){
                                    $data .=  '       <option value="'.$product->id.'">'.$product->name.'</option>';
                                }

                            }else{
                                $order = wc_get_product( $items[0]->product_suggestion );

                                $data .=  '<option value="">'.$order->get_title().'</option>';
                                foreach($products_query as $product){
                                    $data .=  '       <option value="'.$product->id.'">'.$product->name.'</option>';
                                }
                            }
                            $data .=  ' </select> ';
                            echo $data;
                            ?>

                        </li>
                    </ul>
                    <table >

                        <tr>
                            <th>Image</th><th>Description</th><th>Quantity</th>
                        </tr>
                        <?php $postData = $wpdb->get_results( 'SELECT * FROM ' . $wpdb->prefix . 'postmeta WHERE meta_value='.$_GET['id'] );
                        foreach($postData as $item){
                            $files = get_post_meta( $item->post_id, 'package_list_images', 1 );


                            ?>
                        <tr>
                        <td><?=wp_get_attachment_image(  key($files), "medium" ); ?>
                            <td><a href="post.php?post=<?=$item->post_id?>&action=edit"><?=get_the_title( $item->post_id );?></a></td>
                            <td><?php echo 'x'.get_post_meta($item->post_id,'package_list_quantity',true) ?></td>

                        </tr>
                        <?php } ?>
                    </table>
                    <hr>
                    <?php wp_nonce_field( 'save_charges' ) ?>
                    <input name="user_id" type="hidden" id="user_id"  value="<?=$items[0]->user_id?>"  />
                    <input name="action" type="hidden" id="action" value="save_charges" />
                    <input type="submit" id="search-submit" name="suggestion" class="button" value="Submit">
                </form>
          </div>
      </div>
    </div>

