<?php

if ( ! class_exists ( 'WP_List_Table' ) ) {
    require_once ABSPATH . 'wp-admin/includes/class-wp-list-table.php';
}

/**
 * List table class
 */
class schedule_list_table extends \WP_List_Table {

    function __construct() {
        parent::__construct( array(
            'singular' => 'schedule',
            'plural'   => 'schedule',
            'ajax'     => false
        ) );
    }

    function get_table_classes() {
        return array( 'widefat', 'fixed', 'striped', $this->_args['plural'] );
    }

    /**
     * Message to show if no designation found
     *
     * @return void
     */
    function no_items() {
        _e( 'No schedule found!', ' webdevs' );
    }

    /**
     * Default column values if no callback found
     *
     * @param  object  $item
     * @param  string  $column_name
     *
     * @return string
     */
    function column_default( $item, $column_name ) {
        global $wpdb;
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

        $shipment_id = $items = $wpdb->get_results( 'SELECT post_id FROM ' . $wpdb->prefix . 'postmeta WHERE meta_key= "'.package_list_shipping_id.'" AND meta_value="'.$item->id.'"');
        $number_of_package =  count($shipment_id);
       // echo $shipment_id;
        switch ( $column_name ) {
            case 'user_id':
                $user = get_user_by( 'id',$item->user_id );
                $user = $user->user_firstname .' '.$user->user_lastname;
                return $user;

            case 'date_created':
                return $item->date_created;

            case 'date_shipped':
                return $item->date_shipped;
            case 'number_package':
                return '<a  href="#">'.$number_of_package.' package</a>';
            case 'type':
                return $item->type;
            case 'tracking_code':
                return $item->tracking_code;
            case 'product_suggestion':
                $data= '';
                if($item->product_suggestion != '') {
                    $order = wc_get_product($item->product_suggestion);
                    $data .= $order->get_title();
                }

                return $data;
            case 'space' :
                echo ' <a href="admin.php?page=shipment&action=view&id='.$item->id.'"><span style="color: #fff; background-color: #339CFF;font-size: 66%;
                    font-weight: 600;
                    line-height: 1;
                    display: inline;
                    left
                    padding: .35rem .375rem;
                    transition: color .15s ease-in-out,background-color .15s ease-in-out,border-color .15s ease-in-out,box-shadow .15s ease-in-out;
                    text-align: center;
                    vertical-align: baseline;
                    white-space: nowrap;
                    border-radius: .375rem;padding:10px;" >  VIEW </span> </a>  ';
                break;
            case 'modify' :
                if($item->product_suggestion == '') {

                    return '
                  
                    
                    <span style="color: #f80031; background-color: #fdd1da;font-size: 66%;
                    font-weight: 600;
                    line-height: 1;
                    display: inline;
                    left
                    padding: .35rem .375rem;
                    transition: color .15s ease-in-out,background-color .15s ease-in-out,border-color .15s ease-in-out,box-shadow .15s ease-in-out;
                    text-align: center;
                    vertical-align: baseline;
                    white-space: nowrap;
                    border-radius: .375rem;padding:10px;" >  PENDING </span> 
                    ';
                    }
                break;

            default:
                return isset( $item->$column_name ) ? $item->$column_name : '';
        }
    }

    /**
     * Get the column names
     *
     * @return array
     */
    function get_columns() {
        $columns = array(
            'cb'           => '<input type="checkbox" />',
            'user_id'      =>  __( 'Name', ' webdevs' ),
            'date_created'      => __( 'Date Request', ' webdevs' ),
            'date_shipped'      => __( 'Date Ship', ' webdevs' ),
            'number_package'      => __( '# Package', ' webdevs' ),
            'type'      => __( 'Box type', ' webdevs' ),
            'tracking_code'      => __( 'Tracking Code', ' webdevs' ),
            'product_suggestion'      => __( 'Suggestion', ' webdevs' ),
            'space'      => __( '', ' webdevs' ),
            'modify'      => __( '', ' webdevs' ),

        );

        return $columns;
    }

    /**
     * Render the designation name column
     *
     * @param  object  $item
     *
     * @return string
     */
    function column_date_shipped( $item ) {

        $actions           = array();
       // $actions['edit']   = sprintf( '<a href="%s" data-id="%d" title="%s">%s</a>', admin_url( 'admin.php?page=schedule&action=edit&id=' . $item->id ), $item->id, __( 'Edit this item', ' webdevs' ), __( 'Edit', ' webdevs' ) );
        //$actions['delete'] = sprintf( '<a href="%s" class="submitdelete" data-id="%d" title="%s">%s</a>', admin_url( 'admin.php?page=schedule&action=delete&id=' . $item->id ), $item->id, __( 'Delete this item', ' webdevs' ), __( 'Delete', ' webdevs' ) );

        return sprintf( '<a href="%1$s"><strong>%2$s</strong></a> %3$s', admin_url( 'admin.php?page=shipment&action=view&id=' . $item->id ), $item->date_shipped, $this->row_actions( $actions ) );
    }

    /**
     * Get sortable columns
     *
     * @return array
     */
    function get_sortable_columns() {
        $sortable_columns = array(
            'name' => array( 'name', true ),
        );

        return $sortable_columns;
    }

    /**
     * Set the bulk actions
     *
     * @return array
     */
    function get_bulk_actions() {
        $actions = array(
            'trash'  => __( 'Move to Trash', ' webdevs' ),
        );
        return $actions;
    }

    /**
     * Render the checkbox column
     *
     * @param  object  $item
     *
     * @return string
     */
    function column_cb( $item ) {
        return sprintf(
            '<input type="checkbox" name="schedule_id[]" value="%d" />', $item->id
        );
    }

    /**
     * Set the views
     *
     * @return array
     */
    public function get_views_() {
        $status_links   = array();
        $base_link      = admin_url( 'admin.php?page=sample-page' );

        foreach ($this->counts as $key => $value) {
            $class = ( $key == $this->page_status ) ? 'current' : 'status-' . $key;
            $status_links[ $key ] = sprintf( '<a href="%s" class="%s">%s <span class="count">(%s)</span></a>', add_query_arg( array( 'status' => $key ), $base_link ), $class, $value['label'], $value['count'] );
        }

        return $status_links;
    }

    /**
     * Prepare the class items
     *
     * @return void
     */
    function prepare_items() {

        $columns               = $this->get_columns();
        $hidden                = array( );
        $sortable              = $this->get_sortable_columns();
        $this->_column_headers = array( $columns, $hidden, $sortable );

        $per_page              = 20;
        $current_page          = $this->get_pagenum();
        $offset                = ( $current_page -1 ) * $per_page;
        $this->page_status     = isset( $_GET['status'] ) ? sanitize_text_field( $_GET['status'] ) : '2';

        // only ncessary because we have sample data
        $args = array(
            'offset' => $offset,
            'number' => $per_page,
        );

        if ( isset( $_REQUEST['orderby'] ) && isset( $_REQUEST['order'] ) ) {
            $args['orderby'] = $_REQUEST['orderby'];
            $args['order']   = $_REQUEST['order'] ;
        }
        if($status == "pending") {

            $this->items = wd_get_all_schedule($args);

            $this->set_pagination_args( array(
                'total_items' => wd_get_schedule_count(),
                'per_page'    => $per_page
            ) );
        }else{

            $this->items = wd_get_all_schedule_boxes($args);
            $this->set_pagination_args( array(
                'total_items' => wd_get_schedule_count_boxes(),
                'per_page'    => $per_page
            ) );


        }


    }


}