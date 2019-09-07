<?php

namespace ScorpioTek\WordPress;

class AdminMenuModifier {

    private $menuSlugArray;
    /**
     * __construct
     *
     * @return true if the class could be properly instantiated.
     */
    public function __construct() {
        $json_data = file_get_contents( dirname( __DIR__, 1 ) . '/config/menu-slugs.json' );
        if ( $json_data === false ) {
            error_log ( __( 'Could not load the json file' ) );
            return false;
        }
        $json_decoded_data = json_decode( $json_data, true );
        if ( $json_decoded_data === false || $json_decoded_data === null) {
            error_log ( __( 'Could not get contents of json file' ) );
            return false;
        }
        // Load the PHP array representation of the JSON file into memory.
        $this->setMenuSlugArray( $json_decoded_data['admin-menus'] );
        return true;
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

    public function replaceHowdyFromAdminBar ( $new_greeting, $capabilities ) {
        add_action( 'wp_before_admin_bar_render', function() use ( $new_greeting, $capabilities ) {
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

    /**
     * @summary Hides CSS by ID from the WordPress admin area
    *
    * @description Some elements are stubborn and will not be removed from the admin
    * area by using remove_menu_page, so this function was created to use jQuery to hide those
    * elements from the user.
    *
    * @author Christian Saborio <csaborio@scorpiotek.com>
    *
    * @param string $menu_name the CSS id to be removed. 
    * @param array $capabilities an array that specifies one ore more capability to remove the menu item for
    * @return void
    */
    
    public function remove_menu_element_by_id ( $css_id_list, $capabilities ) {
        add_action( 'admin_footer', function() use ( $css_id_list, $capabilities ) { 
            foreach( $capabilities as $capability ) {
                if ( current_user_can( $capability ) ) {
                    echo ( '<script type="text/javascript">');
                    foreach ( $css_id_list as $css_id ) {
                        echo sprintf( 'jQuery("#%1$s").hide()',
                        $css_id );                    
                    }
                    echo ( '</script>');

                }
            }
        });
    }

    public function remove_menu_from_admin_sidebar ( $menu_name, $capabilities ) {
        add_action( 'admin_menu', function() use ( $menu_name, $capabilities ) {
            if ( array_key_exists( $menu_name, $this->getMenuSlugArray() ) ) {
                if ( is_array( $capabilities) ) {
                    foreach( $capabilities as $capability ) {
                        if ( current_user_can( $capability ) ) {
                            remove_menu_page( $this->getMenuSlugArray()[$menu_name] );
                        }
                    }
                }
            }
            else {
                if ( is_array( $capabilities) ) {
                    foreach( $capabilities as $capability ) {
                        remove_menu_page( $menu_name );
                    }
                }
            }
        });
    }

    public function makeWPFormsVisibleToEditors() {
        add_filter( 'wpforms_manage_cap', function( $cap ) {
            return 'unfiltered_html';
        });
    }

    public function addEditablePagesToMenu($pageIDsArray, $menuStartPosition, $displayAddNewSubMenu) {
        add_action( 'admin_menu', function() use ($pageIDsArray, $menuStartPosition, $displayAddNewSubMenu) {
            // add_menu_page('Website Sections', 'Website Sections', 'editor', null, '', 'dashicons-chart-pie', $menuStartPosition++);
            // Register the parent menu.
            $parent_slug = 'website_sections';
            add_menu_page(
                __( 'Website Sections', 'scorpiotek' ),
                __( 'Website Sections', 'scorpiotek' ),
                'editor',
                $parent_slug,
                'display_my_menu',
                'dashicons-chart-pie',
                $menuStartPosition++
            );
            $pages_args = array(
                'sort_order' => 'asc',
                 'sort_column' => 'post_title',
                 // 'hierarchical' => 1,
                 // 'exclude' => array(345),
                //  'include' => '',
                 // 'meta_key' => '',
                 // 'meta_value' => '',
                 // 'authors' => '',
                 // 'child_of' => 0,
                 // 'parent' => -1,
                 // 'exclude_tree' => '',
                 // 'number' => '',
                 // 'offset' => 0,
                 // 'post_type' => 'page',
                 'post_status' => 'publish'
             );
             
             // We will get all pages unless the pageIDsArray parameter was specified.
             if (!empty($pageIDsArray)) {
                 $pages_args['include'] = $pageIDsArray;
             }

             $site_pages = get_pages( $pages_args );
         
             foreach ($site_pages as $page) {
                 add_submenu_page( $parent_slug, $page->post_title, $page->post_title, 'editor', 'post.php?post=' . $page->ID . '&action=edit' );
             }
             if ( $displayAddNewSubMenu ) add_submenu_page( $parent_slug, 'Add New Page', 'Add New Page', 'editor', $parent_slug );

        });
    }

    public function debug_admin_menu() {
        if ( WP_DEBUG ) {
            add_action( 'admin_init',  function() {
                echo '<pre>' . print_r( $GLOBALS[ 'menu' ], TRUE) . '</pre>';
            });
        }
    }    

    /**
     * Setter for menuSlugArray
     *
     * @param string $menuSlugArray the new value of the menuSlugArray property.
     */
    public function setMenuSlugArray( $menuSlugArray ) {
        $this->menuSlugArray = $menuSlugArray;
    }
    /**
     * Getter for the menuSlugArray property.
     */
    public function getMenuSlugArray() {
        return $this->menuSlugArray;
    }

}