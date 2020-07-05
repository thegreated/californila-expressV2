<?php
/**
 * @package  californila-express
 */
namespace Inc\Classes;



class Action
{
    public function register()
    {

        add_action( 'template_redirect', array($this,'redirect_to_specific_page' ));
    }



    function redirect_to_specific_page() {

        if ( is_page('dashboard') && !is_user_logged_in() ) {

            $page = get_page_by_title('login');
            $title = get_permalink($page->ID);
            wp_redirect($title.'?errors=privacy', 301 );
            exit;
        }
        elseif ( is_page('packages') && !is_user_logged_in() ) {

            $page = get_page_by_title('login');
            $title = get_permalink($page->ID);
            wp_redirect($title.'?errors=privacy', 301 );
            exit;
        }
        elseif ( is_page('add-delivery-address') && !is_user_logged_in() ) {

            $page = get_page_by_title('login');
            $title = get_permalink($page->ID);
            wp_redirect($title.'?errors=privacy', 301 );
            exit;
        }
        elseif ( is_page('sent-package') && !is_user_logged_in() ) {

            $page = get_page_by_title('login');
            $title = get_permalink($page->ID);
            wp_redirect($title.'?errors=privacy', 301 );
            exit;
        } elseif ( is_page('packages-personal-shopper') && !is_user_logged_in() ) {

            $page = get_page_by_title('login');
            $title = get_permalink($page->ID);
            wp_redirect($title.'?errors=privacy', 301 );
            exit;
        }
    }

}