<?php
/**
 * @package  californila-express
 */
namespace Inc;

final class Init
{
	/**
	 * Store all the classes inside an array
	 * @return array Full list of classes
	 */
	public static function get_services() 
	{
		return [
			//Classes\User::class,
			Pages\Admin::class,
			Base\Enqueue::class,
			Base\SettingsLinks::class,
            Post\Package::class,
			Post\Faq::class,
			Post\Address::class,
           // Post\Warehouse::class,
			Custom\Package::class,
			Custom\Address::class,
         //   Post\Warehouse::class,
            Custom\Warehouse::class,
            Classes\Pages::class,
            Classes\Shortcode::class,
            Classes\Email::class,
            Classes\Action::class,
            Classes\Packages::class,
            Classes\Address::class,
            Classes\Dashboard::class,
            Custom\Order::class,
		];
	}

	/**
	 * Loop through the classes, initialize them, 
	 * and call the register() method if it exists
	 * @return
	 */
	public static function register_services() 
	{
		foreach ( self::get_services() as $class ) {
			$service = self::instantiate( $class );
			if ( method_exists( $service, 'register' ) ) {
				$service->register();
			}
		}
	}

	/**
	 * Initialize the class
	 * @param  class $class    class from the services array
	 * @return class instance  new instance of the class
	 */
	private static function instantiate( $class )
	{
		$service = new $class();

		return $service;
	}
}