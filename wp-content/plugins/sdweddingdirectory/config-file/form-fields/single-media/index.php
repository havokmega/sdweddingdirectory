<?php
/**
 *  SDWeddingDirectory - Fields
 *  -------------------
 */
if( ! class_exists( 'SDWeddingDirectory_Field_Single_Media' ) && class_exists( 'SDWeddingDirectory_Form_Fields' ) ) {

    /**
     *  SDWeddingDirectory - Fields
     *  -------------------
     */
    class SDWeddingDirectory_Field_Single_Media extends SDWeddingDirectory_Form_Fields{

        /**
         *  Member Variable
         *  ---------------
         */
        private static $instance;

        /**
         *  Initiator
         *  ---------
         */
        public static function get_instance() {

            if( ! isset( self::$instance ) ){

                self::$instance = new self;
            }

            return  self::$instance;
        }

        /**
         *  Construct
         *  ---------
         */
        public function __construct() {

            /**
             *  Multiple Selection Field
             *  ------------------------
             */
            add_filter( 'sdweddingdirectory/field/single-media', [ $this, 'field' ], absint( '10' ), absint( '1' ) );
        }

        /**
         *  Multiple Selection Field
         *  ------------------------
         */
        public static function field( $args = [] ){

            /**
             *  Have Data ?
             *  -----------
             */
            if( parent:: _is_array( $args ) ){

                /**
                 *  Default Set Parameter
                 *  ---------------------
                 */
                $args       =       wp_parse_args( $args, [

                                        'div'                   =>      [],

                                        'row'                   =>      [],

                                        'column'                =>      [],

                                        'data'                  =>      '',

                                        'icon'                  =>      '<i class="fa fa-pencil"></i>',

                                        'is_ajax'               =>      false,

                                        'post_id'               =>      absint( parent:: post_id() ),

                                        'database_key'          =>      '',

                                        'media_id'              =>      '',

                                        'image_size'            =>      esc_attr( 'full' ),

                                        'default_img'           =>      esc_url( parent:: placeholder( 'venue-banner' ) ),

                                        'echo'                  =>      false,

                                        'alert_message'         =>      esc_attr__( 'Upload Image', 'sdweddingdirectory' ),

                                        'icon_layout'           =>      absint( '1' ),

                                        'image_class'           =>      'rounded',

                                        'unique_id'             =>      esc_attr( parent:: _rand() ),

                                        'media_src'             =>      '',

                                        'input_class'           =>      '',

                                        'allowed_type'          =>      esc_attr( 'image' ),

                                        'frame_title'           =>      esc_attr__( 'Select Image', 'sdweddingdirectory' ),

                                        'frame_button'          =>      esc_attr__( 'Insert Image', 'sdweddingdirectory' ),

                                        'extra_link_update'     =>      []

                                    ] );
                /**
                 *  Extract Args
                 *  ------------
                 */
                extract( $args );

                /**
                 *  Have Object
                 *  -----------
                 */
                $image_obj       =       apply_filters( 'sdweddingdirectory/image-size-id/data', $image_size );

                /**
                 *  Before Setup Div Structure 
                 *  --------------------------
                 */
                $data           .=      parent:: _div_start_setup( $args );

                /**
                 *  Database ID
                 *  -----------
                 */
                if( ! empty( $database_key ) ){

                    $media_id       =       get_post_meta( absint( $post_id ), sanitize_key( $database_key ), true );
                }

                /**
                 *  Have Media ID Found ?
                 *  ---------------------
                 */
                if( ! empty( $media_id )  &&  parent:: _have_media( $media_id ) ){

                    $media_src       =      apply_filters(  'sdweddingdirectory/media-data', [

                                                'media_id'      =>      absint( $media_id ),

                                                'image_size'    =>      esc_attr( $image_size  ),

                                                'default'       =>      $default_img

                                            ] );
                }

                /**
                 *  Default Image
                 *  -------------
                 */
                else{

                    $media_src       =      $default_img;
                }


                /**
                 *  Setup Field
                 *  -----------
                 */
                $data           .=      sprintf(    '<div class="position-relative %1$s">

                                                        <img class="w-100 %2$s" id="img_preview_id_%3$s" src="%4$s">

                                                        <div class="custom-file">

                                                            <a  id="button_id_%3$s"  href="javascript:" class="upload_single_media custom-file-label"

                                                                data-key="%5$s"

                                                                data-ajax-save="%6$s" 

                                                                data-post-id="%7$s" 

                                                                data-media-size="%8$s" 

                                                                data-height="%9$s" 

                                                                data-width="%10$s"

                                                                data-preview="img_preview_id_%3$s" 

                                                                data-value-update-text-id="%3$s_media_ids" 

                                                                data-allowed-type="%15$s"

                                                                data-frame-name="%16$s"

                                                                data-frame-button="%17$s"

                                                                data-extra-link-update="%18$s"

                                                                > %11$s</a>

                                                            <input  class="media_section %14$s" type="hidden" 

                                                                    data-placeholder="%12$s"  id="%3$s_media_ids" value="%13$s" />

                                                        </div>

                                                    </div>',

                                                    /**
                                                     *  1. Icon Style Wise Class
                                                     *  ------------------------
                                                     */
                                                    self:: icon_style_class( $icon_layout ),

                                                    /**
                                                     *  2. Have Image Class ?
                                                     *  ---------------------
                                                     */
                                                    $image_class,

                                                    /**
                                                     *  3. Unique ID
                                                     *  ------------
                                                     */
                                                    $unique_id,

                                                    /**
                                                     *  4. Image Link
                                                     *  -------------
                                                     */
                                                    $media_src,

                                                    /**
                                                     *  5. Data Base Meta Key
                                                     *  ---------------------
                                                     */
                                                    esc_attr( $database_key ),

                                                    /**
                                                     *  6. AJAX to update in database ?
                                                     *  -------------------------------
                                                     */
                                                    esc_attr( $is_ajax ),

                                                    /**
                                                     *  7. Login User Access Post ID to update media
                                                     *  --------------------------------------------
                                                     */
                                                    absint( $post_id ),

                                                    /**
                                                     *  8. Image Size
                                                     *  -------------
                                                     */
                                                    esc_attr( $image_size ),

                                                    /**
                                                     *  9. Image Height
                                                     *  ---------------
                                                     */
                                                    parent:: _is_array( $image_obj )

                                                    ?   $image_obj[ 'height' ]

                                                    :   '',

                                                    /**
                                                     *  10. Image Width
                                                     *  ---------------
                                                     */
                                                    parent:: _is_array( $image_obj )

                                                    ?   $image_obj[ 'width' ]

                                                    :   '',

                                                    /**
                                                     *  11. Icon
                                                     *  --------
                                                     */
                                                    $icon,

                                                    /**
                                                     *  12. Placeholder
                                                     *  ---------------
                                                     */
                                                    esc_attr( $alert_message ),

                                                    /**
                                                     *  13. Media ID
                                                     *  ------------
                                                     */
                                                    $media_id,

                                                    /**
                                                     *  14. Input Class
                                                     *  ---------------
                                                     */
                                                    $input_class,

                                                    /**
                                                     *  15. Allowed Type
                                                     *  ----------------
                                                     */
                                                    esc_attr( $allowed_type ),

                                                    /**
                                                     *  16. Frame Name
                                                     *  --------------
                                                     */
                                                    esc_attr( $frame_title ),

                                                    /**
                                                     *  17. Frame Button
                                                     *  ----------------
                                                     */
                                                    esc_attr( $frame_button ),

                                                    /**
                                                     *  18. Have Extra Replace Fields ?
                                                     *  -------------------------------
                                                     */
                                                    parent:: _is_array( $extra_link_update )

                                                    ?   esc_html( json_encode( $extra_link_update ) )

                                                    :   ''
                                        );

                /**
                 *  After Setup Div Structure 
                 *  -------------------------
                 */
                $data           .=      parent:: _div_end_setup( $args );

                /**
                 *  Print the Code
                 *  --------------
                 */
                if( $echo ){

                    print       $data;
                }

                /**
                 *  Return Code
                 *  -----------
                 */
                else{

                    return      $data;
                }
            }
        }

        /**
         *  Icon Layout wise Class
         *  ----------------------
         */
        public static function icon_style_class( $icon_layout = '' ){

            /**
             *  Is is Empty!
             *  ------------
             */
            if( empty( $icon_layout ) || $icon_layout == absint( '1' ) ){

                return      sanitize_html_class( 'upload-icon-style-one' );
            }

            /**
             *  Layout is 2
             *  -----------
             */
            elseif( $icon_layout == absint( '2' ) ){

                return      sanitize_html_class( 'upload-icon-style-two' );
            }

            /**
             *  Layout is 3
             *  -----------
             */
            elseif( $icon_layout == absint( '3' ) ){

                return      sanitize_html_class( 'upload-icon-style-three' );
            }
        }
    }

    /**
     *  SDWeddingDirectory - Fields
     *  -------------------
     */
    SDWeddingDirectory_Field_Single_Media::get_instance();
}