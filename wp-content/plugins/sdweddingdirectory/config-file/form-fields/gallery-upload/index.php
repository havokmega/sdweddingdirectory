<?php
/**
 *  SDWeddingDirectory - Fields
 *  -------------------
 */
if( ! class_exists( 'SDWeddingDirectory_Field_Gallery_Upload' ) && class_exists( 'SDWeddingDirectory_Form_Fields' ) ) {

    /**
     *  SDWeddingDirectory - Fields
     *  -------------------
     */
    class SDWeddingDirectory_Field_Gallery_Upload extends SDWeddingDirectory_Form_Fields{

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
            add_filter( 'sdweddingdirectory/field/gallery-upload', [ $this, 'field' ], absint( '10' ), absint( '1' ) );
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
                 *  Args
                 *  ----
                 */
                $args       =       wp_parse_args( $args, array(

                                        'div'                   =>      [],

                                        'row'                   =>      [],

                                        'column'                =>      [],

                                        'icon'                  =>      '<i class="fa fa-upload me-2"></i>',

                                        'button_text'           =>      esc_attr__( 'Add Gallery Images', 'sdweddingdirectory' ),

                                        'button_class'          =>      sanitize_html_class( 'btn-primary' ),

                                        'is_ajax'               =>      false,

                                        'post_id'               =>      '',

                                        'database_key'          =>      '',

                                        'image_size'            =>      esc_attr( 'thumbnail' ),

                                        'img_class'             =>      '',

                                        'placeholder'           =>      esc_url( parent:: placeholder( 'venue-gallery' ) ),

                                        'echo'                  =>      false,

                                        'alert_message'         =>      esc_attr__( 'Please Upload Gallery', 'sdweddingdirectory' ),

                                        'max_limit'             =>      '',

                                        'min_limit'             =>      '',

                                        'allowed_type'          =>      esc_attr( 'image' ),

                                        'data'                  =>      '',

                                        'row_class'             =>      'row-cols-xxl-6 row-cols-xl-6 row-cols-lg-3 row-cols-md-2 row-cols-sm-2 row-cols-2',

                                        'media_ids'             =>      '',

                                        'media_data'            =>      '',

                                        'unique_id'             =>      esc_attr( parent:: _rand() ),

                                        'input_class'           =>      sanitize_html_class( 'store_media_ids' ),

                                        'frame_title'           =>      esc_attr__( 'Add Gallery Images', 'sdweddingdirectory' ),

                                        'frame_button'          =>      esc_attr__( 'Insert Gallery', 'sdweddingdirectory' )

                                    ) );
                /**
                 *  Extract Args
                 *  ------------
                 */
                extract( $args );


                /**
                 *  Database ID
                 *  -----------
                 */
                if( ! empty( $database_key ) ){

                    /**
                     *  Get IDs
                     *  -------
                     */
                    $media_ids          =   empty( $post_id )

                                        ?   parent:: get_data( sanitize_key( $database_key ) )

                                        :   get_post_meta( absint( $post_id ), sanitize_key( $database_key ), true );

                    /**
                     *  Media Data
                     *  ----------
                     */
                    $media_data     =   self:: gallery_thumb( [

                                            'media_ids'     =>      $media_ids,

                                            'placeholder'   =>      $placeholder

                                        ] );
                }

                /**
                 *  Have Media ID Found ?
                 *  ---------------------
                 */
                if( ! empty( $media_ids )  &&  parent:: _have_media( $media_ids ) ){

                    $media_data       =     self:: gallery_thumb( [

                                                'media_ids'     =>      $media_ids,

                                                'placeholder'   =>      $placeholder

                                            ] );
                }

                /**
                 *  Before Div ?
                 *  ------------
                 */
                $data               .=      self:: _div_start_setup( $args );

                /**
                 *  Create Section
                 *  --------------
                 */
                $data               .=      sprintf(    '<div class="">

                                                            <div class="row %13$s" 

                                                                id="image_preview_id_%1$s" 

                                                                data-ajax-save="%2$s" 

                                                                data-post-id="%3$s" 

                                                                data-key="%14$s" 

                                                                data-value-update-text-id="%1$s_media_ids">%7$s</div>


                                                            <a  href="javascript:" id="button_id_%1$s" class="btn %8$s upload_multi_media"

                                                                data-ajax-save="%2$s" 

                                                                data-post-id="%3$s" 

                                                                data-key="%14$s" 

                                                                data-media-size="%4$s" 

                                                                data-preview="image_preview_id_%1$s" 

                                                                data-value-update-text-id="%1$s_media_ids"

                                                                data-frame-name="%16$s"

                                                                data-frame-button="%17$s"

                                                                data-allowed-type="%12$s">%5$s</a>


                                                            <input  class="media_section %15$s" type="hidden" 

                                                                    data-min-limit="%10$s" 

                                                                    data-max-limit="%11$s" 

                                                                    data-placeholder="%9$s" 

                                                                    id="%1$s_media_ids" 

                                                                    value="%6$s" />

                                                        </div>',

                                                        /**
                                                         *  1. Unique ID
                                                         *  ------------
                                                         */
                                                        esc_attr( $unique_id ),

                                                        /**
                                                         *  2. AJAX to update in database ?
                                                         *  -------------------------------
                                                         */
                                                        esc_attr( $is_ajax ),

                                                        /**
                                                         *  3. Login User Access Post ID to update media
                                                         *  --------------------------------------------
                                                         */
                                                        ( isset( $post_id ) && $post_id !== '' )

                                                        ?   absint( $post_id )

                                                        :   absint( parent:: post_id() ),

                                                        /**
                                                         *  4. Image Size
                                                         *  -------------
                                                         */
                                                        esc_attr( $image_size ),

                                                        /**
                                                         *  5. Button Text Here
                                                         *  -------------------
                                                         */
                                                        $icon . $button_text,

                                                        /**
                                                         *  6. Image ID
                                                         *  -----------
                                                         */
                                                        $media_ids,

                                                        /**
                                                         *  7. Get Gallery Images
                                                         *  ----------------------
                                                         */
                                                        $media_data,

                                                        /**
                                                         *  8. Get Class
                                                         *  -------------
                                                         */
                                                        esc_attr( $button_class ),

                                                        /**
                                                         *  9. Alert Message
                                                         *  ----------------
                                                         */
                                                        esc_attr( $alert_message ),

                                                        /**
                                                         *  10. Min Upload Limit
                                                         *  --------------------
                                                         */
                                                        $min_limit,

                                                        /**
                                                         *  11. Max Upload Limit
                                                         *  --------------------
                                                         */
                                                        $max_limit,

                                                        /**
                                                         *  12. Allowed Type
                                                         *  ----------------
                                                         */
                                                        esc_attr( $allowed_type ),

                                                        /**
                                                         *  13. Row Class
                                                         *  -------------
                                                         */
                                                        $row_class,

                                                        /**
                                                         *  14. Unique ID
                                                         *  -------------
                                                         */
                                                        $database_key,

                                                        /**
                                                         *  15. Input Class
                                                         *  ---------------
                                                         */
                                                        $input_class,

                                                        /**
                                                         *  16. Frame Name
                                                         *  --------------
                                                         */
                                                        esc_attr( $frame_title ),

                                                        /**
                                                         *  17. Frame Button
                                                         *  ----------------
                                                         */
                                                        esc_attr( $frame_button )
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
         *  Default Thumbs
         *  --------------
         */
        public static function gallery_thumb( $args = [] ){

            /**
             *  Have Args ?
             *  -----------
             */
            if( parent:: _is_array( $args ) ){

                /**
                 *  Extract Args
                 *  ------------
                 */
                extract( wp_parse_args( $args, [

                    'placeholder'       =>      esc_url( parent:: placeholder( 'venue-gallery' ) ),

                    'media_ids'         =>      '',

                    'handling'          =>      [],

                    'counter'           =>      absint( '6' ),

                    'demo_images'       =>      false

                ] ) );

                /**
                 *  Make sure Media IDs not empty!
                 *  ------------------------------
                 */
                if( empty( $media_ids ) ){

                    /**
                     *  Display Demo Images ?
                     *  ---------------------
                     */
                    if( $demo_images ){

                        /**
                         *  Display Demo ?
                         *  --------------
                         */
                        for ( $i = absint( $counter ); $i > absint( '0' ); $i-- ) {

                            /**
                             *  Collect
                             *  -------
                             */
                            $handling[]     =

                            sprintf('   <div class="col sdweddingdirectory_gallery_thumb">

                                            <div class="dash-categories">

                                                <div class="edit">

                                                    <a href="javascript:" class="sdweddingdirectory-remove-media" data-media-id="%2$s">

                                                        <i class="fa fa-trash"></i>

                                                    </a>

                                                </div>

                                                <img src="%1$s" data-media-id="%2$s" alt="%3$s" />

                                            </div>

                                        </div>',

                                /**
                                 *  1. Placeholder
                                 *  --------------
                                 */
                                esc_url( $placeholder ),

                                /**
                                 *  2. Random Value
                                 *  ---------------
                                 */
                                esc_attr( parent:: _rand() ),

                                /**
                                 *  3. Brand Name
                                 *  -------------
                                 */
                                esc_attr( get_bloginfo( 'name' ) )
                            );
                        }
                    }
                }

                /**
                 *  Have Media IDs ?
                 *  ----------------
                 */
                else{

                    /**
                     *  Media list [ Coma to Array ]
                     *  ----------------------------
                     */
                    $media_list     =       parent:: _coma_to_array( $media_ids );

                    /**
                     *  Make sure list is array
                     *  -----------------------
                     */
                    if( parent:: _is_array( $media_list ) ){

                        /**
                         *  Collection Start
                         *  ----------------
                         */
                        foreach ( $media_list as $key ) {

                            /**
                             *  Media Check 
                             *  -----------
                             */
                            if ( parent:: _have_media( $key ) ) {

                                /**
                                 *  Collect
                                 *  -------
                                 */
                                $handling[]     =

                                sprintf(   '<div class="col sdweddingdirectory_gallery_thumb">

                                                <div class="dash-categories">

                                                    <div class="edit">

                                                        <a href="javascript:" class="sdweddingdirectory-remove-media" data-media-id="%2$s">

                                                            <i class="fa fa-trash"></i>

                                                        </a>

                                                    </div>

                                                    <img src="%1$s" data-media-id="%2$s" src="%3$s" />

                                                </div>

                                            </div>',

                                            /**
                                             *  1. Get Media ID to Media SRC
                                             *  ----------------------------
                                             */
                                            parent:: _have_media( $key )

                                            ?   apply_filters( 'sdweddingdirectory/media-data', [

                                                    'media_id'         =>       absint( $key ),

                                                    'image_size'       =>       esc_attr( 'thumbnail' ),

                                                ] )

                                            :   esc_url( $placeholder ),

                                            /**
                                             *  2. Media ID
                                             *  -----------
                                             */
                                            absint( $key ),

                                            /**
                                             *  4. Image Alt
                                             *  ------------
                                             */
                                            esc_attr( get_bloginfo( 'name' ) )
                                );
                            }
                        }
                    }
                }

                /**
                 *   Return Data
                 *   -----------
                 */
                return          implode( '',  $handling );
            }
        }
    }

    /**
     *  SDWeddingDirectory - Fields
     *  -------------------
     */
    SDWeddingDirectory_Field_Gallery_Upload::get_instance();
}