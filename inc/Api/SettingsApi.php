<?php 
/**
 * @package  californila-express
 */
namespace Inc\Api;

class SettingsApi
{
	public $admin_pages = array();

	public $admin_subpages = array();

	public $settings = array();

	public $sections = array();

	public $fields = array();

	public function register()
	{
		if ( ! empty($this->admin_pages) ) {
			add_action( 'admin_menu', array( $this, 'addAdminMenu' ) );
		}

		if ( !empty($this->settings) ) {
			add_action( 'admin_init', array( $this, 'registerCustomFields' ) );
		}

		
		add_action( 'init', array( $this, 'registerCustomFields' ) );
	}

	public function addPages( array $pages )
	{
		$this->admin_pages = $pages;

		return $this;
	}

	public function withSubPage( string $title = null ) 
	{
		if ( empty($this->admin_pages) ) {
			return $this;
		}

		$admin_page = $this->admin_pages[0];

		$subpage = array(
			array(
				'parent_slug' => $admin_page['menu_slug'], 
				'page_title' => $admin_page['page_title'], 
				'menu_title' => ($title) ? $title : $admin_page['menu_title'], 
				'capability' => $admin_page['capability'], 
				'menu_slug' => $admin_page['menu_slug'], 
				'callback' => $admin_page['callback']
			)
		);

		$this->admin_subpages = $subpage;

		return $this;
	}

	public function addSubPages( array $pages )
	{
		$this->admin_subpages = array_merge( $this->admin_subpages, $pages );

		return $this;
	}

	public function addAdminMenu()
	{
        $notification_count  = $this->get_notification();
		foreach ( $this->admin_pages as $page ) {
			add_menu_page( $page['page_title'],($notification_count)? sprintf( $page['menu_title'].' <span class="awaiting-mod">%d</span>', $notification_count) :  $page['menu_title'], $page['capability'], $page['menu_slug'], $page['callback'], $page['icon_url'], $page['position'] );
		}

		foreach ( $this->admin_subpages as $page ) {
			add_submenu_page( $page['parent_slug'], $page['page_title'], $page['menu_title'], $page['capability'], $page['menu_slug'], $page['callback'] );
		}
	}

	public function setlabelData( array $labelData )
	{
		$this->labelData = $labelData;

		return $this;
	}

	public function setlabelCptName( $label)
	{
		$this->labelCptName = $label;

		return $this;
	}



	public function registerCustomFields()
	{
		// register setting
		register_post_type( $this->labelCptName, $this->labelData);

	}

    public function get_notification(){
        global $wpdb;
        $items = $wpdb->get_results( 'SELECT * FROM ' . $wpdb->prefix . 'shipment_schedule WHERE tracking_code IS NULL AND product_suggestion IS  NULL ');
        return count($items);

    }

	
}