<?php
/**
 *  SDWeddingDirectory - Couple Wedding Website Section
 *  -------------------------------------------
 */
if( ! class_exists( 'SDWeddingDirectory_Couple_Wedding_Website_Couple_Gallery_Section' ) && class_exists( 'SDWeddingDirectory_Couple_Website_Section' ) ){

    /**
     *  SDWeddingDirectory - Couple Wedding Website Section
     *  -------------------------------------------
     */
    class SDWeddingDirectory_Couple_Wedding_Website_Couple_Gallery_Section extends SDWeddingDirectory_Couple_Website_Section{

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
             *  Wedding Website - Section 
             *  -------------------------
             */
            add_filter( 'sdweddingdirectory/wedding-website/layout-1', [ $this, 'widget' ], absint( '90' ), absint( '1' ) );
        }

        /**
         *  Wedding Website - Section 
         *  -------------------------
         */
        public static function widget( $args = [] ){

            $args[ 'couple_gallery' ]   =   [
                                                'scroll'        =>      '40',                                

                                                'name'          =>      esc_attr__( 'Gallery', 'sdweddingdirectory-couple-website' ),

                                                'member'        =>      [ __CLASS__, 'member' ],

                                                'menu'          =>      true
                                            ];
            return      $args;
        }

        /**
         *  Couple About Us
         *  ---------------
         */
        public static function member(){

            ?>
            <div class="wide-tb-120 captured-moments" id="gallery">
                <div class="container">
                    <?php

                        /**
                         *  Load Header
                         *  -----------
                         */
                        parent:: heading_section( 'couple_gallery_heading', 'couple_gallery_description' );

                        /**
                         *  Load Gallery
                         *  ------------
                         */
                        $_gallery   =   parent:: get_website_meta( 'couple_gallery' );

                        /**
                         *  Is Array ?
                         *  ----------
                         */
                        if( parent:: _is_array( $_gallery ) ){

                            print   '<div class="row"><div class="col-md-12"><ul id="portfolio-flters" class="list-unstyled">';

                            printf( '<li data-filter="*" class="filter-active"><a href="javascript:">All Photos</a></li>', 

                                /**
                                 *  1. Translation Ready String
                                 *  ---------------------------
                                 */
                                esc_attr__( 'All Photos', 'sdweddingdirectory-couple-website' )
                            );

                            foreach( $_gallery as $key => $value ){

                                /**
                                 *  Extract
                                 *  -------
                                 */
                                extract( $value );

                                printf( '<li data-filter=".%1$s"><a href="javascript:">%2$s</a></li>', 

                                    /**
                                     *  1. ID
                                     *  -----
                                     */
                                    sanitize_title( $gallery_name ),

                                    /**
                                     *  2. Text
                                     *  -------
                                     */
                                    esc_attr( $gallery_name )
                                    
                                );
                            }

                            print   '</ul></div></div>';

                            print '<div class="isotope-gallery captured-img-gallery couple-website-gallery row row-cols-xxl-4 row-cols-xl-4 row-cols-lg-3 row-cols-md-2 row-cols-sm-2 row-cols-1">';

                            foreach( $_gallery as $key => $value ){

                                /**
                                 *  Extract
                                 *  -------
                                 */
                                extract( $value );

                                if( parent:: _have_data( $gallery_image ) ){

                                    $_get_media     =   parent:: _filter_media_ids( $gallery_image );

                                    /**
                                     *  Is Array ?
                                     *  ----------
                                     */
                                    if( parent:: _is_array( $_get_media ) ){

                                        foreach( $_get_media as $media_id => $media_value ){

                                            printf(    '<div class="isotope-item col mb-3 %3$s">
                                                            <div class="captured-gallery-item">
                                                                <a href="%1$s" title="%3$s">
                                                                    <img src="%2$s" class="rounded" alt="%3$s" />
                                                                </a>
                                                            </div>
                                                        </div>',

                                                        /**
                                                         *  1. Image Full Width
                                                         *  -------------------
                                                         */
                                                        parent:: media_id_to_get_src( [ 

                                                            'media_id'  =>  $media_value, 

                                                            'size'      =>  esc_attr( 'full' )

                                                        ] ),

                                                        /**
                                                         *  2. Thumbnails
                                                         *  -------------
                                                         */
                                                        parent:: media_id_to_get_src( [ 

                                                            'media_id'  =>  $media_value,

                                                            'size'      =>  esc_attr( 'sdweddingdirectory_img_500x600' ) 

                                                        ] ),

                                                        /**
                                                         *  3. Title
                                                         *  --------
                                                         */
                                                        sanitize_title( $gallery_name )
                                            );
                                        }
                                    }

                                }
                            }

                            print '</div>';
                        }

                    ?>
                    
                </div>
            </div>
            <?php
        }
    }

    /**
     *  SDWeddingDirectory - Couple Wedding Website Section
     *  -------------------------------------------
     */
    SDWeddingDirectory_Couple_Wedding_Website_Couple_Gallery_Section::get_instance();
}