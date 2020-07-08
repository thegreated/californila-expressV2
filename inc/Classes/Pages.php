<?php
/**
 * @package  AlecadddPlugin
 */
namespace Inc\Classes;

use Inc\Base\BaseController;

/**
 *
 */
class Pages extends BaseController
{

    private $pages_data;


    public function register()
    {
        //dashboard
        $page = new Pages();
        $title = 'orders';
        $page->setPagesData($title);
        $title = 'packages';
        $page->setPagesData($title);
        $title = 'edit-packages';
        $page->setPagesData($title);
        $title = 'add-delivery-address';
        $page->setPagesData($title);
        $title = 'view-package';
        $page->setPagesData($title);
        $title = 'purchase';
        $page->setPagesData($title);
        $title = 'checkout';
        $page->setPagesData($title);
        $title = 'sent-package';
        $page->setPagesData($title);
        $title = 'tracking-package';
        $page->setPagesData($title);
        $title = 'track-package';
        $page->setPagesData($title);
        $title = 'packages-personal-shopper';
        $page->setPagesData($title);
        //
        $title = 'login';
        $page->setPagesData($title);
        $title = 'logout';
        $page->setPagesData($title);
        $title = 'registration';
        $page->setPagesData($title);
        $title = 'dashboard';
        $page->setPagesData($title);
        $title = 'packages';
        $page->setPagesData($title);
        $title = 'forgot-password';
        $page->setPagesData($title);


    }

    public function setPagesData( $title)
    {
        if( get_page_by_title( $title ) == NULL ){
            $pages_data = array(
                'post_content'   => '', //content of page
                'post_title'     =>   $title, //title of page
                'post_status'    =>  'publish' , //status of page - publish or draft
                'post_type'      =>  'page'  // type of post
            );
            wp_insert_post( $pages_data );
        }


    }


}