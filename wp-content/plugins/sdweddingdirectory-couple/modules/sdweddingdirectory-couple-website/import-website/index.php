<?php
/**
 *  SDWeddingDirectory - Create Couple
 *  --------------------------
 */
if( ! class_exists( 'SDWeddingDirectory_Admin_Page_Import_Website_Post' ) && class_exists( 'SDWeddingDirectory_Setting_Page' ) ){

    /**
     *  SDWeddingDirectory - Create Couple
     *  --------------------------
     */
    class SDWeddingDirectory_Admin_Page_Import_Website_Post extends SDWeddingDirectory_Setting_Page{

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
         *  Tab Name
         *  --------
         */
        public static function tab_name(){

            return  esc_attr__( 'Website Import', 'sdweddingdirectory-couple-website' );
        }

        /**
         *  Tab ID
         *  ------
         */
        public static function tab_id(){

            return  sanitize_title( self:: tab_name() );
        }

        /**
         *  Page Load Condition
         *  -------------------
         */
        public static function tab_enable(){

            if( isset( $_GET[ 'tab' ] ) && $_GET[ 'tab' ] == self:: tab_id() ){

                return  true;

            }else{

                return  false;
            }
        }

        /**
         *  Load Script
         *  -----------
         */
        public static function sdweddingdirectory_script(){

            /**
             *  Check Condition
             *  ---------------
             */
            if( is_admin() && self:: tab_enable() ){

                /**
                 *  Load Script
                 *  -----------
                 */
                wp_enqueue_script( 

                    /**
                     *  1. File Name
                     *  ------------
                     */
                    esc_attr(   sanitize_title( __CLASS__ )     ),

                    /**
                     *  2. File Path
                     *  ------------
                     */
                    esc_url(   plugin_dir_url( __FILE__ )   .   'script.js'   ),

                    /**
                     *  3. Load After "JQUERY" Load
                     *  ---------------------------
                     */
                    [ 'jquery' ],

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
                 *  Load Script
                 *  -----------
                 */
                wp_localize_script( esc_attr( sanitize_title( __CLASS__ ) ), 'SDWEDDINGDIRECTORY_AJAX_OBJECT', [

                    'ajaxurl'   =>  admin_url( 'admin-ajax.php' ),

                ] );
            }
        }

        /**
         *  Construct
         *  ---------
         */
        public function __construct() {

            /**
             *  SDWeddingDirectory - Setting - Tab
             *  --------------------------
             */
            add_filter( 'sdweddingdirectory/setting-page/tabs', function( $args = [] ){

                return      array_merge( $args, [

                                self:: tab_id()     =>  array(

                                    'tab'           =>  self:: tab_name(),

                                    'active'        =>  self:: tab_enable(),

                                    'callback'      =>  [ __CLASS__, 'tab_content' ]
                                ),

                            ] );

            }, absint( '60' ) );

            /**
             *  Load Script
             *  -----------
             */
            // add_action( 'admin_enqueue_scripts', [ $this, 'sdweddingdirectory_script' ] );
        }

        /**
         *  Tab Content
         *  -----------
         */
        public static function tab_content(){

          ?>

          Import Website Post Code....

          <?php
        }   

        /**
         *  Team Image
         *  ----------
         */
        public static function image(){

            $image  =   [ 1765,  1764, 1763, 1762, 1761, 1760, 1759, 1758, 1757, 1756, 1755, 1754, 1753, 1752, 1751, 1750, 1749, 1748, 1747, 1746 ];

            return  $image[ array_rand( $image, absint( '1' ) ) ];
        }

        /**
         *  Gallery Image
         *  -------------
         */
        public static function gallery_image( $images = '' ){

            if( empty( $images ) ){

                return;

            }else{

                $image   =   SDWeddingDirectory_Loader:: _filter_media_ids( $images );

                return  implode( ',', $image[ array_rand( $image, absint( '3' ) ) ] );
            }
        }

        /**
         *  Create all couple website features
         *  ----------------------------------
         */
        public static function website_post_setup(){

            /**
             *  Website Setup
             *  -------------
             */
            add_option( 'sdweddingdirectory_website_setup', absint( '0' ), '', 'yes' );

            /**
             *  Make sure it's first time load
             *  ------------------------------
             */
            if( get_option( 'sdweddingdirectory_website_setup' ) == absint( '0' ) ){

                /**
                 *  Import : Demo Hire Vendor in Real Wedding
                 *  -----------------------------------------
                 */
                $item    = new WP_Query( array(

                    'post_type'         =>  esc_attr( 'website' ), 

                    'post_status'       =>  esc_attr( 'publish', 'draft', 'pending' ),

                    'posts_per_page'    =>  -1,

                ) );

                /**
                 *  Make sure, website post type have at least one post
                 *  ---------------------------------------------------
                 */
                if( $item->found_posts == absint( '0' ) ){

                    /**
                     *  Import : Demo Hire Vendor in Real Wedding
                     *  -----------------------------------------
                     */
                    $query    = new WP_Query( array(

                        'post_type'         =>  esc_attr( 'couple' ), 

                        'post_status'       =>  esc_attr( 'publish', 'draft', 'pending' ),

                        'posts_per_page'    =>  -1,

                    ) );

                    /**
                     *  Have Post ?
                     *  -----------
                     */
                    if ( $query->have_posts() ){

                        while ( $query->have_posts() ){  $query->the_post(); 

                            $post_id                =   absint( get_the_ID() );

                            $username               =   esc_attr( get_the_title( $post_id ) );

                            $user_email             =   get_post_meta( $post_id, sanitize_key( 'user_email' ), true );

                            $wedding_date           =   get_post_meta( $post_id, sanitize_key( 'wedding_date' ), true );

                            $_real_wedding_post_id  =   apply_filters( 'sdweddingdirectory/real-wedding/post-id', 

                                                            sanitize_email( $user_email )
                                                        );

                            $bride_first_name       =   get_post_meta( absint( $_real_wedding_post_id ), sanitize_key( 'bride_first_name' ), true );

                            $bride_last_name        =   get_post_meta( absint( $_real_wedding_post_id ), sanitize_key( 'bride_last_name' ), true );

                            $groom_first_name       =   get_post_meta( absint( $_real_wedding_post_id ), sanitize_key( 'groom_first_name' ), true );

                            $bride_first_name       =   get_post_meta( absint( $_real_wedding_post_id ), sanitize_key( 'bride_first_name' ), true );

                            $groom_last_name        =   get_post_meta( absint( $_real_wedding_post_id ), sanitize_key( 'groom_last_name' ), true );

                            $bride_image            =   get_post_meta( absint( $_real_wedding_post_id ), sanitize_key( 'bride_image' ), true );

                            $groom_image            =   get_post_meta( absint( $_real_wedding_post_id ), sanitize_key( 'groom_image' ), true );

                            $page_header_banner     =   get_post_meta( absint( $_real_wedding_post_id ), sanitize_key( 'page_header_banner' ), true );

                            $featured_image         =   get_post_meta( absint( $_real_wedding_post_id ), sanitize_key( '_thumbnail_id' ), true );

                            $gallery_image          =   get_post_meta( absint( $_real_wedding_post_id ), sanitize_key( 'realwedding_gallery' ), true );

                            $_heading_description   =   'We are so excited to celebrate our special day with our family and friends. <br/> Thank you so much for visiting our wedding website!';

                            $_couple_description    =   'Sed auctor neque eu tellus rhoncus ut eleifend nibh porttitor. Ut in nulla enim. Phasellus molestie magna non est bibendum non venenatis nisl tempor. Suspendisse dictum me.';

                            $_story                 =   'Aenean imperdiet. Etiam ultricies nisi vel augue. Curabitur ullamcorper ultricies nisi. Nam eget dui. Etiam rhoncus. Maecenas tempus, tellus eget condimentum rhoncus, sem quam semper libero, sit amet adipiscing sem neque sed ipsum.';


                            /**
                             *  Real Wedding Created New Post ID
                             *  --------------------------------
                             */
                            wp_insert_post( array(

                                'post_author'       =>  absint( '1' ),

                                'post_name'         =>  esc_attr( $username ),

                                'post_title'        =>  esc_attr( $username ),

                                'post_status'       =>  esc_attr( get_post_status( $post_id ) ),

                                'post_type'         =>  esc_attr( 'website' ),

                                'post_content'      =>  sprintf( esc_attr__( 'Welcome %1$s', 'sdweddingdirectory-couple-website' ),
                                                            
                                                           /**
                                                            *  Username
                                                            *  --------
                                                            */
                                                            esc_attr( $username )
                                                        ),
                                /**
                                 *  Update MetaBox
                                 *  --------------
                                 *  @credit - https://developer.wordpress.org/reference/functions/wp_insert_post#div-comment-3682
                                 *  ---------------------------------------------------------------------------------------------
                                 */
                                'meta_input'                        =>  array(

                                    /**
                                     *  General Setting
                                     *  ---------------
                                     */
                                    'website_template_layout'       =>  esc_attr( 'website_template_layout_1' ),

                                    'user_email'                    =>  sanitize_email( $user_email ),

                                    'bride_first_name'              =>  esc_attr( $bride_first_name ),

                                    'bride_last_name'               =>  esc_attr( $bride_last_name ),

                                    'groom_first_name'              =>  esc_attr( $groom_first_name ),

                                    'bride_first_name'              =>  esc_attr( $bride_first_name ),

                                    'groom_last_name'               =>  esc_attr( $groom_last_name ),

                                    /**
                                     *  Header Section
                                     *  --------------
                                     */
                                    'header_image'                  =>  absint( $page_header_banner ),

                                    /**
                                     *  Couple Info 
                                     *  ----------
                                     */
                                    'couple_info_heading'           =>  esc_attr( 'THE COUPLE' ),

                                    'couple_info_description'       =>  $_heading_description,

                                    'bride_image'                   =>  absint( $bride_image ),

                                    'groom_image'                   =>  absint( $groom_image ),

                                    '_thumbnail_id'                 =>  absint( $featured_image ),

                                    'groom_instagram'               =>  esc_url( 'https://instagram.com/' ),

                                    'groom_facebook'                =>  esc_url( 'https://facebook.com/' ),

                                    'groom_twitter'                 =>  esc_url( 'https://twitter.com/' ),

                                    'bride_instagram'               =>  esc_url( 'https://instagram.com/' ),

                                    'bride_facebook'                =>  esc_url( 'https://facebook.com/' ),

                                    'bride_twitter'                 =>  esc_url( 'https://twitter.com/' ),

                                    /**
                                     *  Couple Story
                                     *  ------------
                                     */
                                    'couple_story_heading'              =>  esc_attr( 'OUR STORY' ),

                                    'couple_story_description'          =>  $_heading_description,

                                    'about_groom'                       =>  $_couple_description,

                                    'about_bride'                       =>  $_couple_description,

                                    'couple_story'                      =>  [

                                        [
                                            'title'             =>  esc_attr( 'First meet' ),

                                            'story_title'       =>  esc_attr( 'First meet' ),

                                            'story_overview'    =>  $_story,

                                            'story_date'        =>  date( 'Y-m-d' ),

                                            'story_image'       =>  self:: image(),
                                        ],
                                        [
                                            'title'             =>  esc_attr( 'Ring Ceremoney' ),

                                            'story_title'       =>  esc_attr( 'Ring Ceremoney' ),

                                            'story_overview'    =>  $_story,

                                            'story_date'        =>  date( 'Y-m-d' ),

                                            'story_image'       =>  self:: image(),
                                        ],
                                        [
                                            'title'             =>  esc_attr( 'Wedding Party' ),

                                            'story_title'       =>  esc_attr( 'Wedding Party' ),

                                            'story_overview'    =>  $_story,

                                            'story_date'        =>  date( 'Y-m-d' ),

                                            'story_image'       =>  self:: image(),
                                        ]
                                    ],

                                    /**
                                     *  Couple Groom
                                     *  ------------
                                     */
                                    'couple_groom_heading'              =>  esc_attr( 'THE GROOMSMEN' ),

                                    'couple_groom_description'          =>  $_heading_description,

                                    'couple_groom'                      =>  [

                                        [
                                            'title'             =>  esc_attr( 'Hitesh Mahavar' ),

                                            'groom_image'       =>  absint( '4275' ),

                                            'groom_name'        =>  esc_attr( 'Hitesh Mahavar' ),
                                        ],
                                        [
                                            'title'             =>  esc_attr( 'Jitu Chauhan' ),

                                            'groom_image'       =>  absint( '4276' ),

                                            'groom_name'        =>  esc_attr( 'Jitu Chauhan' ),
                                        ],
                                        [
                                            'title'             =>  esc_attr( 'Hitarth Shah' ),

                                            'groom_image'       =>  absint( '4277' ),

                                            'groom_name'        =>  esc_attr( 'Hitarth Shah' ),
                                        ],
                                        [
                                            'title'             =>  esc_attr( 'John Deo' ),

                                            'groom_image'       =>  absint( '4278' ),

                                            'groom_name'        =>  esc_attr( 'John Deo' ),
                                        ],
                                    ],

                                    /**
                                     *  Couple Bride
                                     *  ------------
                                     */
                                    'couple_bride_heading'              =>  esc_attr( 'THE BRIDESMAIDS' ),

                                    'couple_bride_description'          =>  $_heading_description,

                                    'couple_bride'                      =>  [

                                        [
                                            'title'             =>  esc_attr( 'Bhoomika Mahavar' ),

                                            'bride_image'       =>  absint( '4271' ),

                                            'bride_name'        =>  esc_attr( 'Bhoomika Mahavar' ),
                                        ],
                                        [
                                            'title'             =>  esc_attr( 'Komal Mahavar' ),

                                            'bride_image'       =>  absint( '4272' ),

                                            'bride_name'        =>  esc_attr( 'Komal Mahavar' ),
                                        ],
                                        [
                                            'title'             =>  esc_attr( 'Nitu Chauhan' ),

                                            'bride_image'       =>  absint( '4273' ),

                                            'bride_name'        =>  esc_attr( 'Nitu Chauhan' ),
                                        ],
                                        [
                                            'title'             =>  esc_attr( 'Martha Pearson' ),

                                            'bride_image'       =>  absint( '4274' ),

                                            'bride_name'        =>  esc_attr( 'Martha Pearson' ),
                                        ],
                                    ],

                                    /**
                                     *  Couple Testimonials
                                     *  -------------------
                                     */
                                    'couple_testimonial_heading'        =>  esc_attr( 'WHAT THEY SAY' ),

                                    'couple_testimonial_description'    =>  $_heading_description,

                                    'couple_testimonial'                =>  [

                                        [
                                            'title'       =>  esc_attr( 'Martha Pearson' ),

                                            'name'        =>  esc_attr( 'Martha Pearson' ),

                                            'content'     =>  'A man\'s got two shots for jewelry: a wedding ring and a watch. The watch is a lot easier to get on and off than a wedding ring.',
                                        ],
                                        [
                                            'title'       =>  esc_attr( 'Martha Pearson' ),

                                            'name'        =>  esc_attr( 'Martha Pearson' ),

                                            'content'     =>  'A man\'s got two shots for jewelry: a wedding ring and a watch. The watch is a lot easier to get on and off than a wedding ring.',
                                        ],
                                        [
                                            'title'       =>  esc_attr( 'Martha Pearson' ),

                                            'name'        =>  esc_attr( 'Martha Pearson' ),

                                            'content'     =>  'A man\'s got two shots for jewelry: a wedding ring and a watch. The watch is a lot easier to get on and off than a wedding ring.',
                                        ],
                                        [
                                            'title'       =>  esc_attr( 'Martha Pearson' ),

                                            'name'        =>  esc_attr( 'Martha Pearson' ),

                                            'content'     =>  'A man\'s got two shots for jewelry: a wedding ring and a watch. The watch is a lot easier to get on and off than a wedding ring.',
                                        ],
                                    ],

                                    /**
                                     *  Couple Counter
                                     *  --------------
                                     */
                                    'couple_counter_heading'        =>  esc_attr( 'Better to have loved and lost, than to have never loved at all.' ),

                                    'couple_counter_description'    =>  esc_attr( '~ St. Augustine ~' ),

                                    'couple_counter_date'           =>  esc_attr( $wedding_date ),

                                    'couple_counter_image'          =>  absint( '4279' ),

                                    /**
                                     *  Couple Gallery
                                     *  --------------
                                     */
                                    'couple_gallery_heading'            =>  esc_attr( 'CAPTURED MOMENTS' ),

                                    'couple_gallery_description'        =>  $_heading_description,

                                    'couple_gallery'                    =>  [

                                        [

                                            'title'             =>  esc_attr( 'Engagement' ),

                                            'gallery_name'      =>  esc_attr( 'Engagement' ),

                                            'gallery_image'     =>  '',

                                        ],
                                        [

                                            'title'             =>  esc_attr( 'Pre Wedding' ),

                                            'gallery_name'      =>  esc_attr( 'Pre Wedding' ),

                                            'gallery_image'     =>  '',
                                        ],
                                        [

                                            'title'             =>  esc_attr( 'With Friends' ),

                                            'gallery_name'      =>  esc_attr( 'With Friends' ),

                                            'gallery_image'     =>  ''
                                        ],
                                    ],


                                    /**
                                     *  Couple Event
                                     *  ------------
                                     */
                                    'couple_event_heading'            =>  esc_attr( 'WHEN & WHERE' ),

                                    'couple_event_description'        =>  $_heading_description,

                                    'couple_event'                    =>  [

                                        [

                                            'title'         =>  esc_attr( 'Wedding Ceremony' ),

                                            'name'          =>  esc_attr( 'Wedding Ceremony' ),

                                            'content'       =>  $_story,

                                            'date'          =>  $wedding_date,

                                            'image'         =>  absint( '0' ),

                                            'latitude'      =>  esc_attr( '23.095229442580216' ),

                                            'longitude'     =>  esc_attr( '72.61220265178257' ),

                                            'icon'          =>  esc_attr( 'sdweddingdirectory-hanging-heart' )
                                        ],

                                        [

                                            'title'         =>  esc_attr( 'Wedding Party' ),

                                            'name'          =>  esc_attr( 'Wedding Party' ),

                                            'content'       =>  $_story,

                                            'date'          =>  $wedding_date,

                                            'image'         =>  absint( '0' ),

                                            'latitude'      =>  esc_attr( '23.092596408050902' ),

                                            'longitude'     =>  esc_attr( '72.59762428054535' ),

                                            'icon'          =>  esc_attr( 'sdweddingdirectory-wine' )
                                        ],
                                    ]
                                )

                            ) );
                        }
                    }

                    /**
                     *  Reset Query
                     *  -----------
                     */
                    if ( isset( $query ) ) {

                        wp_reset_postdata();
                    }
                }

                /**
                 *  Website Setup
                 *  -------------
                 */
                update_option( 'sdweddingdirectory_website_setup', absint( '1' ) );
            }
        }
    }

    /**
     *  SDWeddingDirectory - Create Couple
     *  --------------------------
     */
    SDWeddingDirectory_Admin_Page_Import_Website_Post:: get_instance();
}