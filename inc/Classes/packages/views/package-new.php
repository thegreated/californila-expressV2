<div class="wrap">
    <h1><?php _e( 'Add Package', 'webdevs' ); ?></h1>

    <form action="" method="post" enctype='multipart/form-data'>
		<?php
		if(isset($_GET['user'])){?>
			
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
				
							<option value="California">California</option>
				
                   </select>
				   </td>
                </tr>
                <tr class="row-service-type">
                    <th scope="row">
                        <label for="service_type"><?php _e( 'Service type', 'webdevs' ); ?></label>
                    </th>
                    <td>
                        <!--<input type="text" name="service_type" id="service_type" class="regular-text" placeholder="<?php echo esc_attr( '', 'webdevs' ); ?>" value="" required="required" /> -->
                        <select class="regular-text" name="service_type"  required="required">
                            <option value="Consolidation"  >Consolidation</option>
                            <option value="Personal Shopper" >Personal Shopper</option>
                        </select>
                    </td>
                </tr>
                <tr class="row-status">
                    <th scope="row">
                        <label for="status"><?php _e( 'Status', 'webdevs' ); ?></label>
                    </th>
                    <td>
                        <select class="regular-text" name="status"  required="required">
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
                        <input type="text" name="merchant_order" id="merchant_order" class="regular-text" placeholder="<?php echo esc_attr( '', 'webdevs' ); ?>" value="" required="required" />
                    </td>
                </tr>
                <tr class="row-reduction">
                    <th scope="row">
                        <label for="reduction"><?php _e( 'Reduction', 'webdevs' ); ?></label>
                    </th>
                    <td>
                        <input type="text" name="reduction" id="reduction" class="regular-text" placeholder="<?php echo esc_attr( '', 'webdevs' ); ?>" value="" />
                    </td>
                </tr>
                <tr class="row-enclosure">
                    <th scope="row">
                        <label for="enclosure"><?php _e( 'Enclosure', 'webdevs' ); ?></label>
                    </th>
                    <td>
                        <input type="text" name="enclosure" id="enclosure" class="regular-text" placeholder="<?php echo esc_attr( '', 'webdevs' ); ?>" value="" />
                    </td>
                </tr>
                <tr class="row-qty">
                    <th scope="row">
                        <label for="qty"><?php _e( 'Quantity', 'webdevs' ); ?></label>
                    </th>
                    <td>
                        <input type="number" name="qty" id="qty" class="regular-text" placeholder="<?php echo esc_attr( '', 'webdevs' ); ?>" value="" required="required" />
                    </td>
                </tr>
                <tr class="row-description">
                    <th scope="row">
                        <label for="description"><?php _e( 'Description', 'webdevs' ); ?></label>
                    </th>
                    <td>
                        <input type="text" name="description" id="description" class="regular-text" placeholder="<?php echo esc_attr( '', 'webdevs' ); ?>" value="" required="required" />
                    </td>
                </tr>
                <tr class="row-classification">
                    <th scope="row">
                        <label for="classification"><?php _e( 'Classification', 'webdevs' ); ?></label>
                    </th>
                    <td>
                        <input type="text" name="classification" id="classification" class="regular-text" placeholder="<?php echo esc_attr( '', 'webdevs' ); ?>" value="" />
                    </td>
                </tr>

                <tr class="row-resized-dimention-lenght">
                    <th scope="row">
                        <label for="description"><?php _e( 'lenght', 'webdevs' ); ?></label>
                    </th>
                    <td>
                        <input type="number" name="resized_dimention_lenght" id="resized_dimention_lenght" class="regular-text" placeholder="<?php echo esc_attr( '', 'webdevs' ); ?>" value="" required="required" />
                    </td>
                </tr>
                <tr class="row-resized-dimention-height">
                    <th scope="row">
                        <label for="classification"><?php _e( 'height', 'webdevs' ); ?></label>
                    </th>
                    <td>
                        <input type="number" name="resized_dimention_height" id="resized_dimention_height" class="regular-text" placeholder="<?php echo esc_attr( '', 'webdevs' ); ?>" value="" />
                    </td>
                </tr>
                <tr class="row-resized-dimention-weight">
                    <th scope="row">
                        <label for="unit_cost"><?php _e( 'width', 'webdevs' ); ?></label>
                    </th>
                    <td>
                        <input type="number" name="resized_dimention_width" id="resized_dimention_width" class="regular-text" placeholder="<?php echo esc_attr( '', 'webdevs' ); ?>" value="" />
                    </td>
                </tr>
                <tr class="row-date-received">
                    <th scope="row">
                        <label for="date_received"><?php _e( 'weight', 'webdevs' ); ?></label>
                    </th>
                    <td>

                        <input type="number" id="number" name="resized_dimention_weight" id="resized_dimention_weight" class="regular-text" placeholder="<?php echo esc_attr( '', 'webdevs' ); ?>" value="" />
                    </td>
                </tr>

                <tr class="row-date-received">
                    <th scope="row">
                        <label for="date_received"><?php _e( 'Date Received', 'webdevs' ); ?></label>
                    </th>
                    <td>
								
                        <input type="date" id="datepicker" name="date_received" id="date_received" class="example-datepicker" value="<?php echo date('Y-m-d',strtotime(date('m/d/yy'))) ?>" />
                    </td>
                </tr>
                <tr>
                    <th scope="row">
                        <label for="images"><?php _e( 'Images', 'webdevs' ); ?></label>
                    </th>
                    <td>
                        <input type="file" name="my_file_upload[]" id="my_file_upload[]" multiple="multiple">
                    </td>

                </tr>

             
                <!--<tr class="row-unit-cost">
                    <th scope="row">
                        <label for="unit_cost"><?php _e( 'Unit Cost', 'webdevs' ); ?></label>
                    </th>
                    <td>
                        <input type="number" name="unit_cost" id="unit_cost" class="regular-text" placeholder="<?php echo esc_attr( '', 'webdevs' ); ?>" value="" />
                    </td>
                </tr>-->

            </tbody>
        </table>

        <input type="hidden" name="field_id" value="0">

        <?php wp_nonce_field( 'package-new' ); ?>
        <?php submit_button( __( 'Add package', 'webdevs' ), 'primary', 'submit_package' ); ?>

    </form>
</div>