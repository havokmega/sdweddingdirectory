<?php
/**
 *  Filter & Hooks
 *  --------------
 */
if( ! class_exists( 'SDWeddingDirectory_Review_Layout' ) && class_exists( 'SDWeddingDirectory_Reviews_Database' ) ){

    /**
     *  Filter & Hooks
     *  --------------
     */
    class SDWeddingDirectory_Review_Layout extends SDWeddingDirectory_Reviews_Database{

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
             *  Load one by one shortcode file
             *  ------------------------------
             */
            foreach ( glob( plugin_dir_path( __FILE__ ) . '/*/index.php' ) as $file ) {
           
                require_once $file;
            }

            /**
             *  Rating - Overview with Toggle
             *  -----------------------------
             */
            add_filter( 'sdweddingdirectory/rating/show-toggle', [ $this, 'show_review_toggle' ], absint( '10' ), absint( '1' ) );
        }

        /**
         *   Review Toggle Show
         *   ------------------
         */
        public static function show_review_toggle( $args = [] ){

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

                    'post_id'       =>      absint( '0' )

                ] ) );

                /**
                 *  Make sure post id not empty!
                 *  ----------------------------
                 */
                if( empty( $post_id ) ){

                    return;
                }

                /**
                 *  Show Review Toggle Bar
                 *  ----------------------
                 */
                return  

                sprintf( '  <div class="heading-wrap g-0">

                                <div class="heading">

                                    <div class="col pl-0">

                                        <h4 class="mb-0">%1$s</h4>

                                        <div class="review-option-btn">

                                           <a data-bs-toggle="collapse" href="#%2$s" role="button" aria-expanded="false" class="collapsed">
                                            
                                                <span class="stars sdweddingdirectory_review" data-review="%3$s"></span>

                                                <span>%3$s <i class="fa fa-angle-down arrow"></i></span>

                                           </a>

                                        </div>

                                    </div>
                                 
                                    <div class="col-auto">

                                        <small>%4$s</small>

                                    </div>

                                </div>

                                <div id="%2$s" class="collapse">

                                    <div class="row">

                                        %5$s <!-- Quality Review -->

                                        %6$s <!-- Facilities Review -->

                                        %7$s <!-- Staff Review -->

                                        %8$s <!-- Flexibility Review -->

                                        %9$s <!-- Value of money Review -->

                                    </div>

                                </div>

                            </div>',

                            /**
                             *  1. Show Title
                             *  -------------
                             */
                            esc_attr( $couple_name ),

                            /**
                             *  2. Review Show Toggle ID ( Toggle Purpose Only )
                             *  ------------------------------------------------
                             */
                            esc_attr( parent:: _rand() ),

                            /**
                             *  3. Get Average Review
                             *  ---------------------
                             */
                            $average,

                            /**
                             *  4. Mariage Date
                             *  ---------------
                             */
                            ! empty( $wedding_date )

                            ?   sprintf(  esc_attr__( 'Married on %1$s', 'sdweddingdirectory-reviews' ),

                                    /**
                                     *  1. Couple Wedding Date Get Through Couple Post ID
                                     *  -------------------------------------------------
                                     */
                                    apply_filters( 'sdweddingdirectory/date-format', [

                                        'date'      =>      esc_attr( $wedding_date )

                                    ] )
                                )

                            :   '',

                            /**
                             *  5. Quality Review
                             *  -----------------
                             */
                            self:: venue_review_status( array(

                                'icon'      =>  apply_filters( 'sdweddingdirectory/rating/icon/quality-of-service',

                                                    '<i class="fa fa-smile-o"></i>'
                                                ),

                                'rating'    =>  get_post_meta( absint(  $post_id ), sanitize_key( 'quality_service' ), true ),

                                'lable'     =>  esc_attr__( 'Quality of Service', 'sdweddingdirectory-reviews' )

                            ) ),

                            /**
                             *  6. Facilities Review
                             *  --------------------
                             */
                            self:: venue_review_status( array(                                   

                                'icon'      =>  apply_filters( 'sdweddingdirectory/rating/icon/responsiveness',

                                                    '<i class="fa fa-exchange"></i>'
                                                ),

                                'rating'    =>  get_post_meta( absint(  $post_id ), sanitize_key( 'facilities' ), true ),

                                'lable'     =>  esc_attr__( 'Responsiveness', 'sdweddingdirectory-reviews' )

                            ) ),

                            /**
                             *  7. Staff Review
                             *  ---------------
                             */
                            self:: venue_review_status( array(

                                'icon'      =>  apply_filters( 'sdweddingdirectory/rating/icon/professionalism',

                                                    '<i class="fa fa-male"></i>'
                                                ),

                                'rating'    =>  get_post_meta( absint(  $post_id ), sanitize_key( 'staff' ), true ),

                                'lable'     =>  esc_attr__( 'Professionalism', 'sdweddingdirectory-reviews' )

                            ) ),

                            /**
                             *  8. Flexibility Review
                             *  ---------------------
                             */
                            self:: venue_review_status( array(

                                'icon'      =>  apply_filters( 'sdweddingdirectory/rating/icon/flexibility',

                                                    '<i class="fa fa-sliders"></i>'
                                                ),

                                'rating'    =>  get_post_meta( absint(  $post_id ), sanitize_key( 'flexibility' ), true ),

                                'lable'     =>  esc_attr__( 'Flexibility', 'sdweddingdirectory-reviews' )

                            ) ),

                            /**
                             *  9. Value of money Review
                             *  ------------------------
                             */
                            self:: venue_review_status( array(

                                'icon'      =>  apply_filters( 'sdweddingdirectory/rating/icon/value-for-money',

                                                    '<i class="fa fa-dollar"></i>'
                                                ),

                                'rating'    =>  get_post_meta( absint(  $post_id ), sanitize_key( 'value_of_money' ), true ),

                                'lable'     =>  esc_attr__( 'Value for Money', 'sdweddingdirectory-reviews' )

                            ) )
                );
            }
        }

        /**
         *  Review : Comments Summary
         *  -------------------------
         */
        public static function venue_review_status( $args = [] ){

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

                    'icon'          =>      '',

                    'rating'        =>      '',

                    'lable'         =>      '',

                    'print'         =>      false,

                    'handler'       =>      ''

                ] ) );

                /**
                 *  Filter
                 *  ------
                 */
                $rating     =   str_replace( ",", ".", $rating );

                $handler    =

                sprintf(    '<div class="col-md-4">

                                <div class="review-option">
                                    
                                    <div class="icon">%1$s <span class="review-each-count">%2$s</span></div>

                                    <div class="count">

                                        <strong>%3$s</strong>

                                        <div>
                                            <div class="bar-base">

                                                <div class="bar-filled" style="width: %4$s;">&nbsp;</div>

                                            </div>
                                        </div>

                                    </div>

                                </div>

                            </div>', 

                            /**
                             *  1. Get Icon
                             *  -----------
                             */
                            $icon,

                            /**
                             *  2. Get Average Rating
                             *  ---------------------
                             */
                            $rating !== ''

                            ?   number_format( $rating, absint( '1' ) )

                            :   '',

                            /**
                             *  3. Label
                             *  --------
                             */
                            esc_attr( $lable ),

                            /**
                             *  4. Get Rating
                             *  -------------
                             */
                            $rating !== ''

                            ?   esc_attr( ( $rating * absint( '100' ) / absint( '5' ) ) . '%' )

                            :   ''
                );

                /**
                 *  Print
                 *  -----
                 */
                if( $print ){

                    print $handler;
                }

                /** 
                 *  Return Data
                 *  -----------
                 */
                else{

                    return $handler;
                }
            }
        }

    } /* class end **/

   /**
    *  Kicking this off by calling 'get_instance()' method
    *  ---------------------------------------------------
    */
    SDWeddingDirectory_Review_Layout:: get_instance();
}