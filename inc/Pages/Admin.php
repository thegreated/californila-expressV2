<?php 
/**
 * @package  AlecadddPlugin
 */
namespace Inc\Pages;

use Inc\Api\SettingsApi;
use Inc\Base\BaseController;
use Inc\Api\Callbacks\AdminCallbacks;
use Inc\Api\Callbacks\ManagerCallbacks;

/**
* 
*/
class Admin extends BaseController
{
	public $settings;

	public $callbacks;
	public $callbacks_mngr;

	public $pages = array();

	public $subpages = array();

	public function register() 
	{
		$this->settings = new SettingsApi();

		$this->callbacks = new AdminCallbacks();
		$this->callbacks_mngr = new ManagerCallbacks();

		$this->setPages();

		$this->setSubpages();

	

		$this->settings->addPages( $this->pages )->withSubPage( 'Dashboard' )->addSubPages( $this->subpages )->register();
	}

	public function setPages() 
	{
		$this->pages = array(
			array(
				'page_title' => 'Californila Express', 
				'menu_title' => 'Californila', 
				'capability' => 'manage_categories',
				'menu_slug' => 'californila_plugin', 
				'callback' => array( $this->callbacks, 'adminDashboard' ), 
				'icon_url' => 'dashicons-store', 
				'position' => 110
			)
		);
	}

	public function setSubpages()
	{
		// $this->subpages = array(
		// 	array(
		// 		'parent_slug' => 'californila_plugin', 
		// 		'page_title' => 'User Packages', 
		// 		'menu_title' => 'Packages', 
		// 		'capability' => 'manage_options', 
		// 		'menu_slug' => 'packages', 
		// 		'callback' => array( $this->callbacks, 'adminPackage' )
		// 	)
		
		// );
	}

	
}