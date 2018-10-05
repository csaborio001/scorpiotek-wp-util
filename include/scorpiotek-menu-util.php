<?php

namespace ScorpioTek\WordPress\Util\Menu;

class AdminMenuModifier {

    public static function remove_posts_menu ( $capability ) {
        if ( current_user_can( $capability ) ) {
            remove_menu_page( 'edit.php' );
        }
    }

    public static function remove_pages_menu ( $capability ) {
        if ( current_user_can( $capability ) ) {
            remove_menu_page( 'edit.php?post_type=page' );
        }
    }    

    public static function remove_comments_menu ( $capability ) {
        if ( current_user_can( $capability ) ) {
            remove_menu_page( 'edit-comments.php' );
        }
    }    

    public static function remove_tools_menu ( $capability ) {
        if ( current_user_can( $capability ) ) {
            remove_menu_page( 'tools.php' );
        }
    }    

    public static function remove_media_menu ( $capability ) {
        if ( current_user_can( $capability ) ) {
            remove_menu_page( 'upload.php' );
        }
    }    

    public static function remove_envira_menu ( $capability ) {
        if ( current_user_can( $capability ) ) {
            remove_menu_page( 'edit.php?post_type=envira' );
        }
    }    

    public static function remove_forms_menu ( $capability ) {
        if ( current_user_can( $capability ) ) {
            remove_menu_page( 'admin.php?page=gf_entries' );
        }
    } 
    
    public static function remove_new_from_admin_bar( $capability ) {
        add_action( 'wp_before_admin_bar_render', function() use ( $capability ) {
            if ( current_user_can( $capability ) ) {
                global $wp_admin_bar;
                // die(var_dump($wp_admin_bar));
                $wp_admin_bar->remove_menu('new-content');
            }
        });
    }
    
    public static function remove_comment_from_admin_bar( $capability ) {
        add_action( 'wp_before_admin_bar_render', function() use ( $capability ) {
            if ( current_user_can( $capability ) ) {
                global $wp_admin_bar;
                $wp_admin_bar->remove_menu('comments');
            }
        });
    }
    
    public static function remove_wordpress_logo_from_admin_bar( $capability ) {
        add_action( 'wp_before_admin_bar_render', function() use ( $capability ) {
            if ( current_user_can( $capability ) ) {
                global $wp_admin_bar;
                $wp_admin_bar->remove_menu('wp-logo');
            }
        });
    }
    
    public static function replace_howdy_from_admin_bar( $new_greeting, $capability ) {
        add_action( 'wp_before_admin_bar_render', function( ) use ( $new_greeting, $capability ) {
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
        });
    }
    
    // http://bit.ly/2xdzD07

    public static function add_menu_separator( $position ) {
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

}