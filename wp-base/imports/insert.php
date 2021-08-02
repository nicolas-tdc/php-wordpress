<?php

/**
 * Insert attachment linked to $post_id from file url
 * Accepts only jpeg and pdf files
 * 
 * @param string $file_url
 * @param string $file_mimetype
 * @param string $file_name
 * @param int $post_id
 * @param bool $pdf_to_img
 * 
 * @return mixed
 * 
 */
function ntdc_insert_attachment( $post_id, $file_name, $file_url, $file_mimetype, $pdf_to_img = false ){

    $file_datas = ntdc_format_file_datas( $file_name, $file_url, $file_mimetype );
    $file = $file_datas['file_dir_path'] . '/' . $file_datas['name'] . '.' . $file_datas['ext'];
    file_put_contents( $file, $file_datas['contents'] );

    // Transform first page of PDF to JPEG
    if( $pdf_to_img === true && $file_datas['mimetype'] === 'application/pdf' ) {
        $file = ntdc_transform_pdf_to_img( $file, $file_datas['file_dir_path'], $file_datas['name'] );
    }

    // Insert attachment
    $attachment = [
        'post_mime_type' => $file_datas['mimetype'],
        'post_parent'    => $post_id,
        'post_title'     => $file_datas['name'],
        'post_content'   => '',
        'post_status'    => 'inherit'
    ];
    $attach_id = wp_insert_attachment( $attachment, $file, $post_id );

    // Generate attachment metadata
    if ( ! is_wp_error( $attach_id ) ) {
        require_once( ABSPATH . 'wp-admin/includes/image.php' );
        $attach_data = wp_generate_attachment_metadata( $attach_id, $file );
        wp_update_attachment_metadata( $attach_id, $attach_data );

        return $attach_id;
    }

    return false;
}

/**
 * Format file datas for insertion
 * 
 * @param string $file_name
 * @param string $file_url
 * @param string $file_mimetype
 * 
 * @return array $file_datas
 * 
 */
function ntdc_format_file_datas( $file_name, $file_url, $file_mimetype ) {

    $mimetype_ext = [
        'application/pdf' => 'pdf',
        'image/jpeg'      => 'jpeg',
    ];
    $upload_dir = wp_upload_dir();
    $file_dir = wp_mkdir_p( $upload_dir['path'] ) ? 'path' : 'basedir';

    $file_datas = [
        'name'          => sanitize_file_name( $file_name ),
        'contents'      => file_get_contents( $file_url ),
        'mimetype'      => $file_mimetype,
        'ext'           => $mimetype_ext[ $file_mimetype ],
        'file_dir_path' => rtrim( $upload_dir[ $file_dir ], '/' ),
    ];

    return $file_datas;

}

/**
 * Transform the first page of a PDF file in jpg file
 * Requires image-magick php extension
 * 
 * @param string &$file
 * @param string $file_path
 * @param string $file_name
 * 
 */
function ntdc_transform_pdf_to_img( &$file, $file_dir_path, $file_name ) {

    $pdf_thumbnail = new imagick();
    $pdf_thumbnail->setResolution( 400, 400 );
    $pdf_thumbnail->readImage( $file . '[0]' );
    $pdf_thumbnail->setImageFormat( 'jpg' );

    unlink( $file );

    $file = $file_dir_path . '/' . $file_name . '.jpg';
    $pdf_thumbnail->writeImage( $file );

}