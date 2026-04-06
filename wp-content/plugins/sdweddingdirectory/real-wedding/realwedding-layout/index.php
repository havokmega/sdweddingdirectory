<?php
/**
 *  RealWedding Layout
 *  ------------------
 */
if( ! class_exists( 'SDWeddingDirectory_RealWedding' ) && class_exists( 'SDWeddingDirectory_Real_Wedding_Database' ) ){

    /**
     *  RealWedding Layout
     *  ------------------
     */
    class SDWeddingDirectory_RealWedding extends SDWeddingDirectory_Real_Wedding_Database{

        /**
         *  Member Variable
         *  ---------------
         */
        private static $instance;

        /**
         *  Member Variable
         *  ---------------
         */
        private $real_wedding_var;

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
             *  1. Real Wedding Post Filter
             *  ---------------------------
             */
            add_filter( 'sdweddingdirectory/real-wedding/post', [ $this, 'sdweddingdirectory_realwedding_layout' ], absint( '10' ), absint( '1' ) );
        }

        /**
         *  Image Alt
         *  ---------
         */
        public static function realwedding_image_alt( $post_id = '' ){

            /**
             *  Image ID
             *  --------
             */
            $media_id         =   get_post_meta( absint( $post_id ), sanitize_key( '_thumbnail_id' ), true );

            /**
             *  Return : Image Alt
             *  ------------------
             */
            return  esc_attr( parent:: _alt( array(

                        'media_id'  =>  absint( $media_id ), 

                        'post_id'   =>  absint( $post_id ),

                        'start_alt' =>  esc_attr( 'The wedding of' )

                    ) ) );
        }

        /**
         *  Media id to get link
         *  --------------------
         */
        public static function media_id_to_get_src( $media_id = '' ){

            if( empty( $media_id ) || $media_id == absint( '0' ) ){
                return;
            }

            $_have_media    =   wp_get_attachment_image_src(

                                    /**
                                     *  Media ID
                                     *  --------
                                     */
                                    absint( $media_id ),

                                    /**
                                     *  2. Size
                                     *  -------
                                     */
                                    esc_attr( 'thumbnail' )
                                );

            if( parent:: _is_array( $_have_media ) && absint( $media_id ) !== absint( '0' ) ){

                return  $_have_media[ absint( '0' ) ];

            }else{

                return  esc_url( parent:: placeholder( 'realwedding-gallery' ) );
            }
        }


        /**
         *  Load Gallery Thumb
         *  ------------------
         */
        public static function realwedding_gallery_helper( $args = [] ){

            if( parent:: _is_array( $args ) ){

                extract( $args );

                /**
                 *  Real Wedding Layout 1
                 *  ---------------------
                 */
                if( $layout == absint( '1' ) ){

                    return

                    sprintf( '  <li>

                                    <a href="%1$s">

                                        <img class="w-100" src="%2$s" alt="%3$s">

                                    </a>

                                </li>', 

                                /**
                                 *  1. Permalink Structure
                                 *  ----------------------
                                 */
                                esc_url( get_the_permalink( absint( $post_id ) ) ),

                                /**
                                 *  2. Media
                                 *  --------
                                 */
                                $media_id

                                ?   esc_url( self:: media_id_to_get_src( absint( $media_id ) ) )

                                :   parent:: placeholder( 'real-wedding-gallery' ),

                                /**
                                 *  3. Image Alt
                                 *  ------------
                                 */
                                esc_attr( parent:: _alt( array(

                                    'media_id'  =>  absint( $media_id ),

                                    'post_id'   =>  absint( $post_id ),

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
                                                        absint( $count )
                                                    )
                                ) ) )
                    );
                }

                /**
                 *  RealWedding Layout 2
                 *  --------------------
                 */
                if( $layout == absint( '2' ) ){

                    return

                    sprintf( '  <li><a href="%1$s">

                                    <div class="load-more">%2$s</div>

                                    <img class="w-100" src="%3$s" alt="%4$s">

                                </a></li>',

                                /**
                                 *  1. Permalink Structure
                                 *  ----------------------
                                 */
                                esc_url( get_the_permalink( absint( $post_id ) ) ),

                                /**
                                 *  2. Translation String
                                 *  ---------------------
                                 */
                                'Load <br> More',

                                /**
                                 *  3. Media
                                 *  --------
                                 */
                                $media_id

                                ?   esc_url( self:: media_id_to_get_src( absint( $media_id ) ) )

                                :   parent:: placeholder( 'real-wedding-gallery' ),

                                /**
                                 *  4. Image Alt
                                 *  ------------
                                 */
                                esc_attr( parent:: _alt( array(

                                    'media_id'  =>  absint( $media_id ), 

                                    'post_id'   =>  absint( $post_id ),

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
                                                        absint( $count )
                                                    )
                                ) ) )
                    );
                }
            }
        }


        /**
         *  Load Real Wedding Gallery Section  ( Post Layout )
         *  --------------------------------------------------
         */
        public static function realwedding_gallery( $post_id = '' ){

            if( empty( $post_id ) ){
                return;
            }

            /**
             *  Have Media ( Gallery ) Ids  ?
             *  -----------------------------
             */
            $media_id   =  parent:: real_wedding_gallery_ids( $post_id );

            /**
             *  Have Data ?
             *  -----------
             */
            $collection  =   '';

            $_counter   =   absint( '1' );

            /**
             *  Have Data ?
             *  -----------
             */
            if( parent:: _is_array( $media_id ) ){

                /**
                 *  Get Each Image
                 *  --------------
                 */
                foreach ( $media_id as $key => $value ) {

                    if( $_counter <= absint( '3' ) ){

                        if( $_counter == absint( '3' ) ){

                            /**
                             *  Return the Media
                             *  ----------------
                             */
                            
                            $collection  .=

                            self:: realwedding_gallery_helper( array(

                                'post_id'   =>  absint( $post_id ),

                                'media_id'  =>  absint( $value ),

                                'layout'    =>  absint( '2' ),

                                'count'     =>  absint( $_counter )

                            ) );


                        }else{

                            /**
                             *  Return the Media
                             *  ----------------
                             */
                            $collection  .=

                            self:: realwedding_gallery_helper( array(

                                'post_id'   =>  absint( $post_id ),

                                'media_id'  =>  absint( $value ),

                                'layout'    =>  absint( '1' ),

                                'count'     =>  absint( $_counter )

                            ) );
                        }
                    }
                    
                    /**
                     *  Count Number
                     *  ------------
                     */
                    $_counter++;
                }

            }else{

                /**
                 *  Default Placeholder
                 *  -------------------
                 */
                for ($i=0; $i <3 ; $i++) {

                    if( $i == absint( '3' ) ){

                        /**
                         *  Return the Media
                         *  ----------------
                         */
                        $collection  .=

                        self:: realwedding_gallery_helper( array(

                            'post_id'   =>  absint( $post_id ),

                            'media_id'  =>  absint( '0' ),

                            'layout'    =>  absint( '2' ),

                            'count'     =>  absint( $i )

                        ) );

                    }else{

                        /**
                         *  Return the Media
                         *  ----------------
                         */
                        $collection  .=

                        self:: realwedding_gallery_helper( array(

                            'post_id'   =>  absint( $post_id ),

                            'media_id'  =>  absint( '0' ),

                            'layout'    =>  absint( '1' ),

                            'count'     =>  absint( $i )

                        ) );
                    }
                }
            }

            /**
             *  Return Real Wedding Thumbnails ( Gallery )
             *  ------------------------------------------
             */
            return  sprintf( '<ul class="list-unstyled gallery">%1$s</ul>',  $collection  );
        }

        /**
         *  1. Load Real Wedding Layout
         *  ---------------------------
         */
        public static function sdweddingdirectory_realwedding_layout( $args = [] ){

            /**
             *  Have Data ?
             *  -----------
             */
            if( parent:: _is_array( $args ) ){

                /**
                 *  Extract Args
                 *  ------------
                 */
                extract( wp_parse_args( $args, [

                    'layout'            =>      absint( '1' ),

                    'post_id'           =>      absint( '0' ),

                    'collection'        =>      '',

                    'meta_query'        =>      [],

                    'meta_required'     =>      [ 'bride_first_name', 'groom_first_name', 'realwedding_gallery' ]

                ] ) );

                /**
                 *  Make sure post id not empty!
                 *  ----------------------------
                 */
                if( empty( $post_id ) ){

                    return;
                }

                /**
                 *  Have Meta ?
                 *  -----------
                 */
                if(  parent:: _is_array( $meta_required )  ){

                    foreach( $meta_required as $key => $value ){

                        /**
                         *  Venue Min Price Filter
                         *  ------------------------
                         */
                        $meta_query[]       =   array(

                                                    'key'           =>      esc_attr( $value ),

                                                    'compare'       =>      '!=',

                                                    'value'         =>      ''
                                                );
                    }
                }

                /**
                 *  Real Wedding Post Query
                 *  -----------------------
                 */
                $query   =  new WP_Query( array_merge( 

                                /**
                                 *  1. Default Args
                                 *  ---------------
                                 */
                                [   'post_type'         =>  esc_attr( 'real-wedding' ),

                                    'post_status'       =>  esc_attr( 'publish' ),

                                    'post__in'          =>  array( $post_id )
                                ],

                                /**
                                 *  2. Have Meta Query ?
                                 *  --------------------
                                 */
                                parent:: _is_array( $meta_query )

                                ?   array(

                                        'meta_query'    =>  array(

                                            'relation'  =>  'AND',

                                            $meta_query,
                                        )
                                    )

                                :   $meta_query

                            ) );

                /**
                 *  If have Post
                 *  ------------
                 */
                if ( $query->have_posts() ){

                    /**
                     *  Loop
                     *  ----
                     */
                    while ( $query->have_posts() ){  $query->the_post();

                        /**
                         *  Post ID
                         *  -------
                         */
                        $post_id    =   absint( get_the_ID() );

                        /**
                         *  Have Gallery ?
                         *  --------------
                         */
                        $have_gallery       =       parent:: _coma_to_array(

                                                        get_post_meta( $post_id, sanitize_key( 'realwedding_gallery' ), true ) 
                                                    );


                        /**
                         *  Make sure gallery have at least 3 
                         *  ---------------------------------
                         */
                        if( count( $have_gallery ) < absint( '3' ) ){

                            return;
                        }

                        /**
                         *  Layout 1
                         *  --------
                         */
                        if( $layout == absint( '1' ) ){

                            /**
                             *  Real Wedding Layout 1
                             *  ---------------------
                             */
                            $collection .=

                            sprintf( '  <div class="real-wedding-wrap">

                                            <div class="real-wedding">

                                                <div class="img">

                                                    <div class="overlay">

                                                        %9$s %1$s

                                                    </div>

                                                    <a href="%2$s"><img class="w-100" src="%3$s" alt="%8$s"></a>

                                                    <div class="date">%4$s</div>

                                                </div>

                                                %5$s <!-- Real Wedding Gallery -->

                                            </div>

                                            <h3><a href="%2$s">%6$s</a></h3>

                                            %7$s

                                            %10$s <!-- Have more Filter -->

                                        </div>',

                                        /**
                                         *  1. Translation String
                                         *  ---------------------
                                         */
                                        apply_filters( 'sdweddingdirectory/realwedding/post/caption-text',

                                            /**
                                             *  Caption
                                             *  -------
                                             */
                                            esc_attr__( 'Our Story', 'sdweddingdirectory-real-wedding' )
                                        ),

                                        /**
                                         *  2. Permalink Structure
                                         *  ----------------------
                                         */
                                        esc_url( get_the_permalink(  absint( $post_id ) ) ),

                                        /**
                                         *  3. Real Wedding Featured Image
                                         *  ------------------------------
                                         */
                                        esc_url(  parent:: realwedding_image( absint( $post_id ) )  ),

                                        /**
                                         *  4. Real Wedding Date
                                         *  --------------------
                                         */
                                        parent:: get_realwedding_date( absint( $post_id ) ),

                                        /**
                                         *  5. Real Wedding Gallery Section
                                         *  -------------------------------
                                         */
                                        self:: realwedding_gallery( absint( $post_id ) ),

                                        /**
                                         *  6. Post Title
                                         *  -------------
                                         */
                                        esc_attr( parent:: realwedding_post_title( absint( $post_id ) ) ),

                                        /**
                                         *  7. Real Wedding Location
                                         *  ------------------------ 
                                         */
                                        apply_filters( 'sdweddingdirectory/post/location', [

                                            'post_id'       =>      absint( $post_id ),

                                            'taxonomy'      =>      esc_attr( 'real-wedding-location' )

                                        ] ),

                                        /**
                                         *  8. Image Alt
                                         *  ------------
                                         */
                                        esc_attr( self:: realwedding_image_alt( absint( $post_id ) ) ),

                                        /**
                                         *  9. Real Wedding Caption Icon / Image
                                         *  ------------------------------------
                                         */
                                        apply_filters( 'sdweddingdirectory/realwedding/post/caption-icon', 

                                            /**
                                             *  Icon
                                             *  ----
                                             */
                                            '<i class="sdweddingdirectory-heart-double-alt"></i>'
                                        ),

                                        /**
                                         *  10. Have More Filter ?
                                         *  ----------------------
                                         */
                                        apply_filters( 'sdweddingdirectory/realwedding/post/bottom-section', '', $post_id )
                            );
                        }

                        /**
                         *  2. Layout 2
                         *  -----------
                         */
                        elseif( $layout == absint( '2' ) ){

                            $collection .=

                            sprintf( '  <div class="real-wedding-wrap top-heading">
                                            
                                            <div class="real-wedding">

                                                <div class="head">

                                                    <h3><a href="%2$s">%6$s</a></h3> %7$s

                                                </div>

                                                <div class="img">

                                                    <div class="overlay">

                                                        %9$s %1$s

                                                    </div>

                                                    <a href="%2$s"><img class="w-100" src="%3$s" alt="%8$s"></a>

                                                    <div class="date"> %4$s </div>

                                                </div>

                                                %5$s <!-- Real Wedding Gallery Media -->

                                                %10$s <!-- Have more Filter -->

                                            </div>                            
                                        </div>',

                                        /**
                                         *  1. Translation String
                                         *  ---------------------
                                         */
                                        apply_filters( 'sdweddingdirectory/realwedding/post/caption-text',

                                            /**
                                             *  Caption
                                             *  -------
                                             */
                                            esc_attr__( 'Our Story', 'sdweddingdirectory-real-wedding' )
                                        ),

                                        /**
                                         *  2. Permalink Structure
                                         *  ----------------------
                                         */
                                        esc_url( get_the_permalink(  absint( $post_id ) ) ),

                                        /**
                                         *  3. Real Wedding Featured Image
                                         *  ------------------------------
                                         */
                                        esc_url(  parent:: realwedding_image( absint( $post_id ) )  ),

                                        /**
                                         *  4. Real Wedding Date
                                         *  --------------------
                                         */
                                        parent:: get_realwedding_date( absint( $post_id ) ),

                                        /**
                                         *  5. Real Wedding Gallery Section
                                         *  -------------------------------
                                         */
                                        self:: realwedding_gallery( absint( $post_id ) ),

                                        /**
                                         *  6. Post Title
                                         *  -------------
                                         */
                                        esc_attr( parent:: realwedding_post_title( absint( $post_id ) ) ),

                                        /**
                                         *  7. Real Wedding Location
                                         *  ------------------------ 
                                         */
                                        apply_filters( 'sdweddingdirectory/post/location', [

                                            'post_id'       =>      absint( $post_id ),

                                            'taxonomy'      =>      esc_attr( 'real-wedding-location' ),

                                            'before'        =>      '<p>',

                                            'after'         =>      '</p>'

                                        ] ),

                                        /**
                                         *  8. Image Alt
                                         *  ------------
                                         */
                                        esc_attr( self:: realwedding_image_alt( absint( $post_id ) ) ),

                                        /**
                                         *  9. Real Wedding Caption Icon / Image
                                         *  ------------------------------------
                                         */
                                        apply_filters( 'sdweddingdirectory/realwedding/post/caption-icon', 

                                            /**
                                             *  Icon
                                             *  ----
                                             */
                                            '<i class="sdweddingdirectory-heart-double-alt"></i>'
                                        ),

                                        /**
                                         *  10. Have More Filter ?
                                         *  ----------------------
                                         */
                                        apply_filters( 'sdweddingdirectory/realwedding/post/bottom-section', '', $post_id )
                            );

                        } // Layout done
                    }   
                }

                /**
                 *  If Have Real Wedding Query to Reset
                 *  -----------------------------------
                 */
                if( isset( $query ) ){

                    wp_reset_postdata();
                }

                /**
                 *  Return RealWedding Data
                 *  -----------------------
                 */
                return      $collection;
            }
        }
    }   

    /**
     *  RealWedding Layout Object
     *  -------------------------
     */
    SDWeddingDirectory_RealWedding::get_instance();
}