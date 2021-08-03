<?php

/**
 * Add wordpress post basic fields values to post args
 * 
 * @param array &$post_args
 * @param array $basic_values
 * 
 * @return void
 */
function ntdc_add_basic_fields_values_to_post_args ( &$post_args, $basic_values ) {
    ntdc_basic_fields_values_handler( $basic_values );
    foreach( $basic_values as $basic_key => $basic_value ) {
        $post_args[ $basic_key ] = $basic_value;
    }
}

/**
 * Handle wordpress post basic fields values
 * 
 * @param array $basic_values
 * 
 * @return void
 */
function ntdc_basic_fields_values_handler( &$basic_values ) {
    // Set default values
    $default_values = [
        'status'    => 'publish',
        'type'      => 'post'
        'title'     => 'Untitled post',
        'timestamp' => time(),
        'content'   => '',
    ];

    // Move to formatter ?
    $basic_values = [
        'status'    => isset( $basic_values['status'] ) && ! emtpy( $basic_values['status'] ) ? $basic_values['status'] : $default_values['status'],
        'type'      => isset( $basic_values['type'] ) && ! emtpy( $basic_values['type'] ) ? $basic_values['type'] : $default_values['type'],
        'title'     => isset( $basic_values['title'] ) && ! emtpy( $basic_values['title'] ) ? $basic_values['title'] : $default_values['title'],
        'timestamp' => isset( $basic_values['timestamp'] ) && ! emtpy( $basic_values['timestamp'] ) ? $basic_values['timestamp'] : $default_values['timestamp'],
        'content'   => isset( $basic_values['content'] ) && ! emtpy( $basic_values['content'] ) ? $basic_values['content'] : $default_values['content'],
    ];

    ntdc_basic_fields_values_formatter( $basic_values );

    $basic_values = [
        'post_status'   => $basic_values['status'],
        'post_type'     => $basic_values['type'],
        'post_title'    => $basic_values['title'],
        'post_date'     => $basic_values['date'],
        'post_content'  => $basic_values['content']
    ];
}

/**
 * Handle wordpress post basic fields values
 * 
 * @param array $basic_values
 * 
 * @return void
 */
function ntdc_basic_fields_values_formatter( &$basic_values ) {
    // Switch case on basic field key
    // Sanitize and format
    // status, type, title, timestamp, content
}

/**
 * Add custom fields values to post arguments before post insertion
 * 
 * @param array &$post_args
 * @param array $meta_values
 * 
 * @return void
 */
function ntdc_add_custom_fields_values_to_post_args( &$post_args, $meta_values ) {
    ntdc_custom_fields_values_handler( $meta_values );
    foreach ( $meta_values as $meta_key => $meta_value) {
        $post_args['meta_input'][ $meta_key ] = $meta_value;
    }
}

/**
 * Handle custom fields values
 * 
 * @param array $custom_fields_values
 * 
 * @return void
 */
function ntdc_custom_fields_values_handler( &$meta_values ) {
    // Foreach meta_values
    // Switch case on field type
    // Format and sanitize
}