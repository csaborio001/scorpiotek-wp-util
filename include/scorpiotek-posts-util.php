<?php

namespace ScorpioTek\WordPress\Util;

class PostUtilities {

    function get_excerpt( $count ){
        $permalink = get_permalink($post->ID);
        $excerpt = get_the_content();
        $excerpt = strip_tags($excerpt);
        $excerpt = substr($excerpt, 0, $count);
        $excerpt = substr($excerpt, 0, strripos($excerpt, " "));
        //$excerpt = $excerpt.'... <a href="'.$permalink.'">more</a>';
        return $excerpt;
    }

    static function get_excerpt_max_words( $text_to_summarize, $desired_word_length ) {
        // Put all words in an array
        $text_to_summarize_array = explode(' ' , $text_to_summarize);
        // Create a string that contains the specified number of words
        return implode(' ', array_splice( $text_to_summarize_array, 0, $desired_word_length ));  
    }

     static function seo_friendly_url( $string ){
        $string = str_replace(array('[\', \']'), '', $string);
        $string = preg_replace('/\[.*\]/U', '', $string);
        $string = preg_replace('/&(amp;)?#?[a-z0-9]+;/i', '-', $string);
        $string = htmlentities($string, ENT_COMPAT, 'utf-8');
        $string = preg_replace('/&([a-z])(acute|uml|circ|grave|ring|cedil|slash|tilde|caron|lig|quot|rsquo);/i', '\\1', $string );
        $string = preg_replace(array('/[^a-z0-9]/i', '/[-]+/') , '-', $string);
        return strtolower(trim($string, '-'));
    }    

    static function get_image_from_id_or_array( $image_source ) {
        /*
        * There's a bug that makes the $image_source return the attachment ID instead of the image object.
        * In order to get around this we check to see if it is an array and get the URL field of the image object.
        * If it is not an array, then we are dealing with the image ID, so we obtain the URL by using wp_get_attachment_url()
        */        
        if ( is_array( $image_source ) ) {
            return $image_source['url'];
         }
         else {
            return wp_get_attachment_url( $image_source );
         }           
    }


}