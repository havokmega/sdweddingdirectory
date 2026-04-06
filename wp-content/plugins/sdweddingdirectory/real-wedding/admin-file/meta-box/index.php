<?php
/**
 *  -----------
 *  Option Tree
 *  -----------
 *
 *  @link( https://github.com/valendesigns/option-tree )
 *
 *  @link( https://github.com/valendesigns/option-tree-theme/blob/master/inc/meta-boxes.php, link)
 *
 *  ----------------------------
 *  SDWeddingDirectory Real Wedding Meta
 *  ----------------------------
 */
if( ! class_exists( 'SDWeddingDirectory_Real_Wedding_Meta' ) && class_exists( 'SDWeddingDirectory_Config' ) ){

    /**
     *  -------------------------------
     *  SDWeddingDirectory Real Wedding Feature
     *  -------------------------------
     */
    class SDWeddingDirectory_Real_Wedding_Meta extends SDWeddingDirectory_Config{

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
             *  Register meta box
             *  -----------------
             */
            add_filter( 'sdweddingdirectory/meta', array($this, 'real_wedding_meta_box' ), absint( '10' ) );

            /**
             *  Register meta box
             *  -----------------
             */
            add_filter( 'sdweddingdirectory/meta', array($this, 'real_wedding_side_meta_box' ), absint( '20' ) );
        }

        /**
         *  SDWeddingDirectory - User Configuration Meta
         *  ------------------------------------
         */
        public static function real_wedding_side_meta_box($args = [] ) {

            $new_args = array(

                'id'        =>  esc_attr( 'sdweddingdirectory_real_wedding_overview_settings' ),

                'title'     =>  esc_attr__( 'Summary', 'sdweddingdirectory-real-wedding' ),

                'pages'     =>  [ 'real-wedding' ],

                'context'   =>  esc_attr( 'side' ),

                'priority'  =>  esc_attr( 'high' ),

                'fields'    =>  array(

                    array(

                        'label'     =>  esc_attr__( 'Page Visit', 'sdweddingdirectory-real-wedding' ),

                        'id'        =>  esc_attr( 'page_visit' ),

                        'type'      =>  esc_attr( 'text' ),
                    ),
                ),
            );

            return array_merge($args, array($new_args));
        }

        /**
         *  Meta
         *  ----
         */
        public static function real_wedding_meta_box( $args = [] ){

            /**
             *  Add New Meta Args
             *  -----------------
             */
            $_new_args  =   array(

                'id'            =>      esc_attr( 'sdweddingdirectory_couple_realwedding_metabox' ),

                'title'         =>      esc_attr__( 'Real Wedding', 'sdweddingdirectory-real-wedding' ),

                'pages'         =>      array( 'real-wedding' ),

                'context'       =>      esc_attr( 'normal' ),

                'priority'      =>      esc_attr( 'high' ),

                'fields'        =>      array(

                    array(

                        'label'         =>      esc_attr__( 'Couple Information', 'sdweddingdirectory-real-wedding' ),

                        'id'            =>      esc_attr( 'realwedding_couple_information' ),

                        'type'          =>      esc_attr( 'tab' ),
                    ),

                    array(

                        'id'            =>      sanitize_key( 'bride_first_name' ),

                        'label'         =>      esc_attr__( 'Bride First Name', 'sdweddingdirectory-real-wedding' ),

                        'type'          =>      esc_attr( 'text' ),
                    ),

                    array(

                        'id'            =>      sanitize_key( 'bride_last_name' ),

                        'label'         =>      esc_attr__( 'Bride Last Name', 'sdweddingdirectory-real-wedding' ),

                        'type'          =>      esc_attr( 'text' ),

                    ),

                    array(

                        'id'            =>      sanitize_key( 'groom_first_name' ),

                        'label'         =>      esc_attr__( 'Groom  First Name', 'sdweddingdirectory-real-wedding' ),

                        'type'          =>      esc_attr( 'text' ),

                    ),

                    array(

                        'id'            =>      sanitize_key( 'groom_last_name' ),

                        'label'         =>      esc_attr__( 'Groom Last Name', 'sdweddingdirectory-real-wedding' ),

                        'type'          =>      esc_attr( 'text' ),

                    ),

                    array(

                        'label'         =>      esc_attr__( 'Vendors Credit', 'sdweddingdirectory-real-wedding' ),

                        'id'            =>      esc_attr( 'vendor_credit_tab' ),

                        'type'          =>      esc_attr( 'tab' ),
                    ),
                        
                    array(

                        'id'            =>      sanitize_key( 'our_website_vendor_credits' ),

                        'label'         =>      esc_html__( 'Website Vendors Credit', 'sdweddingdirectory-real-wedding' ),

                        'desc'          =>      esc_attr__( 'Our Website Vendor Team Credit', 'sdweddingdirectory-real-wedding' ),

                        'type'          =>      esc_attr( 'text' ),
                    ),

                    array(

                        'id'            =>      sanitize_key( 'out_side_vendor_credits' ),

                        'type'          =>      esc_attr( 'list-item' ),

                        'operator'      =>      esc_attr( 'or' ),

                        'choices'       =>      [],

                        'settings'      =>      array(

                            array(

                                'id'            =>      sanitize_key( 'category' ),

                                'label'         =>      esc_attr__( 'Category name', 'sdweddingdirectory-real-wedding' ),

                                'type'          =>      esc_attr( 'text' ),
                            ),

                            array(

                                'id'            =>      sanitize_key( 'company' ),

                                'label'         =>      esc_attr__( 'Vendor Business name', 'sdweddingdirectory-real-wedding' ),

                                'type'          =>      esc_attr( 'text' ),
                            ),

                            array(

                                'id'            =>      sanitize_key( 'website' ),

                                'label'         =>      esc_attr__( 'Vendor Website Link', 'sdweddingdirectory-real-wedding' ),

                                'type'          =>      esc_attr( 'text' ),
                            ),
                        ),
                    ),

                    array(

                        'label'         =>      esc_attr__( 'Media Files', 'sdweddingdirectory-real-wedding' ),

                        'id'            =>      esc_attr( 'realwedding_other_information' ),

                        'type'          =>      esc_attr( 'tab' ),
                    ),

                    array(
                        
                        'id'            =>      sanitize_key( 'page_header_banner' ),
                        
                        'label'         =>      esc_attr__( 'Page Header Banner Image', 'sdweddingdirectory-real-wedding' ),
                        
                        'desc'          =>      esc_attr__( 'Realwedding page header banner image media id information', 'sdweddingdirectory-real-wedding' ),
                        
                        'type'          =>      esc_attr( 'upload' ),
                        
                        'class'         =>      sanitize_html_class( 'ot-upload-attachment-id' ),
                    ),

                    array(
                        
                        'id'            =>      sanitize_key( 'realwedding_gallery' ),
                        
                        'label'         =>      esc_attr__( 'Realwedding Gallerys', 'sdweddingdirectory-real-wedding' ),
                        
                        'type'          =>      esc_attr( 'gallery' ),
                    ),

                    array(
                        
                        'id'            =>      esc_attr( 'bride_image' ),
                        
                        'label'         =>      esc_attr__( 'Bride Image', 'sdweddingdirectory-real-wedding' ),
                        
                        'type'          =>      esc_attr( 'upload' ),
                        
                        'class'         =>      sanitize_html_class( 'ot-upload-attachment-id' ),
                    ),

                    array(
                        
                        'id'            =>      esc_attr( 'groom_image' ),
                        
                        'label'         =>      esc_attr__( 'Groom Image', 'sdweddingdirectory-real-wedding' ),
                        
                        'type'          =>      esc_attr( 'upload' ),
                        
                        'class'         =>      sanitize_html_class( 'ot-upload-attachment-id' ),
                    ),

                    array(

                        'label'         =>      esc_html__( 'Social Media', 'sdweddingdirectory-real-wedding' ),

                        'id'            =>      esc_attr( 'social_media_tab' ),

                        'type'          =>      esc_attr( 'tab' ),
                    ),

                    array(

                        'id'            =>      esc_attr( 'realwedding_social' ),

                        'label'         =>      esc_attr__( 'Real wedding Social Media', 'sdweddingdirectory-real-wedding' ),

                        'std'           =>      array(

                            array(
                                
                                'title'         =>      esc_attr__( 'Facebook', 'sdweddingdirectory-real-wedding' ),
                                
                                'icon'          =>      esc_attr( 'fa-facebook-f' ),
                                
                                'link'          =>      '',
                            ),

                            array(
                                
                                'title'         =>      esc_attr__( 'Twitter', 'sdweddingdirectory-real-wedding' ),
                                
                                'icon'          =>      esc_attr( 'fa-twitter' ),
                                
                                'link'          =>      '',
                            ),

                            array(
                                
                                'title'         =>      esc_attr__( 'Instagram', 'sdweddingdirectory-real-wedding' ),
                                
                                'icon'          =>      esc_attr( 'fa-instagram' ),
                                
                                'link'          =>      '',
                            ),

                            array(
                                
                                'title'         =>      esc_attr__( 'LinkedIn', 'sdweddingdirectory-real-wedding' ),
                                
                                'icon'          =>      esc_attr( 'fa-linkedin' ),
                                
                                'link'          =>      '',
                            ),
                        ),

                        'type'              =>      esc_attr( 'list-item' ),

                        'settings'          =>      array(

                            array(

                                'id'            =>      esc_attr( 'icon' ),

                                'label'         =>      esc_attr__( 'Please Select Icon Font', 'sdweddingdirectory-real-wedding' ),

                                'type'          =>      esc_attr( 'select' ),

                                'choices'       =>      apply_filters( 'sdweddingdirectory_icons_set_for_ot', [] ),

                            ),

                            array(

                                'id'            =>      esc_attr( 'link' ),

                                'label'         =>      esc_attr__( 'Please Update Link', 'sdweddingdirectory-real-wedding' ),

                                'type'          =>      esc_attr( 'text' ),
                            ),
                        ),
                    ),
                ),
            );

            /**
             *  Return Merge Array
             *  ------------------
             */
            return      array_merge( $args, array( $_new_args ) );
        }
    }

    /**
     *  Meta Object
     *  -----------
     */
    SDWeddingDirectory_Real_Wedding_Meta:: get_instance();
}