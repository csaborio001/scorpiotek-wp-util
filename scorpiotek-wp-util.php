<?php

/*  	
    Plugin Name: ScorpioTek WordPress Utilities
    Description: Contains utility classes to assist with WordPress theme development
    @since  1.0
    Version: 1.0.1.8
	Text Domain: scorpiotek.com
*/

// exit if file is called directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( file_exists( __DIR__ . '/include/scorpiotek-log-util.php' ) ) {
    require_once( 'include/scorpiotek-log-util.php' );
}
else {
    error_log( 'ScorpioTek-WP-Util: Error loading file scorpiotek-log-util.php' );
}

if ( file_exists( __DIR__ . '/include/scorpiotek-menu-util.php' ) ) {
    require_once( 'include/scorpiotek-menu-util.php' );
}
else {
    error_log( 'ScorpioTek-WP-Util: Error loading file scorpiotek-menu-util.php' );
}

if ( file_exists( __DIR__ . '/include/scorpiotek-posts-util.php' ) ) {
    require_once( 'include/scorpiotek-posts-util.php' );
}
else {
    error_log( 'ScorpioTek-WP-Util: Error loading file scorpiotek-posts-util.php' );
}

if ( file_exists( __DIR__ . '/include/scorpiotek-dynamicdashboard.php' ) ) {
    require_once( 'include/scorpiotek-dynamicdashboard.php' );
}
else {
    error_log( 'ScorpioTek-WP-Util: Error loading file scorpiotek-dynamicdashboard.php' );
}


if ( file_exists( __DIR__ . '/include/scorpiotek-date-util.php' ) ) {
    require_once( 'include/scorpiotek-date-util.php' );
}
else {
    error_log( 'ScorpioTek-WP-Util: Error loading file scorpiotek-date-util.php' );
}

if ( file_exists( __DIR__ . '/include/scorpiotek-form-util.php' ) ) {
    require_once( 'include/scorpiotek-form-util.php' );
}
else {
    error_log( 'ScorpioTek-WP-Util: Error loading file scorpiotek-form-util.php' );
}



