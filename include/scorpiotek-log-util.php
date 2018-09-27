<?php

namespace ScorpioTek\WordPress\Util\Log;

class Logger {

    static function write_log( $log ) {
        if ( is_array( $log ) || is_object( $log ) ) {
            error_log( print_r( $log, true ) );
        } else {
            error_log( $log );
        } 
    }
}