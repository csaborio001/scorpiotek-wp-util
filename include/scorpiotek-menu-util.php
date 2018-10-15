<?php

namespace ScorpioTek\WordPress\Util\Menu;

class AdminMenuModifier {
    
    /* 
     * These are the allowed operations contained into in an array
     * When remove_item_from_wpadminbar or remove_menu_from_admin_sidebar is invoked
     * we check to see if the menu name exists in this array
     */
    private $menu_option_ids = array (
        'admin_menu_main_posts' => 'edit.php',
        'admin_menu_main_pages' => 'edit.php?post_type=page',
        'admin_menu_main_comments' => 'edit-comments.php',
        'admin_menu_main_users' => 'users.php',
        'admin_menu_main_tools' => 'tools.php',
        'admin_menu_main_settings' => 'options-general.php',
        'admin_menu_main_custom_fields' => 'edit.php?post_type=acf-field-group',
        'admin_menu_main_media' => 'upload.php',
        'admin_menu_main_envira' => 'edit.php?post_type=envira',
        'admin_menu_main_gravity_forms' => 'gf_edit_forms',
        'wp_admin_bar_plus_menu' => 'new-content',
        'wp_admin_bar_sidebar_comments' => 'comments',
        'wp_admin_bar_logo' => 'wp-logo',
        'wp_admin_bar_view_posts' => 'archive',
        'woocommerce' => 'woocommerce',
        'woocommerce_edit_products' => 'edit.php?post_type=product',
        'cerber' => 'cerber-security'
    );

/**
  * @summary Removes elements from the admin sidebar for one or more capabilities
  *
  * @description Some elements in the admin sidebar do not need to be displayed for certain users 
  * or groups. This function removes the elements specified for those user groups
  *
  * @author Christian Saborio <csaborio@scorpiotek.com>
  *
  * @param string $menu_name the menu id to be removed. Has to match one of the keys available in $menu_option_ids
  * @param array $capabilities an array that specifies one ore more capability to remove the menu item for
  * @return void
  */
  
    public function remove_menu_from_admin_sidebar ( $menu_name, $capabilities ) {
        if ( array_key_exists( $menu_name, $this->get_menu_option_ids() ) ) {
            if ( is_array( $capabilities) ) {
                foreach( $capabilities as $capability ) {
                    if ( current_user_can( $capability ) ) {
                        remove_menu_page( $this->get_menu_option_ids()[$menu_name] );
                    }
                }
            }
        }
    }
 
/**
  * @summary Removes elements from the wp admin bar (the horizonal one)
  *
  * @description Some elements in the wp admin bar sidebar do not need to be displayed for certain users 
  * or groups. This function removes the elements specified for those user groups
  *
  * @author Christian Saborio <csaborio@scorpiotek.com>
  *
  * @param string $menu_name the menu id to be removed. Has to match one of the keys available in $menu_option_ids
  * @param array $capabilities an array that specifies one ore more capability to remove the menu item for
  * @return void
  *
  */
  
    public function remove_item_from_wp_adminbar( $menu_name, $capabilities ) {
        add_action( 'wp_before_admin_bar_render', function() use ( $menu_name, $capabilities ) {
            if ( array_key_exists( $menu_name, $this->get_menu_option_ids() ) ) {
                if ( is_array( $capabilities) ) {
                    foreach( $capabilities as $capability ) {
                            global $wp_admin_bar;
                            $wp_admin_bar->remove_menu($this->get_menu_option_ids()[$menu_name]);
                    }
                }
            }
        });        
    }

/**
  * @summary Replaces the default 'Howdy ' greeting with a new greeting
  *
  * @description WordPress adds 'Howdy ' before the username in the top right corner of the backed.
  * In some scenarios the 'Howdy' greeting is not appropriate. This function allows the greeting
  * to be changed for one or more capabilities.
  *
  * @author Christian Saborio <csaborio@scorpiotek.com>
  *
  * @param string $new_greeting the text that will replace 'Howdy'
  * @param array $capabilities the array that contains the capabilities to which to apply the replacement
  *
  * @return void
  */
  
    public function replace_howdy_from_admin_bar( $new_greeting, $capabilities ) {
        add_action( 'wp_before_admin_bar_render', function( ) use ( $new_greeting, $capabilities ) {
            if ( is_array( $capabilities) ) {
                foreach( $capabilities as $capability ) {            
                    if ( current_user_can( $capability ) ) {
                        global $wp_admin_bar;
                        $my_account = $wp_admin_bar->get_node('my-account');
                        $new_title = str_replace( 'Howdy', $new_greeting, $my_account->title );
                        $wp_admin_bar->add_node( array(
                                'id' => 'my-account',
                                'title' => $new_title
                            )
                        );
                    }
                }
            }
        });
    }
    
    // http://bit.ly/2xdzD07

/**
  * @summary Adds a WordPress separator (some space) after a particular menu
  *
  * @description WordPress allows the use of separators to give a feeling of grouping between certain elements 
  * This function allows to easily add these separators.
  *
  * @author Christian Saborio <csaborio@scorpiotek.com>
  *
  * @param int $position the position in which to insert the separator in the WordPress menu
  *
  * @return void
  */
  
    public function add_menu_separator( $position ) {
        add_action( 'admin_init', function() use ( $position ) {
            global $menu;
            $menu[ $position ] = array(
                0	=>	'',
                1	=>	'read',
                2	=>	'separator' . $position,
                3	=>	'',
                4	=>	'wp-menu-separator'
            );            
        });          

    }

/**
  * @summary Simple Getter for the Menu Option IDs of this class
  *
  * @description description
  *
  * @author Christian Saborio <csaborio@scorpiotek.com>
  *
  * @return array A copy of the $menu_option_ids array
  */
  

    public function get_menu_option_ids() {
            return $this->menu_option_ids;
    }

     /**
     * @summary Prints out the menu hierarchy in Array Notation, useful for debugging. Do not use in production.
    *
    * @description The menu admin sidebar structure in WordPress is created from an array. 
    * Each menu item contains arrays that contain properties for each of the elements. This function will print those values.
    * The user can then use 'View Source' to get a good idea of the array structure. 
    * Very useful if the user wants to find the ID of an element to be modified/deleted
    *
    * @author Christian Saborio <csaborio@scorpiotek.com>
    *
    */
  
    public function debug_admin_menu() {
        if ( WP_DEBUG ) {
            add_action( 'admin_init',  function() {
                echo '<pre>' . print_r( $GLOBALS[ 'menu' ], TRUE) . '</pre>';
            });
        }
    }
}