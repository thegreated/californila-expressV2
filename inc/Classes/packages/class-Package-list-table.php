<?php

if ( ! class_exists ( 'WP_List_Table' ) ) {
    require_once ABSPATH . 'wp-admin/includes/class-wp-list-table.php';
}

/**
 * List table class
 */
class package_list_table extends \WP_List_Table {

    function __construct() {
        parent::__construct( array(
            'singular' => 'Package',
            'plural'   => 'Packages',
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
        _e( 'No Packages Found!', 'webdevs' );
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

        switch ( $column_name ) {
            case 'warehouse_id':
                return $item->warehouse_id;

            case 'address_id':
                return $item->address_id;

            case 'user_id':
                return '<a href="admin.php?page=packagelist&user='.$item->user_id.'">'.$item->user_id.'</a>';

            case 'service_type':
                return $item->service_type;

            case 'status':
                return $item->status;

            case 'merchant_order':
                return $item->merchant_order;

            case 'reduction':
                return $item->reduction;

            case 'enclosure':
                return $item->enclosure;

            case 'qty':
                return $item->qty;

            case 'description':
                return $item->description;

            case 'classification':
                return $item->classification;

            case 'unit_cost':
                return $item->unit_cost;

            case 'date_received':

                return $item->date_received;

            case 'resized_dimention_lenght':
                return $item->resized_dimention_lenght;

            case 'resized_dimention_height':
                return $item->resized_dimention_height;

            case 'resized_dimention_width':
                return $item->resized_dimention_width;

            case 'resized_dimention_weight':
                return $item->resized_dimention_weight;

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
            'warehouse_id'      => __( 'Warehouse', 'webdevs' ),
            'address_id'      => __( 'Address', 'webdevs' ),
            'user_id'      => __( 'User', 'webdevs' ),
            'service_type'      => __( 'Service type', 'webdevs' ),
            'status'      => __( 'Status', 'webdevs' ),
            'merchant_order'      => __( 'Merchat Order', 'webdevs' ),
            'reduction'      => __( 'Reduction', 'webdevs' ),
            'enclosure'      => __( 'Enclosure', 'webdevs' ),
            'qty'      => __( 'Qty', 'webdevs' ),
            'description'      => __( 'Description', 'webdevs' ),
            'classification'      => __( 'Classification', 'webdevs' ),
            'unit_cost'      => __( 'Unit cost', 'webdevs' ),
            'date_received'      => __( 'Date Received', 'webdevs' ),
            'resized_dimention_lenght'      => __( 'Lenght', 'webdevs' ),
            'resized_dimention_height'      => __( 'Height', 'webdevs' ),
            'resized_dimention_width'      => __( 'Width', 'webdevs' ),
            'resized_dimention_weight'      => __( 'Weight', 'webdevs' ),

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
    function column_warehouse_id( $item ) {

        $actions           = array();
        $actions['edit']   = sprintf( '<a href="%s" data-id="%d" title="%s">%s</a>', admin_url( 'admin.php?page=packagelist&action=edit&id=' . $item->id .'&user='.$_GET['user']), $item->id, __( 'Edit this item', 'webdevs' ), __( 'Edit', 'webdevs' ) );
        $actions['delete'] = sprintf( '<a href="%s" class="submitdelete" data-id="%d" title="%s">%s</a>', admin_url( 'admin.php?page=packagelist&action=delete&id=' . $item->id ), $item->id, __( 'Delete this item', 'webdevs' ), __( 'Delete', 'webdevs' ) );

        return sprintf( '<a href="%1$s"><strong>%2$s</strong></a> %3$s', admin_url( 'admin.php?page=packagelist&action=view&id=' . $item->id ), $item->warehouse_id, $this->row_actions( $actions ) );
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
            'trash'  => __( 'Move to Trash', 'webdevs' ),
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
            '<input type="checkbox" name="Package_id[]" value="%d" />', $item->id
        );
    }

    /**
     * Set the views
     *
     * @return array
     */
    public function get_views_() {
        $status_links   = array();
        $base_link      = admin_url( 'admin.php?page=packagelist' );

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

	
        $this->items  = package_get_all_Package( $args );

        $this->set_pagination_args( array(
            'total_items' => package_get_Package_count(),
            'per_page'    => $per_page
        ) );
    }
	
	 function prepare_items_user($id) {
		 
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
			'user_id' => $id

        );

        if ( isset( $_REQUEST['orderby'] ) && isset( $_REQUEST['order'] ) ) {
            $args['orderby'] = $_REQUEST['orderby'];
            $args['order']   = $_REQUEST['order'] ;
        }


        $this->items  = package_get_all_Package( $args );

        $this->set_pagination_args( array(
            'total_items' => package_get_Package_count(),
            'per_page'    => $per_page
        ) );
		 
	 }
	 
	 function get_user_data($id){
		
		
		$items = get_user_by( 'id', $id ); 

		
		return $items;
	 }
}