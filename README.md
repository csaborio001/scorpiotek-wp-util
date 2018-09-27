# scorpiotek-wp-util
Classes for common WordPress tasks such as logging, modifying admin menu items, etc.

For modifying admin menus activate the pluging and use the following code (ideally in your functions.php file):

use \ScorpioTek\WordPress\Util\Menu\AdminMenuModifier as AdminMenuModifier;

function clean_admin_menu() {
    // Menu Modifications
    if ( class_exists ('ScorpioTek\WordPress\Util\Menu\AdminMenuModifier') ) {
        AdminMenuModifier::remove_posts_menu( 'editor' );
        ...
    }
}
add_action('admin_menu', 'clean_admin_menu');




