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
class Package extends BaseController
{
	public $settings;

    private $dataName = 'Package';
    private $dbName = 'package';
    private $address = 'packagelist';

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
        add_action( 'save_post', array($this,'add_package_email'), 10, 3 );


    }
    public function my_admin_menu() {
        add_submenu_page('californila_plugin', 'Package', 'Package', 'manage_categories', 'edit.php?post_type=package');
    }



   public  function add_package_email( $post_id, $post, $update ) {

        if ($post->post_type  != 'package'){
            return;
        }
        if( ! $update && $post->post_status == "auto-draft" ) {
            return;
        }else if ( ! $update ) {
             return;
        }

        $qty =  get_post_meta( $post_id, 'package_list_quantity', true );
        $user_id =  get_post_meta( $post_id, 'package_list_user_id', true );
        $files = get_post_meta( $post_id, 'package_list_images', 1 );
        $datetime_format = get_post_meta( $post_id, 'package_list_datetime', true );
        $datetime = date("F j, Y, g:i a",$datetime_format);
        $title = $artist_title = get_the_title( $post_id );;
        $img_size = "medium";
        $user = get_user_by( 'ID',$user_id);
        $to = $user->user_email;
        $name_user = $user->first_name.' '.$user->last_name;
        $greetings = '<p>Your package list has been updated. Date we received this package is ' . $datetime . ' </p>  <p>The details of package updated is shown on table below::</p><hr/>';

        $subject = "Your item ".$title." has been arrive on US warehouse";
        $body = '<h4>Hi Good day '.$name_user.'</h4> ';
        $body .= ''.$greetings.'';
        $body .= '<table style="  border: 1px solid black;" ><thead><tr><th style=" padding: 15px;   border: 1px solid black;">Image</th><th style=" padding: 15px;   border: 1px solid black;">Description</th><th style=" padding: 15px; border: 1px solid black;">Quantity</th></tr></thead><tbody> <tr><td style=" padding: 15px; border: 1px solid black;" >';
        $body .= '<div class="file-list-image">';
        $body .= wp_get_attachment_image(  key($files), $img_size );
        $body .='</div>';
        $body .= '</td><td style=" padding: 15px; border: 1px solid black;">'.$title.'</td> <td style=" padding: 15px; border: 1px solid black;"`>'.$qty.' </td> </tr> </tbody> </table>';
        $headers = array('Content-Type: text/html; charset=UTF-8');

        wp_mail( $to, $subject, $body, $headers );
    }
	public function setLabels() 
	{
        
		$this->labels =array(
            'name'                  => _x( $this->dataName, 'Post type general name', $this->address ),
            'singular_name'         => _x( 5 ,'Post type singular name',  $this->address  ),
            'menu_name'             => _x( 'Californila - Address', 'Admin Menu text',  $this->address  ),
            'name_admin_bar'        => _x( $this->dataName, 'Add New on Toolbar',  $this->address ),
            'add_new'               => __( 'Add New ', $this->address  ),
            'add_new_item'          => __( 'Add New ' .$this->dataName,  $this->address ),
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
            'show_in_menu'       => false,
            'rewrite'            => array( 'slug' => $this->dbName ),
            'hierarchical'       => true,
            'menu_position'      => null,
            'menu_icon'          => 'dashicons-exerpt-view',
            'supports'           => array( 'title', 'author' ),
  
        );
    }




}