<link href="https://fonts.googleapis.com/css2?family=Sriracha&display=swap" rel="stylesheet">
<?php $imgurl= get_stylesheet_directory_uri().'/assets/img/icons'; ?>
<div class="californila-row">
    <div class="californila-column">
        <div class="californila-card">
            <img src="<?=$imgurl.'/icons_ship.png' ?>" height="50px" alt="">
            <h3 class="californila-h3"><?php  apply_filters('shipment_data_count_' ,'on-hold'); ?></h3>
            <strong>Processing</strong>
         

        </div>
    </div>

    <div class="californila-column">
        <div class="californila-card">
            <img src="<?=$imgurl.'/icons_processing.png' ?>" height="50px" alt="">
            <h3 class="californila-h3"><?php echo apply_filters('shipment_data_count_' ,'processing'); ?></h3>
            <strong>Ship</strong>

        </div>
    </div>

    <div class="californila-column">
        <div class="californila-card">
            <img src="<?=$imgurl.'/icons_transit.png' ?>" height="50px" alt="">
            <h3 class="californila-h3"><?php echo apply_filters('shipment_data_count_' ,'transit'); ?></h3>
            <strong>Transit</strong>

        </div>
    </div>
    <div class="californila-column">
        <div class="californila-card">
            <img src="<?=$imgurl.'/icons_out for delivery.png' ?>" height="50px" alt="">
            <h3 class="californila-h3"><?php echo apply_filters('shipment_data_count_' ,'received-manila'); ?></h3>
            <strong>Receive from manila</strong>

        </div>
    </div>

    <div class="californila-column">
        <div class="californila-card">
            <img src="<?=$imgurl.'/icons_receive from manila.png' ?>" height="50px" alt="">
            <h3 class="californila-h3"><?php echo apply_filters('shipment_data_count_' ,'delivery'); ?></h3>
            <strong>Out for Delivery</strong>

        </div>
    </div>
    <div class="californila-column">
        <div class="californila-card">
            <img src="<?=$imgurl.'/icons_delivered.png' ?>" height="50px" alt="">
            <h3 class="californila-h3"><?php echo apply_filters('shipment_data_count_' ,'completed'); ?></h3>
            <strong>Delivered</strong>

        </div>
    </div>



</div>

<div class="wrap">
    <h2><?php _e( 'Pending Boxes', ' webdevs' ); ?> </h2>

    <form method="post">
        <input type="hidden" name="page" value="ttest_list_table">

        <?php
        $list_table = new schedule_list_table();
        $list_table->prepare_items('pending');
       // $list_table->search_box( 'search', 'search_id' );
        $list_table->display();
        ?>
    </form>
</div>
<div class="wrap">
    <h2><?php _e( 'Boxes To Pay', ' webdevs2' ); ?> </h2>

    <form method="post">
        <input type="hidden" name="page" value="ttest_list_table">

        <?php
        $list_table2 = new schedule_list_table();
        $list_table2->prepare_items('pending2');
        // $list_table->search_box( 'search', 'search_id' );
        $list_table2->display();
        ?>
    </form>
</div>