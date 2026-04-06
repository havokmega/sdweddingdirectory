<?php
/**
 *  SDWeddingDirectory WordPress Media Uploader
 *  -----------------------------------
 */
if( ! class_exists( 'SDWeddingDirectory_Media_Uploader' ) && class_exists( 'SDWeddingDirectory_Config' ) ){

    /**
     *  Load Couple Dashboard
     *  ---------------------
     */
    class SDWeddingDirectory_Media_Uploader extends SDWeddingDirectory_Config{

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
          
            if ( ! isset( self::$instance ) ) {

                self::$instance = new self;
            }

            return self::$instance;
        }

        /**
         *  Construct
         *  ---------
         */
        public function __construct() {

            /**
             *  1. Couple Dashboard Load Scripts
             *  --------------------------------
             */
            add_action( 'wp_enqueue_scripts', [$this, 'sdweddingdirectory_script'], absint( '9999' ) );

            add_filter( 'ajax_query_attachments_args', [$this, 'filter_media'] );

            add_filter( 'sdweddingdirectory/localize_script', [ $this, 'localize_media_security' ] );

            /**
             *  Fire AJAX action
             *  ----------------
             */
            add_action( 'wp_ajax_sdweddingdirectory_update_single_media',  [$this, 'sdweddingdirectory_update_single_media'] );
        }

        /**
         *  Add nonce for media update AJAX calls
         *  ------------------------------------
         */
        public static function localize_media_security( $args = [] ){

            if( ! parent:: _is_array( $args ) ){

                $args = [];
            }

            $args['sdweddingdirectory_media_security'] = wp_create_nonce( 'sdweddingdirectory_media_security' );

            return $args;
        }

        /**
         *  Filter Media
         *  ------------
         */
        public static function filter_media( $query ){

            /**
             *  Login user will show own uploaded post
             *  --------------------------------------
             */
            $_is_admin      =   is_user_logged_in() && current_user_can('administrator');

            $_is_vendor     =   is_user_logged_in() && parent:: is_vendor();

            $_is_couple     =   is_user_logged_in() && parent:: is_couple();

            /**
             *  Is Couple or Vendor + Is Login ?
             *  --------------------------------
             */
            if( ( $_is_couple || $_is_vendor ) && ! $_is_admin ){

                $query[ 'author' ]  =   parent:: author_id();
            }

            return $query;
        }

        /**
         *  1. Couple Dashboard Load Scripts
         *  --------------------------------
         */
        public static function sdweddingdirectory_script(){

            /**
             *  Have Collection ?
             *  -----------------
             */
            $_request_collection    =   apply_filters( 'sdweddingdirectory/enable-script/media-upload', [] );

            /**
             *  Have Request ?
             *  --------------
             */
            if( parent:: _is_array( $_request_collection ) && in_array( 'true', $_request_collection ) ){

                /**
                 *  WordPress Media script load
                 *  ---------------------------
                 */
                wp_enqueue_media();

                /**
                 *  Load Script
                 *  -----------
                 */
                wp_enqueue_script( 

                    /**
                     *  1. File Name
                     *  ------------
                     */
                    esc_attr( sanitize_title( __CLASS__ ) ),

                    /**
                     *  2. File Path
                     *  ------------
                     */
                    esc_url(   plugin_dir_url( __FILE__ )   .   'script.js'   ),

                    /**
                     *  3. Load After "JQUERY" Load
                     *  ---------------------------
                     */
                    array( 'jquery', 'toastr' ),

                    /**
                     *  4. Bootstrap - Library Version
                     *  ------------------------------
                     */
                    esc_attr( parent:: _file_version( plugin_dir_path( __FILE__ )   .   'script.js' ) ),

                    /**
                     *  5. Load in Footer
                     *  -----------------
                     */
                    true
                );
            }
        }

        /**
         *  2. Update Single Picture in Database
         *  ------------------------------------
         */
        public static function sdweddingdirectory_update_single_media(){

            global $post, $wp_query;

            /**
             *  Login + nonce checks
             *  --------------------
             */
            if( ! is_user_logged_in() ){

                wp_send_json_error( [
                    'message'   =>  esc_html__( 'Not logged in.', 'sdweddingdirectory' ),
                    'notice'    =>  absint( '0' ),
                ] );
            }

            $_security   = isset( $_POST['security'] ) ? sanitize_text_field( wp_unslash( $_POST['security'] ) ) : '';

            if( empty( $_security ) || ! wp_verify_nonce( $_security, 'sdweddingdirectory_media_security' ) ){

                wp_send_json_error( [
                    'message'   =>  esc_html__( 'Invalid security token.', 'sdweddingdirectory' ),
                    'notice'    =>  absint( '0' ),
                ] );
            }

            $_post_id    = isset( $_POST[ 'post_id' ] ) && $_POST[ 'post_id' ] !== ''

                            ?   absint( $_POST[ 'post_id' ] )

                            :   absint( parent:: post_id() );

            $post_data = get_post( absint( $_post_id ) );

            $linked_user_id = absint( get_post_meta( absint( $_post_id ), sanitize_key( 'user_id' ), true ) );

            $owns_profile_post = ! empty( $post_data ) && (

                                    absint( $post_data->post_author ) === absint( get_current_user_id() )

                                    || $linked_user_id === absint( get_current_user_id() )
                                );

            if( empty( $_post_id ) || ( ! current_user_can( 'edit_post', absint( $_post_id ) ) && ! $owns_profile_post ) ){

                wp_send_json_error( [
                    'message'   =>  esc_html__( 'Unauthorized.', 'sdweddingdirectory' ),
                    'notice'    =>  absint( '0' ),
                ] );
            }

            $_condition_1   =   isset( $_POST[ 'image_key' ] ) && $_POST[ 'image_key' ] !== '';

            $_condition_2   =   isset( $_POST[ 'image_id' ] )  && $_POST[ 'image_id' ]  !== '';

            /**
             *  Check the Condition
             *  -------------------
             */
            if( $_condition_1 && $_condition_2 ){

                update_post_meta(

                    absint( $_post_id ),

                    sanitize_key( wp_unslash( $_POST[ 'image_key' ] ) ),

                    absint( $_POST[ 'image_id' ] )
                );

                die( json_encode( array(

                    'message'   => esc_html__( 'Media Uploaded Successfully!', 'sdweddingdirectory' ),

                    'notice'    => absint( '1' ),

                ) ) );

            }else{

                die( json_encode( array(

                    'message'   => esc_html__( 'Error..', 'sdweddingdirectory' ),

                    'notice'    => absint( '2' )

                ) ) );
            }
        }

        /**
         *  3. Single Media Upload Condition Div
         *  ------------------------------------
         */
        public static function single_media_upload_div_structure(){

            /**
             *  Media Preview DIV
             *  -----------------
             */
            ?><div id="sdweddingdirectory_couple_placeholder"></div><?php

            /**
             *  Button Attributes
             *  -----------------
             *
             *  data-ajax-save                 = true / false
             *  
             *  data-post-id                   = this attribute to get login user post id.
             *
             *  data-key                       = backend database meta key name they store direct on this ID.
             *
             *  data-media-size                = thumbnails name they CROP image as mention add_image_size function
             *
             *  data-preview-id                = after media select they showing preview on this ID
             *
             *  data-value-update-text-id      = after media select this id have media attachments with (,) comma seperaters.
             *
             *  ----------------------------------------------------------------
             *  This class to showing media frame :   ** .upload_single_media **
             *  ----------------------------------------------------------------
             */

            printf( '<a href="javascript:" id="%1$s" class="btn btn-outline-white upload_single_media"

                    data-ajax-save="%2$s" 

                    data-post-id="%3$s" 

                    data-key="%4$s" 

                    data-media-size="%5$s" 

                    data-preview="%6$s" 

                    data-value-update-text-id="%7$s" 

                    ><i class="fa fa-camera"></i> %8$s</a>',

                /**
                 *  1. Button ID
                 *  ------------
                 */
                esc_attr( '_BUTTON_ID_' ),

                /**
                 *  2. AJAX to update in database ?
                 *  -------------------------------
                 */
                true,

                /**
                 *  3. Login User Access Post ID to update media
                 *  --------------------------------------------
                 */
                absint( '0' ),

                /**
                 *  4. Image Preview ID
                 *  -------------------
                 */
                esc_attr( '_DATABASE_META_KEY_' ),

                /**
                 *  5. Image Size
                 *  -------------
                 */
                esc_attr( '_IMAGE_SIZE_HERE_' ),

                /**
                 *  6. Image Preview Showing on Thid ID
                 *  -----------------------------------
                 */
                esc_attr( '_IMAGE_PREVIEW_ON_THIS_ID_' ),

                /**
                 *  7. Media IDs Store HERE
                 *  -----------------------
                 */
                esc_attr( '_MEDIA_ATTACHEMENT_TEXT_IDS_HERE_' ),

                /**
                 *  8. Button Text Here
                 *  -------------------
                 */
                esc_attr__( 'Button Text Here', 'sdweddingdirectory' )
            );
        }

        /**
         *  4. Multi Media Upload Condition Div
         *  ------------------------------------
         */
        public static function multi_media_upload_div_structure(){

            /**
             *  Media Preview DIV
             *  -----------------
             */
            ?><div id="sdweddingdirectory_multiple_media"></div><?php

            /**
             *  Button Attributes
             *  -----------------
             *
             *  data-key = backend database meta key name they store direct on this ID.
             *  data-media-size                = thumbnails name they CROP image as mention add_image_size function
             *  data-preview                   = after media select they showing preview on this ID
             *  CLASS TO WORKING               = .upload_multi_media
             *
             *  ----------------
             */

            printf( '<a href="javascript:" data-key="%5$s" data-media-size="%4$s" data-preview="%1$s" id="%3$s" class="btn btn-outline-white upload_multi_media"><i class="fa fa-camera"></i> %2$s</a>',

                /**
                 *  1. Image Preview ID
                 *  -------------------
                 */
                esc_attr( 'sdweddingdirectory_multiple_media' ),

                /**
                 *  2. Button Text
                 *  --------------
                 */
                esc_html__( 'Upload Gallery', 'sdweddingdirectory' ),

                /**
                 *  3. Button ID
                 *  ------------
                 */
                esc_attr( 'upload_gallery_media' ),

                /**
                 *  4. Image Size
                 */
                esc_attr( 'sdweddingdirectory_couple_dashboard_100x100' ),

                /**
                 *  5. In Database upload image id
                 *  ------------------------------
                 */
                esc_attr( 'bride_gallery_ids' )
            );
        }
    }

    /**
     *  Media Upload Object
     *  -------------------
     */
    SDWeddingDirectory_Media_Uploader::get_instance();   
}
