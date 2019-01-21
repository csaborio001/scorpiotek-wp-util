# scorpiotek-wp-util
---
Classes for common WordPress tasks such as logging, modifying admin menu items, etc.

# Menu Utilities

The class AdminMenuModifier provides an easy way to modify admin menus. Namely, it 
makes it possible to remove any admin menu for any particular user.

## Usage

* Activate the plugin as with any normal plugin. You won't see anything but you will now be able to use
the class.

* Use the following statement to easily invoke the class:

        use \ScorpioTek\WordPress\Util\Menu\AdminMenuModifier as AdminMenuModifier;

* Attach the function that will modify the menus and call the respective function:

        if ( class_exists ('ScorpioTek\WordPress\Util\Menu\AdminMenuModifier') ) {
        $cesaa_AdminMenuModifier = new AdminMenuModifier();

* You can now invoke any of the functions and specify which users the function will affect. For instance, to remove the 
WP logo for editor users, you invoke the function as follows:

        $cesaa_AdminMenuModifier->remove_item_from_wp_adminbar( 'wp_admin_bar_logo', array( 'editor' ) );

The *remove_menu_from_admin_sidebar* and *remove_item_from_wp_adminbar* expect a menu_name parameter that defines
what will be removed. These values are defined by the labels inside the menu_option_ids array inside the AdminMenuModifier class:

    private $menu_option_ids = array (
        'admin_menu_main_posts' => 'edit.php',
        'admin_menu_main_pages' => 'edit.php?post_type=page',
        'admin_menu_main_comments' => 'edit-comments.php',
        'admin_menu_main_tools' => 'tools.php',
        'admin_menu_main_media' => 'upload.php',
        'admin_menu_main_envira' => 'edit.php?post_type=envira',
        'admin_menu_main_gravity_forms' => 'gf_entries',
        ...


## Version History 

### 1.0.1 - January 21, 2019

* Added dynamic dashboard code, cleaned up code a bit.

### 1.0 - October 13, 2018

* Initial Release
* Refactored AdminMenuModifier code to use less functions and allow the user to define items to be removed by using parameters
* Documented the code in the AdminMenuModifier class





