<?php
/**
 *  Database information here
 *  -------------------------
 */
if( ! class_exists( 'SDWeddingDirectory_Real_Wedding_Database' ) && class_exists( 'SDWeddingDirectory_Form_Tabs' ) ){

    /**
     *  Database information here
     *  -------------------------
     */
    class SDWeddingDirectory_Real_Wedding_Database extends SDWeddingDirectory_Form_Tabs{

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
            
        }

        /**
         *  Get Real Wedding Featured Image
         *  -------------------------------
         */
        public static function realwedding_image( $post_id = '' ){

            if( empty( $post_id ) ){
                return;
            }

            /**
             *  Have Media ID ?
             *  ---------------
             */
            $_media_id      =   absint( get_post_meta( absint( $post_id ), sanitize_key( '_thumbnail_id' ), true ) );

            /**
             *  5. Get Venue Thumbnails
             *  -------------------------
             */
            if( parent:: _have_media( $_media_id ) ){

                /**
                 *  Real Wedding Image Size
                 *  -----------------------
                 */
                return  esc_url( 

                            apply_filters( 'sdweddingdirectory/media-data', [

                                'media_id'      =>  absint( $_media_id ),

                                'image_size'    =>  sanitize_key( 'sdweddingdirectory_img_500x515' ),
                            ] )
                        );
            }else{

                return  esc_url( parent:: placeholder( 'real-wedding' ) );
            }
        }

        /**
         *  2.2 Real Wedding - Photo Gallery
         *  --------------------------------
         */
        public static function real_wedding_gallery_ids( $post_id = '' ){

            if( empty( $post_id ) ){
                return;
            }

            $gallery_data    =  get_post_meta(

                                    /**
                                     *  1. Post ID
                                     *  ----------
                                     */
                                    absint( $post_id ),

                                    /**
                                     *  2. Meta Key
                                     *  -----------
                                     */
                                    sanitize_key( 'realwedding_gallery' ),

                                    /**
                                     *  3. TRUE
                                     *  -------
                                     */
                                    true
                                );

            /**
             *  1. Have Gallery Data ?
             *  ----------------------
             */
            if( ! empty( $gallery_data ) && $gallery_data !== '' && $gallery_data != '' ){

                /**
                 *  Create Array with exploe value
                 *  ------------------------------
                 */
                $_media    =   explode( ',', $gallery_data );

                /**
                 *  Is Array ?
                 *  ----------
                 */
                if( SDWeddingDirectory_Loader::_is_array( $_media ) ){

                    /**
                     *  Return Media IDs
                     *  ----------------
                     */
                    return  $_media;

                }

            }else{

                return;
            }
        }

        /**
         *  Have Location ?
         *  ---------------
         */
        public static function have_location_taxonomy( $post_id = '' ){

            if( empty( $post_id ) ){
                return;
            }

            /**
             *  Venue Location
             *  ----------------
             */
            $_have_location =

            SDWeddingDirectory_Taxonomy:: get_location_taxonomy(

                /**
                 *  Post ID
                 *  -------
                 */
                absint( $post_id ),

                /**
                 *  Taxonomoy Slug
                 *  --------------
                 */
                esc_attr( 'real-wedding-location' )
            );

            /**
             *  Return Location information
             *  ---------------------------
             */
            if( SDWeddingDirectory_Loader:: _is_array( $_have_location ) ){

                return implode( ', ' , $_have_location );
            }
        }

        /**
         *  Real Wedding Date
         *  -----------------
         */
        public static function get_realwedding_date( $post_id = '' ){

            if( empty( $post_id ) ){
                return;
            }

            $_data  =   get_post_meta(

                            /**
                             *  1. Real Wedding Post ID
                             *  -----------------------
                             */
                            absint( $post_id ), 

                            /**
                             *  2. Meta Key
                             *  -----------
                             */
                            sanitize_key( 'wedding_date' ),

                            /**
                             *  3. TRUE
                             *  -------
                             */
                            true 
                        );

            /**
             *  Have Data ?
             *  -----------
             */
            if( $_data !== '' && ! empty( $_data ) && $_data != '' ){

                return  sprintf( '<span class="real-wedding-date">%1$s</span>',

                            /**
                             *  1. Get Real Wedding Date
                             *  ------------------------
                             */
                            esc_attr( date( "M d, Y", strtotime( $_data ) ) )
                        );

            }else{

                return;
            }
        }


        /**
         *  Get Post Title 
         *  --------------
         */
        public static function realwedding_post_title( $post_id = '' ){

            if( empty( $post_id ) ){
                
                return;
            }

            /**
             *  Bride Name
             *  ----------
             */
            $_bride_name    =   esc_attr( get_post_meta( 

                                    /**
                                     *  1. Post ID
                                     *  ----------
                                     */
                                    absint( $post_id ), 

                                    /**
                                     *  2. Meta Key
                                     *  -----------
                                     */
                                    sanitize_key( 'bride_first_name' ), 

                                    /**
                                     *  3. TRUE
                                     *  -------
                                     */
                                    true 

                                ) );

            /**
             *  Groom Name
             *  ----------
             */
            $_groom_name    =   esc_attr( get_post_meta( 

                                    /**
                                     *  1. Post ID
                                     *  ----------
                                     */
                                    absint( $post_id ), 

                                    /**
                                     *  2. Meta Key
                                     *  -----------
                                     */
                                    sanitize_key( 'groom_first_name' ), 

                                    /**
                                     *  3. TRUE
                                     *  -------
                                     */
                                    true 

                                ) );

            /**
             *  Return Couple + Bride Name
             *  --------------------------
             */
            return  sprintf( '%1$s & %2$s',

                        /**
                         *  1. Bride Name
                         *  -------------
                         */
                        ( $_bride_name !== '' )

                        ?   esc_attr( $_bride_name )

                        :   esc_attr__( 'Mr', 'sdweddingdirectory-real-wedding' ),
                        
                        /**
                         *  2. Groom Name
                         *  -------------
                         */
                        ( $_groom_name !== '' ) 

                        ?   esc_attr( $_groom_name )

                        :   esc_attr__( 'Mrs', 'sdweddingdirectory-real-wedding' )
                    );
        }

        /**
         *  Total couple use this facilities in website
         *  -------------------------------------------
         */
        public static function website_couple_user( $key = '', $value = '' ){

            $meta_query     =   [];

            $meta_query[]   =   array(

                'key'       =>  esc_attr( $key ),

                'compare'   =>  esc_attr( '=' ),

                'value'     =>  esc_attr( $value )
            );

            /**
             *  Find Venue Query
             *  ------------------
             */
            $args           =   array_merge(

                /**
                 *  Default args
                 *  ------------
                 */
                array(

                    'post_type'         => esc_attr( 'real-wedding' ),

                    'post_status'       => esc_attr( 'publish' ),

                    'posts_per_page'    => -1,
                ),

                /**
                 *  2. If Have Meta Query ?
                 *  -----------------------
                 */
                parent:: _is_array( $meta_query ) 

                ?   array(

                        'meta_query'        => array(

                            'relation'  => 'AND',

                            $meta_query,
                        )
                    )

                :   []

            );

            $item       =   new WP_Query( $args );

            $output     =   sprintf( '%1$s %2$s',

                                absint( $item->found_posts ),

                                esc_attr__( 'Couples', 'sdweddingdirectory-real-wedding' )
                            );

            if( isset( $args ) ){

                wp_reset_postdata();
            }

            return $output;
        }

        /**
         *  Color Name to Get Color code via Setting Option Saved Code!
         *  -----------------------------------------------------------
         */
        public static function color_name_code( $color_name = '' ){

            if( empty( $color_name ) ){

                $color_name    =   parent:: get_realwedding_data( 'realwedding-color' );
            }

            $_color_list      =   sdweddingdirectory_option( 'realwedding-color' );

            $_color_code      =   '';

            /**
             *  Color List
             *  ----------
             */
            if( parent:: _is_array( $_color_list ) ){

                foreach( $_color_list as $key => $value ){

                    if( sanitize_title( $value[ 'title' ] ) == sanitize_title( $color_name ) ){

                        $_color_code  =  esc_attr( $value[ 'color' ] );
                    }
                }
            }

            return  esc_attr( $_color_code );
        }

        /**
         *  Season value to get season icon
         *  -------------------------------
         */
        public static function setting_option_icon( $setting_option_key = '', $name_to_get_icon = '' ){

            $collection  =   sdweddingdirectory_option( $setting_option_key );

            $icon        =   '';

            /**
             *  Color List
             *  ----------
             */
            if( parent:: _is_array( $collection ) ){

                foreach( $collection as $key => $value ){

                    if( $value[ 'title' ] == $name_to_get_icon ){

                        $icon  =  esc_attr( $value[ 'icon' ] );
                    }
                }
            }

            return  esc_attr( $icon );
        }

        /**
         *  Couple Outside Vendor Team
         *  --------------------------
         */
        public static function get_outside_vendor( $args = [] ){

            /**
             *  Extract Args
             *  ------------
             */
            extract( wp_parse_args( $args, [

                'post_id'                   =>  absint( '0' ),

                'body_content'              =>   '',

                'get_data'                  =>   '',

                'vendor_category'           =>   esc_attr__( 'Which Category Vendor', 'sdweddingdirectory-real-wedding' ),

                'vendor_business_name'      =>   esc_attr__( 'Vendor Business Name', 'sdweddingdirectory-real-wedding' ),

                'vendor_website_link'       =>   esc_attr__( 'https://vendor-website.com/', 'sdweddingdirectory-real-wedding' ),

                'category'                  =>   '',

                'company'                   =>   '',

                'website'                   =>   '',

            ] ) );

            /**
             *  Is empty!
             *  ---------
             */
            if( empty( $post_id ) ){
            
                /**
                 *  Column Start
                 *  ------------
                 */
                $body_content      .=      '<div class="col collpase_section">';

                    $body_content      .=      '<div class="card mb-4">';

                        $body_content      .=      '<div class="card-body">';

                            $body_content      .=      '<div class="row">';

                                $body_content      .=      '<div class="col-md-4">';

                                $body_content      .=      parent:: create_input_field( array(

                                                                    'name'          =>  esc_attr( 'category' ),

                                                                    'id'            =>  esc_attr( 'category' ),

                                                                    'class'         =>  esc_attr( 'category form-dark' ),

                                                                    'formgroup'     =>  false,

                                                                    'placeholder'   =>  esc_attr( $vendor_category ),

                                                                    'value'         =>  esc_attr( $category )
                                                            ) );

                                $body_content      .=      '</div>';

                                $body_content      .=      '<div class="col-md-4">';

                                $body_content      .=      parent:: create_input_field( array(

                                                                    'name'          =>  esc_attr( 'company' ),

                                                                    'id'            =>  esc_attr( 'company' ),

                                                                    'class'         =>  esc_attr( 'company form-dark' ),

                                                                    'formgroup'     =>  false,

                                                                    'placeholder'   =>  esc_attr( $vendor_business_name ),

                                                                    'value'         =>  esc_attr( $company )
                                                            ) );

                                $body_content      .=      '</div>';

                                $body_content      .=      '<div class="col-md-4">';

                                $body_content      .=      parent:: create_input_field( array(

                                                                    'name'          =>  esc_attr( 'website' ),

                                                                    'id'            =>  esc_attr( 'website' ),

                                                                    'class'         =>  esc_attr( 'website form-dark' ),

                                                                    'formgroup'     =>  false,

                                                                    'placeholder'   =>  esc_attr( $vendor_website_link ),

                                                                    'value'         =>  esc_attr( $website )
                                                            ) );

                                $body_content      .=      '</div>';

                            $body_content      .=      '</div>';

                        $body_content      .=      '</div>' . self:: removed_core_section_icon( true );

                    $body_content      .=      '</div>';
                
                $body_content      .=      '</div>';

                /**
                 *  Create Collpase
                 *  ---------------
                 */
                return  $body_content;
            }

            /**
             *  Get list of database
             *  --------------------
             */
            else{

                /**
                 *  Get Data
                 *  --------
                 */
                $_have_data          =   get_post_meta( absint( $post_id ), sanitize_key( 'out_side_vendor_credits' ), true );

                /**
                 *  Have Data ?
                 *  -----------
                 */
                if( parent:: _is_array( $_have_data ) ){

                    foreach ( $_have_data as $key => $value ) {
                    
                        /**
                         *  Create Collpase
                         *  ---------------
                         */
                        $get_data  .=  call_user_func( [ __CLASS__, __FUNCTION__ ], $value );
                    }
                }
            }

            /**
             *  Return : Data
             *  -------------
             */
            return  $get_data;
        }

        /**
         *  Real Wedding Meta query use counter
         *  -----------------------------------
         */
        public static function realwedding_meta_value_count( $args = [] ){

            /**
             *  Extract
             *  -------
             */
            extract( wp_parse_args( $args, [

                'meta_key'      =>      '',

                'meta_value'    =>      '',

            ] ) );

            /**
             *  Make sure meta query not empty!
             *  -------------------------------
             */
            if( empty( $meta_key ) || empty( $meta_value ) ){

                return  absint( '0' );
            }

            /**
             *  Meta query
             *  ----------
             */
            $query   =   new WP_Query( [

                                'post_type'         =>  esc_attr( 'real-wedding' ),

                                'post_status'       =>  esc_attr( 'publish' ),

                                'posts_per_page'    =>  -1,

                                'meta_query'        => array(

                                    'relation'  => 'AND',

                                    [   'key'       =>  esc_attr( 'realwedding-dress' ),

                                        'type'      =>  esc_attr( 'numeric' ),

                                        'compare'   =>  esc_attr( '=' ),

                                        'value'     =>  absint( $meta_value )
                                    ]
                                )
                        ] );


            /**
             *  Couple Hire this Designer 
             *  -------------------------
             */
            $_count     =   absint( $query->found_posts );

            /**
             *  Reset Query
             *  -----------
             */
            wp_reset_postdata();

            /**
             *  Return Counter
             *  --------------
             */
            return      $_count;
        }


    } /* class end **/

   /**
    *  Kicking this off by calling 'get_instance()' method
    *  ---------------------------------------------------
    */
    SDWeddingDirectory_Real_Wedding_Database:: get_instance();
}