<?php

namespace ScorpioTek\WordPress\Util;

class DateUtilities {
    static function convert_spanish_date_to_english( $spanish_date_field ) {
        $nmeng = array('january', 'february', 'march', 'april', 'may', 'june', 'july', 'august', 'september', 'october', 'november', 'december');
        $nmspn = array('enero', 'febrero', 'marzo', 'abril', 'mayo', 'junio', 'julio', 'agosto', 'septiembre', 'octubre', 'noviembre', 'diciembre');
        return str_ireplace( $nmspn, $nmeng, $spanish_date_field );
    }
}