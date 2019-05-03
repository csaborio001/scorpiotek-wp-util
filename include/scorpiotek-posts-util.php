<?php

namespace ScorpioTek\WordPress\Util;

class PostUtilities {

    static function get_excerpt( $count ){
        $permalink = get_permalink($post->ID);
        $excerpt = get_the_content();
        $excerpt = strip_tags($excerpt);
        $excerpt = substr($excerpt, 0, $count);
        $excerpt = substr($excerpt, 0, strripos($excerpt, " "));
        //$excerpt = $excerpt.'... <a href="'.$permalink.'">more</a>';
        return $excerpt;
    }

    static function get_excerpt_max_words( $text_to_summarize, $desired_word_length ) {
        // Convert each word into an array of words.
        $text_to_summarize_array = explode( ' ' , $text_to_summarize);
        if ( count( $text_to_summarize_array )  < $desired_word_length ) return $text_to_summarize;
        // Remove everything from the array after index of $desired_word_length, then convert
        // back to string by concatenating each array cell with a blank space.
        return implode( ' ', array_splice( $text_to_summarize_array, 0, $desired_word_length ));  
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

     /**
  * @summary Displays featured image in various ways
  *
  * @description Tries to get the featured image of a post by using various methods. It tries to get the default features image, tries to get a predefined field of a content type.
  *
  * @author Christian Saborio <csaborio@scorpiotek.com>
  *
  * @param string $field name the name if the field type that holds the image information
  *
  * @return none
  */
  
  static function get_featured_image( $post, $dimensions = array(), $field_name = '', $default_image_path = '' )  {
    // Set the dimensions, default to medium if empty.
    $image_dimensions = empty( $dimensions ) ? 'medium' : $dimensions;
    // Figure out if the post has a featured image.
    $thumbnail_id = has_post_thumbnail( $post->ID ) ? get_post_thumbnail_id( $post->ID ) : -1;
    // If it doesn't have a featured image, try and get the ACF filed associated with the post image.
    if ( $thumbnail_id == -1 && !empty( $field_name ) ) {
        $thumbnail_id = get_field( $field_name, $post->ID );
    }
    // See of we can get an image from the thumbnail id provided.
    $image_url = wp_get_attachment_image( $thumbnail_id, $image_dimensions, false, array( 'alt' => $post->post_title ) );
    // If image is a valid URL print that.
    if ( !empty( $image_url ) ) {
        echo $image_url;
    }
    else { // Otherwise print default image.
        
        if ( is_array( $dimensions ) && count( $dimensions ) === 2 ) {
            echo sprintf('<img src="%1$s" alt="%2$s" width="%3$s" height="%4$s" />',
            $default_image_path,
            $post->post_title,
            $dimensions[0],
            $dimensions[1]
            );
        }
        else {
            echo ('<img src="' . $default_image_path . '" alt="' . $post->post_title . '" />' );
        }
    }
  }

  static function get_custom_categories( $post_id, $taxonomy ) {
    $categories = get_the_terms( $post_id, $taxonomy );
    $taxonomies = '';
    foreach ( $categories as $term ) {
        // Need to figure if this is the last item in the list or if this is a list that 
        // only has one element in it. 
        $last_item = ( ( $categories[ count( $categories ) - 1 ] )->name == $term->name ) ||
        ( count( $categories) == 1 )  ? true : false;
        // If it is not the last element, append comma at the end, otherwise nothing.
        $taxonomies = $term->name . ( $last_item ? '' : ', ' );
    }
    return $taxonomies;
  }

  static function the_custom_categories( $post_id, $taxonomy ) {
    echo get_custom_categories( $post_id, $taxonomy );
  }

}

        
