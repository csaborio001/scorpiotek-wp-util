<?php

/*  	
    Plugin Name: ScorpioTek WordPress Utilities
    Description: Contains utility classes to assist with WordPress theme development
    @since  1.0
    Version: 1.0.1.2
	Text Domain: scorpiotek.com
*/

// exit if file is called directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

require_once( 'include/scorpiotek-log-util.php' );
require_once( 'include/scorpiotek-menu-util.php' );
require_once( 'include/scorpiotek-posts-util.php' );
require_once( 'include/scorpiotek-dynamicdashboard.php' );


