<?php
/**
 * Allows the customization of the WPAmdin Menu
 *
 * @package ScorpioTekWPUtils
 *
 * @version 0.1.0
 */

namespace ScorpioTek\WordPress;

/**
 * Contains the members and functions that allows the user to modify the WordPress admin menu.
 */
class AdminMenuModifier {

	/**
	 * Holds the array conversion of admin menu items to their corresponding slugs. Loaded from
	 * the JSON file inside config that is loaded in the constructor.
	 *
	 * @var array $menu_slug_array
	 */
	private $menu_slug_array;
	/**
	 * Initializes the AdminMenuModifier by loading the array name/slugs from JSON file.
	 *
	 * @return true if the class could be properly instantiated.
	 */
	public function __construct() {
		$json_data = file_get_contents( dirname( __DIR__, 1 ) . '/config/menu-slugs.json' );
		if ( false === $json_data ) {
			error_log ( __( 'Could not load the json file' ) );
			return false;
		}
		$json_decoded_data = json_decode( $json_data, true );
		if ( $json_decoded_data === false || $json_decoded_data === null) {
			error_log ( __( 'Could not get contents of json file' ) );
			return false;
		}
		// Load the PHP array representation of the JSON file into memory.
		$this->set_menu_slug_array( $json_decoded_data['admin-menus'] );
		return true;
	}

	/**
	 * Sets the action so that the function that checks to see if the
	 * message needs to be replaced for the current user.
	 *
	 * @param string $new_greeting - the text to replace the howdy message.
	 * @param array  $target_roles - the array to apply the greeting change.
	 */
	public function replace_howdy_message( $new_greeting, $target_roles ) {
		/** If the user is not in the target roles, bail out, nothing to do. */
		if ( ! $this->is_current_user_in_group( $target_roles ) ) {
			return;
		}
		add_action(
			'wp_before_admin_bar_render',
			function() use ( $new_greeting, $target_roles ) {
				$this->replace_howdy_callback( $new_greeting, $target_roles );
			}
		);
	}
	/**
	 * The callback that takes care of verifying if the user belongs to the
	 * roles that were specified and applying the message change if they are.
	 *
	 * @param string $new_greeting - the text to replace the howdy message.
	 * @param array  $target_roles - the array to apply the greeting change.
	 */
	public function replace_howdy_callback( $new_greeting, $target_roles ) {
		/** Current user belongs to one of those roles, change the message. */
		global $wp_admin_bar;
		$my_account = $wp_admin_bar->get_node( 'my-account' );
		$new_title  = str_replace( 'Howdy', $new_greeting, $my_account->title );
		$wp_admin_bar->add_node(
			array(
				'id'    => 'my-account',
				'title' => $new_title,
			)
		);
	}
	/**
	 * Checks to see if the current user belongs to any of the passed
	 * target roles.
	 *
	 * @param array $target_roles the list of groups to check if the current user
	 * belongs to.
	 */
	public function is_current_user_in_group( $target_roles ) {
		$current_user_roles = wp_get_current_user()->roles;
		foreach ( $current_user_roles as $role ) {
			if ( in_array( $role, $target_roles, true ) ) {
				return true;
			}
		}
		return false;
	}
	/**
	 * Removes a WP_Admin item for the current user if they belong in one
	 * of the passed target roles.
	 *
	 * @param string $menu_name - the name of the menu to remove.
	 * @param array  $target_roles - the list containing the roles to look for.
	 */
	public function remove_menu_item_for_role( $menu_name, $target_roles ) {
		/** If the user is not in the target roles, bail out, nothing to do. */
		if ( ! $this->is_current_user_in_group( $target_roles ) ) {
			return;
		}
		add_action(
			'admin_menu',
			function() use ( $menu_name, $target_roles ) {
				$this->remove_menu_item_for_role_callback( $menu_name );
			}
		);
	}
	/**
	 * The function that does the actual heavy-lifting of removing the menu.
	 * Simply checks if the menu name the user passed lives as a key in the
	 * array loaded in memory from the menu-slugs.json file. If so, then it
	 * removes it so that the current user won't see them.
	 * 
	 * @param string $menu_name - the name of the menu to remove.
	 * @param array  $target_roles - the list containing the roles to look for.
	 */
	public function remove_menu_item_for_role_callback( $menu_name ) {
		if ( array_key_exists( $menu_name, $this->get_menu_slug_array() ) ) {
			remove_menu_page( $this->get_menu_slug_array()[ $menu_name ] );
		} else {
			remove_menu_page( $menu_name );
		}
	}


	/**
	 * Setter for the menu_slug_array property.
	 *
	 * @param string $menu_slug_array the new value of the menu_slug_array property.
	 */
	public function set_menu_slug_array( $menu_slug_array ) {
		$this->menu_slug_array = $menu_slug_array;
		return $this->get_menu_slug_array();
	}
	/**
	 * Getter for the menu_slug_array property.
	 *
	 * @return string - the value of the menu_slug_array property.
	 */
	public function get_menu_slug_array() {
		return $this->menu_slug_array;
	}

	/** EVERYTHING BELOW NEEDS TO BE REFACTORED/REWRITTEN AND SHOULD NOT BE USED */

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
}
