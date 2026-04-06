<?php
/**
 *  SDWeddingDirectory - Singular RealWedding
 *  ---------------------------------
 */
if( ! class_exists( 'SDWeddingDirectory_Single_RealWedding' ) && class_exists( 'SDWeddingDirectory_Real_Wedding_Database' ) ){

    /**
     *  SDWeddingDirectory - Singular RealWedding
     *  ---------------------------------
     */
    class SDWeddingDirectory_Single_RealWedding extends SDWeddingDirectory_Real_Wedding_Database{

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
             *   1. Realwedding script
             *   ---------------------
             */
            add_action( 'wp_enqueue_scripts', [$this, 'sdweddingdirectory_script'] );

            /**
             *  3. Singular Readwedding action
             *  ------------------------------
             */
            add_action( 'sdweddingdirectory/real-wedding/detail-page', [$this, 'realwedding_detail_page'] );
        }

        /**
         *   1. Realwedding script
         *   ---------------------
         */
        public static function sdweddingdirectory_script(){

            /**
             *  Is Real Wedding - Singular Page
             *  -------------------------------
             */
            if(  is_singular( 'real-wedding' ) ){

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

                /**
                 *  Load Library Condition
                 *  ----------------------
                 */
                add_filter( 'sdweddingdirectory/enable-script/magnific-popup', function( $args = [] ){

                    return  array_merge( $args, [ 'real_wedding_singular'   =>  true ] );

                } );

                /**
                 *  Load Library Condition
                 *  -----------------------
                 */
                add_filter( 'sdweddingdirectory/enable-script/review', function( $args = [] ){

                    return  array_merge( $args, [ 'real_wedding_singular'   =>  true ] );
                    
                } );
            }
        }

        /**
         *  2. Singular Readwedding action
         *  ------------------------------
         */
        public static function realwedding_detail_page(){

            /**
             *  Page Visit
             *  ----------
             */
            $_page_visit    =   get_post_meta( absint( get_the_ID() ), sanitize_key( 'page_visit' ), true );

            /**
             *  Page Visit Counter
             *  ------------------
             */
            update_post_meta( 

                /**
                 *  Page ID
                 *  -------
                 */
                absint( get_the_ID() ), 

                /**
                 *  Page Visit
                 *  ----------
                 */
                sanitize_key( 'page_visit' ),  

                /**
                 *  Get the last visit counter
                 *  --------------------------
                 */
                absint( $_page_visit ) +  absint( '1' )
            );

            /**
             *  1. Real Wedding Page Overview
             *  -----------------------------
             */
            self:: page_header_banner();

            /**
             *  2. Section Start
             *  ----------------
             */
            ?><section class="wide-tb-90"><div class="container"><?php

                /**
                 *  2.1 Real Wedding - Story Content
                 *  --------------------------------
                 */
                self:: real_wedding_story_content();

                /**
                 *  2.2 Real Wedding - Photo Gallery
                 *  --------------------------------
                 */
                self:: real_wedding_photo_gallery();

                /**
                 *  2.4 Real Wedding - Couple Info
                 *  ------------------------------
                 */
                self:: real_wedding_couple_info();

                /**
                 *  2.3 Real Wedding - Tagged Vendors
                 *  --------------------------------
                 */
                self:: real_wedding_tagged_vendors();

                /**
                 *  2.4 Real Wedding - Our Side Vendors
                 *  -----------------------------------
                 */
                self:: real_wedding_tagged_out_side_vendor();

                /**
                 *  2.5 Real Wedding - Similar RealWedding
                 *  --------------------------------------
                 */
                self:: real_wedding_similar_realwedding();

            ?></div></section><?php
        }

        /**
         *  1. Real Wedding Page Overview
         *  -----------------------------
         */
        public static function page_header_banner(){

            $groom_first_name           =   get_post_meta( absint( get_the_ID() ), sanitize_key( 'groom_first_name' ), true );

            $bride_first_name           =   get_post_meta( absint( get_the_ID() ), sanitize_key( 'bride_first_name' ), true );

            $wedding_date_get           =   get_post_meta( absint( get_the_ID() ), sanitize_key( 'wedding_date' ), true );

            $page_header_image_id       =   get_post_meta( absint( get_the_ID() ), sanitize_key( 'page_header_banner' ), true );

            $wedding_vendors            =   get_post_meta( absint( get_the_ID() ), sanitize_key( 'our_website_vendor_credits' ), true );

            /**
             *  Page Headre Banner Load
             *  -----------------------
             */
            printf('<section class="real-wedding-single-wrap">

                        <div class="real-wedding-single-img" style="%1$s"></div>

                        <div class="real-wedding-single">

                            <div class="container h-100">

                                <div class="row align-items-lg-end d-flex h-100 align-items-center">

                                    <div class="col-lg-5">

                                        <div class="name">

                                            <div class="ring">

                                                <i class="sdweddingdirectory-heart-ring"></i>

                                            </div>

                                            <div>

                                                <h2>%2$s & %3$s</h2>

                                                <div class="mb-2">%4$s</div>

                                                <span><i class="fa fa-calendar"></i> %5$s</span>

                                            </div>

                                        </div>

                                    </div>

                                    <div class="col-lg-7 text-lg-end mt-4 mt-lg-0">

                                        <div class="links">%6$s %8$s</div>

                                    </div>

                                </div>

                            </div>

                        </div>

                    </section>',

                    /**
                     *  1. Real Wedding - Page Header Banner Background Image
                     *  -----------------------------------------------------
                     */
                    sprintf('background:url(%1$s) center center no-repeat; background-size: cover;',

                        /**
                         *  Have Page Header Banner Image ?
                         *  -------------------------------
                         */
                        !   empty( $page_header_image_id )

                        ?   apply_filters( 'sdweddingdirectory/media-data', [

                                'media_id'      =>  absint( $page_header_image_id ),

                                'image_size'    =>  esc_attr( 'sdweddingdirectory_img_1920x600' ),

                            ] )

                        :   esc_url( parent:: placeholder( 'real-wedding-banner' ) )
                    ),

                    /**
                     *  2. Real Wedding Groom Name
                     *  --------------------------
                     */
                    esc_attr( $groom_first_name ),

                    /**
                     *  3. Real Wedding Bride Name
                     *  --------------------------
                     */
                    esc_attr( $bride_first_name ),

                    /**
                     *  4. Real Wedding Address
                     *  -----------------------
                     */
                    apply_filters( 'sdweddingdirectory/post/location', [

                        'post_id'       =>      absint( get_the_ID() ),

                        'taxonomy'      =>      esc_attr( 'real-wedding-location' ),

                    ] ),

                    /**
                     *  5. Real Wedding Date
                     *  --------------------
                     */
                    esc_attr( date( 'd F Y', strtotime( $wedding_date_get ) ) ),

                    /**
                     *  6. RealWedding Social Media
                     *  ---------------------------
                     */
                    sprintf(   '<a class="btn btn-outline-white sdweddingdirectory-share-post-model" data-post-id="%1$s" href="javascript:">

                                    <i class="fa fa-share-alt"></i> 

                                    <span>%2$s</span>

                                </a>', 

                        /**
                         *  1. Post ID
                         *  ----------
                         */
                        absint( get_the_ID() ),

                        /**
                         *  2. Translation Ready String
                         *  ---------------------------
                         */
                        esc_attr__( 'Share', 'sdweddingdirectory-real-wedding' )
                    ),

                    /**
                     *  7. Favorite Text
                     *  ----------------
                     */
                    esc_attr__( 'Favorite', 'sdweddingdirectory-real-wedding' ),

                    /**
                     *  8. Request Pricing Text
                     *  -----------------------
                     */
                    !   empty( $wedding_vendors )

                    ?   sprintf(   '<a href="javascript:" class="btn btn-default" id="sdweddingdirectory_vendor_request">

                                        <i class="fa fa-check"></i> %1$s

                                    </a>',

                                    /**
                                     *  1. Translation Ready String
                                     *  ---------------------------
                                     */
                                    esc_attr__( 'Request Pricing', 'sdweddingdirectory-real-wedding' )
                        )

                    :   ''
            );
        }

        /**
         *  2.1 Real Wedding - Story Content
         *  --------------------------------
         */
        public static function real_wedding_story_content(){

            /**
             *  Get Content ?
             *  -------------
             */
            $_content   =           get_the_content(

                                        null, 

                                        false,

                                        /**
                                         *  Post ID
                                         *  -------
                                         */
                                        absint( get_the_ID() )
                                    );

            /**
             *  Have Data ?
             *  -----------
             */
            if( ! empty( $_content ) ){

                printf('<div class="card-shadow position-relative">

                            <div class="card-shadow-header">

                                <h3><i class="fa fa-file-text"></i> %1$s</h3>

                            </div>

                            <div class="card-shadow-body pb-1">%2$s</div>

                        </div>',

                        /**
                         *  1. Our Story Text
                         *  --------------
                         */
                        esc_attr__( 'Our Story', 'sdweddingdirectory-real-wedding' ),

                        /**
                         *  2.2.1 Real Wedding Content
                         *  --------------------------
                         */
                        do_shortcode(

                            /**
                             *  ------------
                             *  Post Content
                             *  ------------
                             *  @credit - https://developer.wordpress.org/reference/functions/get_the_content/#user-contributed-notes
                             *  -----------------------------------------------------------------------------------------------------
                             */
                            apply_filters( 

                                /**
                                 *  Filter Name
                                 *  -----------
                                 */
                                esc_attr( 'the_content' ), 

                                /**
                                 *  ---------------
                                 *  Get The Content
                                 *  ---------------
                                 *  @article - https://developer.wordpress.org/reference/functions/get_the_content/
                                 *  -------------------------------------------------------------------------------
                                 */
                                get_the_content(

                                    null, 

                                    false,

                                    /**
                                     *  Post ID
                                     *  -------
                                     */
                                    absint( get_the_ID() ) 
                                )
                            )
                        )
                );
            }
        }

        /**
         *  2.2 Real Wedding - Photo Gallery
         *  --------------------------------
         */
        public static function real_wedding_photo_gallery(){

            /**
             *  Gallery Data
             *  ------------
             */
            $gallery_data    =  parent:: _filter_media_ids( parent:: _coma_to_array(

                                    get_post_meta( absint( get_the_ID() ), sanitize_key( 'realwedding_gallery' ), true )

                                ) );

            /**
             *  Have Gallery Data Collection ?
             *  ------------------------------
             */
            if( parent::_is_array( $gallery_data ) ){

                /**
                 *  Gallery Counter
                 *  ---------------
                 */
                $_show_image    =   absint( '8' );

                $_counter       =   absint( '0' );

                $_collapse_id   =   esc_attr( 'sdweddingdirectory-real-wedding-image-gallery' );

                $_count_images  =   absint( count( $gallery_data ) );

                /**
                 *  Section Start
                 *  -------------
                 */
                ?>
                <div class="card-shadow position-relative">

                    <div class="card-shadow-header">

                        <h3><i class="fa fa-image"></i> <?php esc_attr_e( 'Photo Gallery', 'sdweddingdirectory-real-wedding' ); ?></h3>

                    </div>

                    <?php

                    printf( '<div class="card-shadow-body %1$s">',

                        $_count_images < absint( '6' )

                        ?   sanitize_html_class( 'pb-0' )

                        :   ''
                    );

                    /**
                     *  Have Data ?
                     *  -----------
                     */
                    foreach ( $gallery_data as $_key => $media_id ) {

                        /**
                         *  Make sure counter value 
                         *  -----------------------
                         */
                        if( $_counter == absint( '0' ) ){

                            printf( '<div class="row row-cols-1 row-cols-md-4 row-cols-sm-3 realwedding-gallery">' );
                        }
                        
                        elseif( $_counter == absint( $_show_image ) ){

                            printf( '</div><div id="%1$s" class="collapse"><div class="row row-cols-1 row-cols-md-4 row-cols-sm-3 realwedding-gallery %1$s">',

                                /**
                                 *  1. Collapse ID
                                 *  --------------
                                 */
                                esc_attr( $_collapse_id )
                            );
                        }

                        /**
                         *  Gallery Load
                         *  ------------
                         */
                        printf('<div class="col">

                                    <a href="%2$s" title="%3$s %4$s">

                                        <img src="%1$s" alt="%5$s" class="rounded">

                                    </a>

                                </div>',

                                /**
                                 *  1. Image Source
                                 *  ---------------
                                 */
                                parent:: _have_media( $media_id )

                                ?   apply_filters( 'sdweddingdirectory/media-data', [

                                        'media_id'      =>  absint( $media_id ),

                                        'image_size'    =>  esc_attr( 'sdweddingdirectory_img_600x385' ),

                                    ] )

                                :   esc_url( parent:: placeholder( 'real-wedding-gallery' ) ),

                                /**
                                 *  2. Image Source
                                 *  ---------------
                                 */
                                apply_filters( 'sdweddingdirectory/media-data', [

                                    'media_id'      =>  absint( $media_id ),

                                    'image_size'    =>  esc_attr( 'full' ),

                                ] ),

                                /**
                                 *  3. Venue Name
                                 *  ---------------
                                 */
                                esc_attr( get_the_title() ),

                                /**
                                 *  4. Translation Ready String
                                 *  ---------------------------
                                 */
                                esc_attr__( 'Gallery', 'sdweddingdirectory-real-wedding' ),

                                /**
                                 *  5. Image ALT
                                 *  ------------
                                 */
                                esc_attr( parent:: _alt( array(

                                    'media_id'  =>  absint( $media_id ),

                                    'post_id'   =>  absint( get_the_ID() ),

                                    'start_alt' =>  esc_attr( 'The wedding of' ),

                                    'end_alt'   =>  sprintf( 

                                                        /**
                                                         *  1. Translation Ready String
                                                         *  ---------------------------
                                                         */
                                                        esc_attr__( 'Gallery %1$s', 'sdweddingdirectory-real-wedding' ), 

                                                        /**
                                                         *  2. Galler Count
                                                         *  ---------------
                                                         */
                                                        absint( $_counter )
                                                    )
                                ) ) )
                        );

                        /**
                         *  Counter Value
                         *  -------------
                         */
                        $_counter++;
                    }

                    /**
                     *  Make sure gallery images more then 6 + this is end of last image
                     *  ----------------------------------------------------------------
                     */
                    if( $_counter > absint( $_show_image ) && $_count_images > absint( $_show_image ) ){

                        printf( '</div></div>

                                <div class="gallery-btn">
                                    <a data-bs-toggle="collapse" href="#%1$s" role="button" aria-expanded="false" class="collapsed"><i class="fa fa-angle-down"></i> <span>%2$s %3$s</span></a>
                                </div>',

                                /**
                                 *  1. Collapse ID
                                 *  --------------
                                 */
                                esc_attr( $_collapse_id ),

                                /**
                                 *  6. Total Pending View Image
                                 *  ---------------------------
                                 */
                                absint( absint( $_count_images ) - absint( $_show_image ) ),

                                /**
                                 *  3. Translation Ready String
                                 *  ---------------------------
                                 */
                                esc_attr__( 'More', 'sdweddingdirectory-real-wedding' )
                        );

                    }else{

                        print '</div>';
                    }

                    ?>

                    </div>

                </div>
                <?php
            }
        }

        /**
         *  2.3 Real Wedding - Tagged Vendors
         *  ---------------------------------
         */
        public static function real_wedding_tagged_vendors(){

            $wedding_vendors    =   self:: get_data( sanitize_key( 'our_website_vendor_credits' ) );

            $groom_first_name   =   self:: get_data( sanitize_key( 'groom_first_name' ) );

            $bride_first_name   =   self:: get_data( sanitize_key( 'bride_first_name' ) );

            /**
             *  This couple hire any vendor team on own wedding ?
             *  -------------------------------------------------
             */
            if( preg_split ("/\,/", $wedding_vendors ) && ! empty( $wedding_vendors ) ){

                ?>
                    <div class="card-shadow position-relative" id="couple_vendors_team">
                        <div class="card-shadow-header">
                            <?php

                                printf( '<h3><i class="fa fa-tags"></i> %1$s</h3>', 

                                    sprintf(  esc_attr__( '%1$s and %2$s\'s vendor team', 'sdweddingdirectory-real-wedding' ), 

                                        /**
                                         *  1. Groom Name Here
                                         *  ------------------
                                         */
                                        esc_attr( $groom_first_name ),

                                        /**
                                         *  1. Bride Name Here
                                         *  ------------------
                                         */
                                        esc_attr( $bride_first_name )
                                    )
                                );
                            ?>
                        </div>
                        
                        <div class="card-shadow-body pb-0 realwedding-vendor-team"><div class="row"><?php

                            /**
                             *  Get Collection
                             *  --------------
                             */
                            $post_ids   =   apply_filters( 'sdweddingdirectory/venue/badge-filter', preg_split ("/\,/", $wedding_vendors ) );

                            /**
                             *  Make sure it's array
                             *  --------------------
                             */
                            if( parent:: _is_array( $post_ids ) ){

                                foreach ( $post_ids as $key => $venue_id ) {

                                    if( isset( $venue_id ) && ! empty( $venue_id ) ){

                                        /**
                                         *  Load Article
                                         *  ------------
                                         */
                                        print 

                                        apply_filters( 'sdweddingdirectory/venue/post', [

                                            'layout'    =>  absint( '2' ),

                                            'post_id'   =>  absint( $venue_id )

                                        ] );
                                    }
                                }
                            }

                        ?></div></div>

                    </div>

                <?php

            }
        }

        /**
         *  2.4 : Real Wedding - Our Side Vendor
         *  ------------------------------------
         */
        public static function real_wedding_tagged_out_side_vendor(){

            $wedding_vendors    =   self:: get_data( sanitize_key( 'out_side_vendor_credits' ) );

            $groom_first_name   =   self:: get_data( sanitize_key( 'groom_first_name' ) );

            $bride_first_name   =   self:: get_data( sanitize_key( 'bride_first_name' ) );

            /**
             *  This couple hire any vendor team on own wedding ?
             *  -------------------------------------------------
             */
            if( parent:: _is_array( $wedding_vendors ) ){

                ?>

                <div class="card-shadow position-relative" id="couple_out_side_vendors_team">

                    <div class="card-shadow-header">

                        <?php

                            printf( '<h3><i class="fa fa-tags"></i> %1$s</h3>',

                                sprintf(  esc_attr__( '%1$s and %2$s\'s out side vendor team', 'sdweddingdirectory-real-wedding' ), 

                                    /**
                                     *  1. Groom Name Here
                                     *  ------------------
                                     */
                                    esc_attr( $groom_first_name ),

                                    /**
                                     *  1. Bride Name Here
                                     *  ------------------
                                     */
                                    esc_attr( $bride_first_name )
                                )
                            );
                        ?>

                    </div>
                    
                    <div class="card-shadow-body pb-0 realwedding-vendor-team">

                        <div class="row row-cols-1 row-cols-xl-3 row-cols-lg-3 row-cols-md-3 row-cols-sm-2">

                            <?php

                                foreach ( $wedding_vendors as $key => $value ) {

                                    extract( $value );

                                    printf(     '<div class="col">

                                                    <div class="tagged-vendors">

                                                        <h3>%1$s</h3>

                                                        <p>%2$s</p>

                                                        <a href="%3$s" target="_blank">%4$s</a>

                                                    </div>

                                                </div>',

                                        /**
                                         *  1. Business Name
                                         *  ----------------
                                         */
                                        esc_attr( $company ),

                                        /**
                                         *  2. Category
                                         *  -----------
                                         */
                                        esc_attr( $category ),

                                        /**
                                         *  3. Website
                                         *  ----------
                                         */
                                        esc_url( $website ),

                                        /**
                                         *  4. Translation Ready String
                                         *  ---------------------------
                                         */
                                        esc_attr__( 'Website', 'sdweddingdirectory-real-wedding' )
                                    );

                                }

                            ?>

                        </div>

                    </div>

                </div>

                <?php
            }
        }

        /**
         *  2.4 Real Wedding - Couple Info
         *  ------------------------------
         */
        public static function real_wedding_couple_info(){

            ?>
            <div class="card-shadow position-relative">

                <div class="card-shadow-header">

                    <h3><i class="fa fa-list-ul"></i> <?php esc_attr_e( 'Wedding Couple Info', 'sdweddingdirectory-real-wedding' ); ?></h3>

                </div>
                
                <div class="card-shadow-body pb-0">

                    <div class="row">

                        <div class="col-lg-6 col-12">

                            <div class="row">
                            <?php

                                /**
                                 *  1. Featured Image
                                 *  -----------------
                                 */
                                $_have_thumbnail    =   parent:: realwedding_image( absint( get_the_ID() ) );

                                /**
                                 *  Have Featured Image ?
                                 *  ---------------------
                                 */
                                if( ! empty( $_have_thumbnail ) ){

                                    printf( '<div class="col-sm-6 col-12"><img src="%1$s" class="mb-4" alt="%2$s" /></div>', 

                                        /**
                                         *  1. Featured Image
                                         *  -----------------
                                         */
                                        esc_url( $_have_thumbnail ),

                                        /**
                                         *  2. Image ALT
                                         *  ------------
                                         */
                                        esc_attr( get_the_title( absint( get_the_ID() ) ) )
                                    );
                                }

                                /**
                                 *  Groom + Bride Name
                                 *  ------------------
                                 */
                                
                                $groom_first_name   =   self:: get_data( sanitize_key( 'groom_first_name' ) );

                                $bride_first_name   =   self:: get_data( sanitize_key( 'bride_first_name' ) );

                                /**
                                 *  Load the section 
                                 *  ----------------
                                 */
                                if( $groom_first_name !== '' && $bride_first_name !== ''){

                                    ?><div class="col-sm-6 col-12 mb-4"><?php

                                        /**
                                         *  Bride + Groom Name
                                         *  ------------------
                                         */
                                        printf( '<h3>%1$s &amp; %2$s</h3>',

                                                /**
                                                 *  1. Groom Name
                                                 *  -------------
                                                 */
                                                esc_attr( $groom_first_name ),
                                                
                                                /**
                                                 *  2. Bride Name
                                                 *  -------------
                                                 */ 
                                                esc_attr( $bride_first_name )
                                        );

                                        /**
                                         *  Description
                                         *  -----------
                                         */
                                        if( get_the_excerpt() !== '' ){

                                            echo html_entity_decode( get_the_excerpt() );
                                        }
                                        
                                        /**
                                         *  Social Media
                                         *  ------------
                                         */
                                        $social_info =  self:: get_data( sanitize_key( 'realwedding_social' ) );

                                        if( parent:: _is_array( $social_info ) && false ){

                                            ?><div class="social-sharing"><?php

                                                foreach ( $social_info as $key => $value ) {

                                                    printf('<a href="%1$s" class="share-btn-%4$s" title="%3$s"><i class="fa %2$s"></i></a>',

                                                        /**
                                                         *  1. Social Link
                                                         *  --------------
                                                         */
                                                        esc_url( $value['link'] ),

                                                        /**
                                                         *  2. Social Icon
                                                         *  --------------
                                                         */
                                                        esc_attr( $value['icon'] ),

                                                        /**
                                                         *  3. Social Media Name
                                                         *  --------------------
                                                         */
                                                        esc_attr( $value['title'] ),

                                                        /**
                                                         *  4. Title Sanitize
                                                         *  -----------------
                                                         */
                                                        sanitize_title( $value['title'] )

                                                    );

                                                } // end foreach;

                                            ?></div><?php

                                        } // end if;

                                    ?></div><?php
                                }
                            ?>
                            </div>

                        </div>

                        <div class="col-lg-6 col-12">
                        
                        <div class="row"><?php

                            /**
                             *  Page Views
                             *  ----------
                             */
                            self:: real_wedding_meta_info( [

                                'meta_key'      =>      esc_attr( 'page_visit' ),

                                'name'          =>      esc_attr__( 'Views', 'sdweddingdirectory-real-wedding' )

                            ] );

                            /**
                             *  Real Wedding - Function
                             *  -----------------------
                             */
                            self:: real_wedding_meta_info( [

                                'meta_key'      =>      esc_attr( 'realwedding_function' ),

                                'name'          =>      esc_attr__( 'Function', 'sdweddingdirectory-real-wedding' ),

                                'column'        =>      esc_attr( 'col-md-12 mb-0' )

                            ] );

                            /**
                             *  Real Wedding : Tags
                             *  -------------------
                             */
                            self:: real_wedding_tags();

                            ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <?php
        }

        /**
         *  2.5 Real Wedding - Similar RealWedding
         *  --------------------------------------
         */
        public static function real_wedding_similar_realwedding(){

            global $post, $wp_query;

            /**
             *  Meta Query Variable
             *  -------------------
             */
            $meta_query             =   [];

            /**
             *  Bride First Name
             *  ----------------
             */
            $meta_query[]   =   array(

                'key'       =>  esc_attr( 'bride_first_name' ),

                'compare'   =>  '!=',

                'value'     =>  ''
            );

            /**
             *  Groom First Name
             *  ----------------
             */
            $meta_query[]   =   array(

                'key'       =>  esc_attr( 'groom_first_name' ),

                'compare'   =>  '!=',

                'value'     =>  ''
            );

            /**
             *  Real Wedding : Similar Post : Query
             *  -----------------------------------
             */
            $query     =    new WP_Query( [

                                'post_type'         =>  esc_attr( 'real-wedding' ),

                                'post_status'       =>  esc_attr( 'publish' ),

                                'posts_per_page'    =>  absint( '4' ),

                                'orderby'           =>  'menu_order ID',

                                'order'             =>  'post_date',

                                'post__not_in'      =>  array(

                                                            absint( get_the_ID() )
                                                        ),

                                'meta_query'        =>  array(

                                    'relation'      => 'AND',

                                    $meta_query,
                                )

                            ] );

            /**
             *  Have Real Wedding Post ?
             *  ------------------------
             */
            if( $query->have_posts() ){

                /**
                 *  Heading
                 *  -------
                 */
                printf( '<div class="card-shadow position-relative">

                            <div class="card-shadow-header">

                                <h3><i class="sdweddingdirectory_birde"></i> %1$s</h3>

                            </div>

                            <div class="card-shadow-body pb-0">

                                <div class="row row-cols-xxl-4 row-cols-xl-4 row-cols-lg-3 row-cols-md-2 row-cols-sm-2 row-cols-1 ">',

                            /**
                             *  1. Translation Ready String
                             *  ---------------------------
                             */
                            esc_attr__( 'Similar RealWedding', 'sdweddingdirectory-real-wedding' )
                );

                /**
                 *  Post Load
                 *  ---------
                 */
                while ( $query->have_posts() ){  $query->the_post();

                    printf( '<div class="col">%1$s</div>',

                        /**
                         *  1. Real Wedding Layout 1
                         *  ------------------------
                         */
                        apply_filters( 'sdweddingdirectory/real-wedding/post', [

                            'layout'    =>  absint( '1' ),

                            'post_id'   =>  absint( get_the_ID() )

                        ] )
                    );
                }

                print '</div></div></div>';

                /**
                 *  Have Query ?
                 *  ------------
                 */
                if( isset( $query ) ){

                    wp_reset_postdata();
                }
            }
        }

        /**
         *  2.4.1.1. SDWeddingDirectory CPT Category Value
         *  --------------------------------------
         */
        public static function real_wedding_category(){

            $_category   = get_the_terms( absint( get_the_ID() ) , esc_attr( 'real-wedding-category' ) );

            /**
             *  Have Data ?
             *  -----------
             */
            if( parent:: _is_array( $_category ) ){

                $term_data      =   [];

                foreach ( $_category as $term ) {

                    $term_data[] =  sprintf( '<a href="%1$s">%2$s</a>', 

                                        /**
                                         *  1. Term Link
                                         *  ------------
                                         */
                                        esc_url( get_term_link( $term ) ),

                                        /**
                                         *  2. Term Name
                                         *  ------------
                                         */
                                        esc_attr( $term->name )
                                    );
                }

                /**
                 *  Have Data ?
                 *  -----------
                 */
                if( parent:: _is_array( $term_data ) ){

                    /**
                     *  Merge One By One All Category
                     *  -----------------------------
                     */
                    $term_data_view = join( ", ", $term_data);  

                    printf('<div class="col-md-12 mb-0">

                                <div class="wedding-info-details border-bottom">

                                    <small>%1$s</small> %2$s

                                </div>

                            </div>',

                            /**
                             *  1. Categories Text
                             *  ------------------
                             * 
                             */
                            esc_attr__( 'Categories', 'sdweddingdirectory-real-wedding' ),

                            /**
                             *  2. Categories All
                             *  -----------------
                             */
                            $term_data_view
                    );
                }
            }
        }

        /**
         *  2.4.1.1. SDWeddingDirectory CPT Tags Value
         *  ----------------------------------
         */
        public static function real_wedding_tags(){

            $_tags      =       get_the_terms( absint( get_the_ID() ), esc_attr( 'real-wedding-tag' ) ); 

            /**
             *  Have Data ?
             *  -----------
             */
            if( parent:: _is_array( $_tags ) ){

                $term_data      =   [];

                foreach ( $_tags as $term ) {

                    $term_data[] =  sprintf( '<a href="%1$s">%2$s</a>', 

                                        /**
                                         *  1. Term Link
                                         *  ------------
                                         */
                                        esc_url( get_term_link( $term ) ),

                                        /**
                                         *  2. Term Name
                                         *  ------------
                                         */
                                        esc_attr( $term->name )
                                    );
                }

                /**
                 *  Have Data ?
                 *  -----------
                 */
                if( parent:: _is_array( $term_data ) ){

                    /**
                     *  Merge One By One All Category
                     *  -----------------------------
                     */
                    $term_data_view     =   join( ', ', $term_data );

                    printf( '<div class="col-md-12 mb-0">

                                <div class="wedding-info-details">

                                    <small>%1$s</small> %2$s

                                </div>

                            </div>',

                            /**
                             *  1. Categories Text
                             *  ------------------
                             * 
                             */
                            esc_attr__( 'Tags', 'sdweddingdirectory-real-wedding' ),

                            /**
                             *  2. Categories All
                             *  -----------------
                             */
                            $term_data_view
                    );
                }
            }
        }

        /**
         *  Get post meta - Function
         *  ------------------------
         */
        public static function get_data( $a ){

            global $wp_query, $post;

            if( $a !== '' && isset( $a ) ){

                return get_post_meta( absint( get_the_ID() ), sanitize_key( $a ), true );
            }
        }

        /**
         *  Taxonomy Name
         *  -------------
         */
        public static function real_wedding_tax_info( $args = [] ){

            /**
             *  Have Args
             *  ---------
             */
            if( parent:: _is_array( $args ) ){

                /**
                 *  Extract
                 *  -------
                 */
                extract( wp_parse_args( $args, [

                    'post_id'       =>      absint( get_the_ID() ),

                    'taxonomy'      =>      '',

                    'name'          =>      '',

                    'column'        =>      'col-md-4 col-12 mb-0'

                ] ) );

                /**
                 *  Make sure post id and taxonomy not empty!
                 *  -----------------------------------------
                 */
                if( empty( $post_id ) || empty( $taxonomy ) ){

                    return;
                }

                /**
                 *  Real Wedding - Tax Information
                 *  ------------------------------
                 */
                $value      =   apply_filters( 'sdweddingdirectory/find-post-location/name', [

                                    'taxonomy'      =>    esc_attr( $taxonomy ),

                                    'post_id'       =>    absint( $post_id )

                                ] );

                /**
                 *  Real Wedding - Tax Value
                 *  ------------------------
                 */
                if( ! empty( $value ) ){

                    printf( '<div class="%1$s">

                                <div class="wedding-info-details border-bottom">

                                    <small>%2$s</small>

                                    <span>%3$s</span>

                                </div>

                            </div>',

                            /**
                             *   1. Column
                             *   ---------
                             */
                            esc_attr( $column ),

                            /**
                             *  2. Name
                             *  -------
                             */
                            esc_attr( $name ),

                            /**
                             *  3. Tax - Value
                             *  --------------
                             */
                            esc_attr( $value )
                    );
                }
            }
        }

        /**
         *  Meta Information
         *  ----------------
         */
        public static function real_wedding_meta_info( $args = [] ){

            /**
             *  Have Args
             *  ---------
             */
            if( parent:: _is_array( $args ) ){

                /**
                 *  Extract
                 *  -------
                 */
                extract( wp_parse_args( $args, [

                    'post_id'       =>      absint( get_the_ID() ),

                    'meta_key'      =>      '',

                    'name'          =>      '',

                    'column'        =>      'col-md-4 col-12 mb-0'

                ] ) );

                /**
                 *  Make sure post id and taxonomy not empty!
                 *  -----------------------------------------
                 */
                if( empty( $post_id ) || empty( $meta_key ) ){

                    return;
                }

                /**
                 *  Real Wedding - Meta
                 *  -------------------
                 */
                $value      =   get_post_meta( absint( $post_id ), sanitize_key( $meta_key ), true );

                /**
                 *  Real Wedding - Tax Value
                 *  ------------------------
                 */
                if( ! empty( $value ) ){

                    printf( '<div class="%1$s">

                                <div class="wedding-info-details border-bottom">

                                    <small>%2$s</small>

                                    <span>%3$s</span>

                                </div>

                            </div>',

                            /**
                             *   1. Column
                             *   ---------
                             */
                            esc_attr( $column ),

                            /**
                             *  2. Name
                             *  -------
                             */
                            esc_attr( $name ),

                            /**
                             *  3. Tax - Value
                             *  --------------
                             */
                            esc_attr( $value )
                    );
                }
            }
        }
    }

    /**
     *  SDWeddingDirectory - Singular RealWedding
     *  ---------------------------------
     */
    SDWeddingDirectory_Single_RealWedding::get_instance();
}