<div class="wrap">
    <h1><?php _e( 'Edit Package', 'webdevs' ); ?></h1>

    <?php $item = package_get_package( $id ); ?>


    <form action="" method="post">
        <?php     if(isset($_GET['user'])){?>

            <input type="hidden" name="user_id" value="<?=$_GET['user']?>" />
        <?php }?>

        <table class="form-table">
            <tbody>
                <tr class="row-warehouse-id">
                    <th scope="row">
                        <label for="warehouse_id"><?php _e( 'Warehouse', 'webdevs' ); ?></label>
                    </th>
                    <td>
                        
					<select class="regular-text" name="warehouse_id"  required="required">
				
                                    <option value="1">California</option>
                        
                        </select>
                   </td>
                </tr>
                <tr class="row-service-type">
                    <th scope="row">
                        <label for="service_type"><?php _e( 'Service type', 'webdevs' ); ?></label>
                    </th>
                    <td>
                        <input type="text" name="service_type" id="service_type" class="regular-text" placeholder="<?php echo esc_attr( '', 'webdevs' ); ?>" value="<?php echo esc_attr( $item->service_type ); ?>" required="required" />
                    </td>
                </tr>
                <tr class="row-status">
                    <th scope="row">
                        <label for="status"><?php _e( 'Status', 'webdevs' ); ?></label>
                    </th>
                    <td>
                        <select class="regular-text" name="status"  required="required">
                            <option value="<?php echo esc_attr( $item->status ); ?>" ><?php echo esc_attr( $item->status ); ?></option>
                            <option value="On Hold"  >On hold</option>
                            <option value="Ready To Ship" >Ready To Ship</option>
                            <option value="Schedule To Ship" >Schedule To Ship</option>
                        </select>
                    </td>
                </tr>
                <tr class="row-merchant-order">
                    <th scope="row">
                        <label for="merchant_order"><?php _e( 'Merchant Order', 'webdevs' ); ?></label>
                    </th>
                    <td>
                        <input type="text" name="merchant_order" id="merchant_order" class="regular-text" placeholder="<?php echo esc_attr( '', 'webdevs' ); ?>" value="<?php echo esc_attr( $item->merchant_order ); ?>" required="required" />
                    </td>
                </tr>
                <tr class="row-reduction">
                    <th scope="row">
                        <label for="reduction"><?php _e( 'Reduction', 'webdevs' ); ?></label>
                    </th>
                    <td>
                        <input type="text" name="reduction" id="reduction" class="regular-text" placeholder="<?php echo esc_attr( '', 'webdevs' ); ?>" value="<?php echo esc_attr( $item->reduction ); ?>" />
                    </td>
                </tr>
                <tr class="row-enclosure">
                    <th scope="row">
                        <label for="enclosure"><?php _e( 'Enclosure', 'webdevs' ); ?></label>
                    </th>
                    <td>
                        <input type="text" name="enclosure" id="enclosure" class="regular-text" placeholder="<?php echo esc_attr( '', 'webdevs' ); ?>" value="<?php echo esc_attr( $item->enclosure ); ?>" />
                    </td>
                </tr>
                <tr class="row-qty">
                    <th scope="row">
                        <label for="qty"><?php _e( 'Quantity', 'webdevs' ); ?></label>
                    </th>
                    <td>
                        <input type="number" name="qty" id="qty" class="regular-text" placeholder="<?php echo esc_attr( '', 'webdevs' ); ?>" value="<?php echo esc_attr( $item->qty ); ?>" required="required" />
                    </td>
                </tr>
                <tr class="row-description">
                    <th scope="row">
                        <label for="description"><?php _e( 'Description', 'webdevs' ); ?></label>
                    </th>
                    <td>
                        <input type="text" name="description" id="description" class="regular-text" placeholder="<?php echo esc_attr( '', 'webdevs' ); ?>" value="<?php echo esc_attr( $item->description ); ?>" required="required" />
                    </td>
                </tr>
                <tr class="row-classification">
                    <th scope="row">
                        <label for="classification"><?php _e( 'Classification', 'webdevs' ); ?></label>
                    </th>
                    <td>
                        <input type="text" name="classification" id="classification" class="regular-text" placeholder="<?php echo esc_attr( '', 'webdevs' ); ?>" value="<?php echo esc_attr( $item->classification ); ?>" />
                    </td>
                </tr>


                <tr class="row-resized-dimention-lenght">
                    <th scope="row">
                        <label for="description"><?php _e( 'lenght  (CM)', 'webdevs' ); ?></label>
                    </th>
                    <td>
                        <input type="number" name="resized_dimention_lenght" id="resized_dimention_lenght" class="regular-text" placeholder="<?php echo esc_attr( '', 'webdevs' ); ?>" value="<?php echo esc_attr( $item->resized_dimention_lenght ); ?>" required="required" />
                    </td>
                </tr>
                <tr class="row-resized-dimention-height">
                    <th scope="row">
                        <label for="classification"><?php _e( 'height  (CM)', 'webdevs' ); ?></label>
                    </th>
                    <td>
                        <input type="number" name="resized_dimention_height" id="resized_dimention_height" class="regular-text" placeholder="<?php echo esc_attr( '', 'webdevs' ); ?>" value="<?php echo esc_attr( $item->resized_dimention_height ); ?>" />
                    </td>
                </tr>
                <tr class="row-resized-dimention-weight">
                    <th scope="row">
                        <label for="unit_cost"><?php _e( 'width (CM)', 'webdevs' ); ?></label>
                    </th>
                    <td>
                        <input type="number" name="resized_dimention_width" id="resized_dimention_width" class="regular-text" placeholder="<?php echo esc_attr( '', 'webdevs' ); ?>" value="<?php echo esc_attr( $item->resized_dimention_width ); ?>" />
                    </td>
                </tr>
                <tr class="row-date-received">
                    <th scope="row">
                        <label for="date_received"><?php _e( 'weight (KG)', 'webdevs' ); ?></label>
                    </th>
                    <td>

                        <input type="number" id="number" name="resized_dimention_weight" id="resized_dimention_weight" class="regular-text" placeholder="cm" value="<?php echo esc_attr( $item->resized_dimention_weight ); ?>" />
                    </td>
                </tr>

                <tr class="row-date-received">
                    <th scope="row">
                        <label for="date_received"><?php _e( 'Date Received', 'webdevs' ); ?></label>
                    </th>
                    <td>
                    <input type="date" id="datepicker" name="date_received" id="date_received" class="example-datepicker" value="<?php echo esc_attr( $item->date_received ); ?>" />
                    </td>
                </tr>
             </tbody>
        </table>

        <input type="hidden" name="field_id" value="<?php echo $item->id; ?>">

        <?php wp_nonce_field( 'package-new' ); ?>
        <?php submit_button( __( 'Edit Package', 'webdevs' ), 'primary', 'submit_package' ); ?>

    </form>
</div>