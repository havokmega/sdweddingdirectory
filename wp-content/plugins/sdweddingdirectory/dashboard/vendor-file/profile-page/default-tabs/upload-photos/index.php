<?php
/**
 *  SDWeddingDirectory - Vendor Profile Tab: Upload Photos
 *  ------------------------------------------------------
 */
if( ! class_exists( 'SDWeddingDirectory_Vendor_Profile_Upload_Photos_Tab' ) && class_exists( 'SDWeddingDirectory_Vendor_Profile_Database' ) ){

    class SDWeddingDirectory_Vendor_Profile_Upload_Photos_Tab extends SDWeddingDirectory_Vendor_Profile_Database{

        private static $instance;

        public static function get_instance() {

            if ( null == self::$instance ) {

                self::$instance = new self();
            }

            return self::$instance;
        }

        public static function tab_name(){

            return esc_attr__( 'Upload Photos', 'sdweddingdirectory' );
        }

        public function __construct() {

            add_filter( 'sdweddingdirectory/vendor-profile/tabs', function( $args = [] ){

                return array_merge( $args, [

                    'upload-photos' => [

                        'active'    => false,

                        'id'        => esc_attr( parent:: _rand() ),

                        'name'      => esc_attr( self:: tab_name() ),

                        'callback'  => [ 'class' => __CLASS__, 'member' => 'tab_content' ],

                        'create_form' => [

                            'form_before'   => '',

                            'form_after'    => '',

                            'form_id'       => esc_attr( 'sdweddingdirectory_vendor_upload_photos' ),

                            'form_class'    => '',

                            'button_before' => '',

                            'button_after'  => '',

                            'button_id'     => esc_attr( 'vendor_upload_photos_btn' ),

                            'button_class'  => '',

                            'button_name'   => esc_attr__( 'Save Photos', 'sdweddingdirectory' ),

                            'security'      => esc_attr( 'vendor_upload_photos_security' ),
                        ]
                    ]

                ] );

            }, absint( '15' ) );
        }

        public static function tab_content(){

            $post_id = absint( parent:: post_id() );

            /**
             *  Section Info
             */
            parent:: create_section( array(

                'field' => array(

                    'field_type' => esc_attr( 'info' ),

                    'class'      => sanitize_html_class( 'mb-0' ),

                    'title'      => esc_attr( self:: tab_name() ),
                )

            ) );

            ?>
            <div class="card-body">
                <p class="text-muted mb-3">
                    <?php printf(
                        esc_html__( 'Upload up to %d photos to your gallery. Drag images to reorder.', 'sdweddingdirectory' ),
                        20
                    ); ?>
                </p>
                <div class="sdwd-photo-count-bar mb-3">
                    <?php
                    $gallery_meta = get_post_meta( $post_id, sanitize_key( 'venue_gallery' ), true );
                    $current_ids  = is_string( $gallery_meta ) && $gallery_meta !== ''
                                    ? array_filter( array_map( 'absint', explode( ',', $gallery_meta ) ) )
                                    : [];
                    $current_count = count( $current_ids );
                    ?>
                    <span class="sdwd-photo-counter">
                        <strong class="sdwd-photo-count-num"><?php echo absint( $current_count ); ?></strong> / 20
                        <?php esc_html_e( 'photos used', 'sdweddingdirectory' ); ?>
                    </span>
                </div>
            </div>
            <?php

            /**
             *  Gallery Upload Field
             */
            parent:: create_section( array(

                'div' => array(

                    'id'    => '',

                    'class' => 'card-body text-center',

                    'start' => true,

                    'end'   => true,
                ),

                'row' => array(

                    'start' => true,

                    'end'   => true,
                ),

                'column' => array(

                    'grid'  => absint( '12' ),

                    'start' => true,

                    'end'   => true,

                    'class' => sanitize_html_class( 'text-center' )
                ),

                'field' => array(

                    'field_type'   => esc_attr( 'gallery_img_upload' ),

                    'database_key' => esc_attr( 'venue_gallery' ),

                    'image_size'   => esc_attr( 'thumbnail' ),

                    'post_id'      => absint( $post_id ),

                    'row_class'    => 'row-cols-xxl-6 row-cols-xl-6 row-cols-lg-3 row-cols-md-2 row-cols-sm-2 row-cols-2',

                    'input_class'  => sanitize_html_class( 'vendor_gallery_image' ),

                    'button_class' => 'btn-primary btn-sm'
                )

            ) );
        }
    }

    SDWeddingDirectory_Vendor_Profile_Upload_Photos_Tab::get_instance();
}
