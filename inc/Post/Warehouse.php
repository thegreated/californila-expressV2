<?php
/**
 * @package  AlecadddPlugin
 */
namespace Inc\Post;

use Inc\Api\SettingsApi;
use Inc\Base\BaseController;

/**
 *
 */
class Warehouse extends BaseController
{
    public $settings;

    private $dataName = 'Warehiuse';
    private $dbName = 'warehouse';
    private $address = 'warehouseList';

    public $labelData = array();

    public $labels = array();

    public function register()
    {
        $this->settings = new SettingsApi();
        $this->setLabels();
        $this->setLabelsData();
        // var_dump($this->labelData);
        $this->settings->setlabelCptName($this->dbName)->setlabelData($this->labelData)->register();
        add_action('admin_menu',array($this, 'my_admin_menu'));


    }
    function my_admin_menu() {
       add_submenu_page('californila_plugin', 'Warehouse', 'Warehouse', 'manage_options', 'edit.php?post_type=warehouse');
    }


    public function setLabels()
    {

        $this->labels =array(
            'name'                  => _x( $this->dataName, 'Post type general name', $this->address ),
            'singular_name'         => _x( 5 ,'Post type singular name',  $this->address  ),
            'menu_name'             => _x( 'Californila - Address', 'Admin Menu text',  $this->address  ),
            'name_admin_bar'        => _x( $this->dataName, 'Add New on Toolbar',  $this->address ),
            'add_new'               => __( 'Add New', $this->address  ),
            'add_new_item'          => __( 'Add New '.$this->dataName,  $this->address ),
            'new_item'              => __( 'New '.$this->dataName, $this->address  ),
            'edit_item'             => __( 'Edit '.$this->dataName, $this->address  ),
            'view_item'             => __( 'View '.$this->dataName, $this->address  ),
            'all_items'             => __( 'All '.$this->dataName, $this->address  ),
            'search_items'          => __( 'Search '.$this->dataName, $this->address  ),
            'parent_item_colon'     => __( 'Parent '.$this->dataName.':', $this->address  ),
            'not_found'             => __( 'No '.$this->dataName.' found.',  $this->address  ),
            'not_found_in_trash'    => __( 'No '.$this->dataName.' found in Trash.',  $this->address ),
            'featured_image'        => _x( $this->dataName.' Cover Image', 'Overrides the “Featured Image” phrase for this post type. Added in 4.3', $this->address  ),
            'set_featured_image'    => _x( 'Set cover image', 'Overrides the “Set featured image” phrase for this post type. Added in 4.3', $this->address  ),
            'remove_featured_image' => _x( 'Remove cover image', 'Overrides the “Remove featured image” phrase for this post type. Added in 4.3', $this->address  ),
            'use_featured_image'    => _x( 'Use as cover image', 'Overrides the “Use as featured image” phrase for this post type. Added in 4.3',  $this->address ),
            'archives'              => _x( $this->dataName.' archives', 'The post type archive label used in nav menus. Default “Post Archives”. Added in 4.4', $this->address  ),
            'insert_into_item'      => _x( 'Insert into '.$this->dataName, 'Overrides the “Insert into post”/”Insert into page” phrase (used when inserting media into a post). Added in 4.4',  $this->address  ),
            'uploaded_to_this_item' => _x( 'Uploaded to this '.$this->dataName, 'Overrides the “Uploaded to this post”/”Uploaded to this page” phrase (used when viewing media attached to a post). Added in 4.4', $this->address  ),
            'filter_items_list'     => _x( 'Filter '.$this->dataName.' list', 'Screen reader text for the filter links heading on the post type listing screen. Default “Filter posts list”/”Filter pages list”. Added in 4.4',  $this->address  ),
            'items_list_navigation' => _x( $this->dataName.' list navigation', 'Screen reader text for the pagination heading on the post type listing screen. Default “Posts list navigation”/”Pages list navigation”. Added in 4.4', $this->address ),

        );
    }
    public function setLabelsData()
    {
        $this->labelData  = array(
            'labels'             => $this->labels,
            'public'             => false,
            'show_ui'            => true,
            'show_in_menu'       => true,
            'rewrite'            => array( 'slug' => $this->dbName ),
            'hierarchical'       => true,
            'menu_position'      => null,
            'menu_icon'          => 'dashicons-exerpt-view',
            'supports'           => array( 'title', 'author' ),

        );
    }


}